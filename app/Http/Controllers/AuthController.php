<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Admin;
use App\Models\User;

class AuthController extends Controller
{
    /**
 * Admin login
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function adminLogin(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
            'errors' => $validator->errors(),
        ], 422);
    }

    $credentials = $request->only('email', 'password');

    if (Auth::guard('admin')->attempt($credentials)) {
        $admin = Auth::guard('admin')->user();
        $token = $this->generateAdminToken($admin);

        return response()->json([
            'success' => true,
            'message' => 'Admin logged in successfully',
            'token' => $token,
            'admin' => $admin,
        ], 200);
    } else {
        $email = $request->input('email');
        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Email does not exist',
            ], 401);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password',
            ], 401);
        }
    }
}

/**
 * Admin logout
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function adminLogout(Request $request)
{
    $admin = Auth::guard('admin')->user();

    if ($admin) {
        Auth::guard('admin')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Admin logged out successfully',
        ], 200);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
        ], 401);
    }
}

/**
 * User login
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function userLogin(Request $request)
{
    $validator = Validator::make($request->all(), [
        'username' => 'required',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
            'errors' => $validator->errors(),
        ], 422);
    }

    $credentials = $request->only('username', 'password');

    if (Auth::guard('user')->attempt($credentials)) {
        $user = Auth::guard('user')->user();
        $token = $this->generateUserToken($user);

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully',
            'token' => $token,
            'user' => $user,
        ], 200);
    } else {
        $username = $request->input('username');
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Username does not exist',
            ], 401);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password',
            ], 401);
        }
    }
}

/**
 * User registration
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function userRegister(Request $request)
{
    $validator = Validator::make($request->all(), [
        'username' => 'required|unique:users|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user = new User();
    $user->username = $request->input('username');
    $user->email = $request->input('email');
    $user->password = Hash::make($request->input('password'));
    $user->save();

    $token = $this->generateUserToken($user);

    return response()->json([
        'success' => true,
        'message' => 'User registered successfully',
        'token' => $token,
        'user' => $user,
    ], 200);
}


/**
 * User logout
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function userLogout(Request $request)
{
    $user = Auth::guard('user')->user();

    if ($user) {
        Auth::guard('user')->logout();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully',
        ], 200);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
        ], 401);
    }
}

    /**
     * Generate JWT token for the admin user
     *
     * @param  \App\Models\Admin  $admin
     * @return string
     */
    private function generateAdminToken(Admin $admin)
    {
        $token = JWTAuth::fromUser($admin);
        return $token;
    }

     /**
     * Generate JWT token for the user user
     *
     * @param  \App\Models\User  $lecturer
     * @return string
     */
    private function generateUserToken(User $user)
    {
        $token = JWTAuth::fromUser($user);
        return $token;
    }

}
