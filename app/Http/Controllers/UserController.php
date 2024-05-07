<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Food;
use App\Models\UserFood;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Get the user's added foods
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyFoods()
    {
        $user = User::find(Auth::user()->id);
        $userFoods = UserFood::where('user_id', $user->id)->get();
        $foods = $userFoods->map(function ($userFood) {
            return $userFood->food;
        });

        return response()->json([
            'success' => true,
            'data' => $foods
        ], 200);
    }

    /**
     * Add a food to the user's list
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMyFood(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $food = Food::find($request->input('food_id'));

        if ($food) {
            $userFood = UserFood::create([
                'user_id' => $user->id,
                'food_id' => $food->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Food added to your list',
                'data' => $food
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Food not found'
            ], 404);
        }
    }

    /**
     * Update a food in the user's list
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMyFood(Request $request, $id)
    {
        $userFood = UserFood::find($id);

        if ($userFood) {
            $userFood->update([
                'food_id' => $request->input('food_id')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Food updated in your list',
                'data' => $userFood->food
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Food not found in your list'
            ], 404);
        }
    }

    /**
     * Delete a food from the user's list
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMyFood($id)
    {
        $userFood = UserFood::find($id);

        if ($userFood) {
            $userFood->delete();

            return response()->json([
                'success' => true,
                'message' => 'Food removed from your list'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Food not found in your list'
            ], 404);
        }
    }

    /**
     * Get the recommended foods based on the selected food
     *
     * @param int $food_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecommendedFoods($food_id)
    {
        $user = User::find(Auth::user()->id);
        $selectedFood = Food::find($food_id);

        if ($selectedFood) {
            $userFoods = $user->userFoods;
            $recommendedFoods = [];

            foreach ($userFoods as $userFood) {
                $food = $userFood->food;
                if ($food->food_class == $selectedFood->food_class && $food->sugar_content < $selectedFood->sugar_content) {
                    $recommendedFoods[] = $food;
                }
            }

            $recommendation = [
                'selected_food' => $selectedFood,
                'recommended_foods' => $recommendedFoods
            ];

            $feedback = $this->getFeedback($selectedFood->sugar_content);

            return response()->json([
                'success' => true,
                'data' => $recommendation,
                'feedback' => $feedback
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Food not found'
            ], 404);
        }
    }

    /**
     * Get the list of available foods
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewAvailableFoods()
    {
        $foods = Food::all();

        return response()->json([
            'success' => true,
            'data' => $foods
        ], 200);
    }

    /**
     * Get the feedback based on the sugar content
     *
     * @param float $sugarContent
     * @return string
     */
    private function getFeedback($sugarContent)
    {
        if ($sugarContent >= 0.7) {
            return "Strong warning: The selected food has a high sugar content.";
        } elseif ($sugarContent >= 0.3) {
            return "Warning: The selected food has a moderate sugar content.";
        } else {
            return "The selected food is okay.";
        }
    }

    /**
     * Get the user's dashboard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userDashboard()
    {
        $user = User::find(Auth::user()->id);
        $totalFoods = UserFood::where('user_id', $user->id)->count();
        $bloodSugarLevel = $user->blood_sugar_level;

        return response()->json([
            'success' => true,
            'data' => [
                'total_foods' => $totalFoods,
                'blood_sugar_level' => $bloodSugarLevel
            ]
        ], 200);
    }

    /**
     * Update the user's profile
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->blood_sugar_level = $request->input('blood_sugar_level');
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ], 200);
    }

    /**
     * View the user's profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewProfile()
    {
        $user = User::find(Auth::user()->id);

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }
}
