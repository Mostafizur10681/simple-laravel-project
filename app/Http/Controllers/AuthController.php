<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Nette\Schema\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {
            // Custom error messages for validation
            $messages = [
                'name.required' => 'The name field is required.',
                'name.max' => 'The name must not exceed 255 characters.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.max' => 'The email must not exceed 255 characters.',
                'email.unique' => 'This email is already registered. Please use a different email.',
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 8 characters.',
            ];

            // Validation rules
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ], $messages);

            // Create the user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Return success response
            return response()->json([
                'message' => 'Registration successful.',
                'user' => $user,
            ], 201);
        } catch (ValidationException $e) {
            // Return validation error messages
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422); // HTTP 422 Unprocessable Entity
        } catch (\Exception $e) {
            // Catch other exceptions
            return response()->json([
                'message' => 'An error occurred during registration.',
                'error' => $e->getMessage(),
            ], 500); // HTTP 500 Internal Server Error
        }
    }




    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function user(Request $request)
    {
        $user =  User::all();
        $userInfo = UserInfo::all();
        return response()->json($user);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }

}
