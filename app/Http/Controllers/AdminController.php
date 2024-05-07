<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Food;

class AdminController extends Controller
{
    /**
     * List all users
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listUsers()
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'data' => $users
        ], 200);
    }

    /**
     * View a user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewUser($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    /**
     * Update a user
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $user->update([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'blood_sugar_level' => $request->input('blood_sugar_level')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    /**
     * Delete a user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

     /**
     * List all foods
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listFoods()
    {
        $foods = Food::all();

        return response()->json([
            'success' => true,
            'data' => $foods
        ], 200);
    }

    /**
     * Create a new food
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFood(Request $request)
    {
        $food = Food::create([
            'name' => $request->input('name'),
            'food_class' => $request->input('food_class'),
            'sugar_content' => $request->input('sugar_content')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Food created successfully',
            'data' => $food
        ], 201);
    }

    /**
     * Update a food
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateFood(Request $request, $id)
    {
        $food = Food::find($id);

        if ($food) {
            $food->update([
                'name' => $request->input('name'),
                'food_class' => $request->input('food_class'),
                'sugar_content' => $request->input('sugar_content')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Food updated successfully',
                'data' => $food
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Food not found'
            ], 404);
        }
    }

    /**
     * Delete a food
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFood($id)
    {
        $food = Food::find($id);

        if ($food) {
            $food->delete();

            return response()->json([
                'success' => true,
                'message' => 'Food deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Food not found'
            ], 404);
        }
    }


    /**
     * Get the admin dashboard data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminDashboard()
    {
        $totalUsers = User::count();
        $totalFoods = Food::count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'total_foods' => $totalFoods
            ]
        ], 200);
    }


}
