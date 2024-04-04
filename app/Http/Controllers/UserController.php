<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; // Import Validator
use Illuminate\Support\Facades\Hash; // Import Hash
use Illuminate\Validation\ValidationException; // Import ValidationException
use App\Models\User; // Import User model
use Laravel\Sanctum\PersonalAccessToken; // Import PersonalAccessToken


/**
 * @OA\Info(
 *     title="Business Card API",
 *     description="This API allows users to create, update, and delete business cards.",
 *     version="1.0.0"
 * )
 */

class UserController extends Controller
{
    /**
     * Register a new user.
     *
     * @OA\Post(
     *     path="/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration data",
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     )
     * )
     */

    public function register(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Log in user.
     *
     * @OA\Post(
     *     path="/login",
     *     summary="Log in user",
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function login(Request $request){
        try{
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->erroes()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email','password']))){
                return response()->json([
                    'status'=>false,
                    'message'=>'Email or password incorrect'
                ],401);
            }

            $user=USer::where('email',$request->email)->first();
            return response()->json([
                'status'=>true,
                'message'=>'User logged in succesfully',
                'token'=>$user->createToken("API TOKEN")->plainTextToken
            ],200);
        }catch (\throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Log out user.
     *
     * @OA\Post(
     *     path="/logout",
     *     summary="Log out user",
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'User logged out successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
