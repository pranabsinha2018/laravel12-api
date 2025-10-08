<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Registration API
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'fail',
                'message' => $validator->errors(),
            ], 400);
        }

        // Image Upload

        $imagePath = null;
        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            $file = $request->file('profile_picture');

            // Generate a unique filename
            $fileName = time().'_'.$file->getClientOriginalName();

            // Move file to the public directory
            $file->move(public_path('storage/profile'), $fileName);

            // Save the relative path to the database
            $imagePath = "storage/profile/" . $fileName;
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'User created successfully',
        ], 201);

    }

    // Login API
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'fail',
                'message' => $validator->errors(),
            ], 400);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user              = Auth::user();
            $response['token'] = $user->createToken('BlogApp')->plainTextToken;
            $response['email'] = $user->email;
            $response['name']  = $user->name;

            return response()->json([
                'status'  => 'success',
                'message' => 'Logged in successfully',
                'data'    => $response,
            ], 200);
        } else {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Invalid credentials',
            ], 400);
        }
    }

    // Logout API
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete(); // revoke all tokens
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged out successfully',
        ], 200);
    }

    // Profile API
    public function profile(): JsonResponse {

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
}
