<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;


class UserController extends Controller
{
    //
    public function index()
    {
        try {
            $users = User::all();

            // Return JSON response with user data
            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'data' => $users,
            ]);
        } catch (\Throwable $th) {
            // Handle exceptions
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users',
                'error' => $th->getMessage(), // Optional: Include error message for debugging
            ], 500); // HTTP status code 500 for Internal Server Error
        }
    }



}
