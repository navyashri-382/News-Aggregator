<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Your API Documentation Title",
 *      description="This is the API documentation for the news aggregator service."
 * )
 */

class AuthController extends Controller
{

/**
 * @OA\Post(
 *     path="/api/register",
 *     tags={"Authentication"},
 *     summary="Register a new user",
 *     description="Creates a new user account and returns an access token.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", example="Navyashri Shetty"),
 *             @OA\Property(property="email", type="string", format="email", example="navyashri@gmail.com"),
 *             @OA\Property(property="password", type="string", example="securepassword123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Successfully created user - Navyashri Shetty",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Successfully created user: Navyashri Shetty!"),
 *             @OA\Property(property="accessToken", type="string", example="9|ASAN31EFcnbZDEVELDCE3u3p7IWKIQQAwU1yp2j728d68ab5")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Validation failed.")
 *         )
 *     )
 * )
 */
    // User Registration
     public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = new User([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if($user->save()){
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;

            return response()->json([
            'message' => 'Successfully created user: ' . $request->name . '!',
            'accessToken'=> $token,
            ],201);
        }
        else{
            return response()->json(['error'=>'Provide proper details']);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login a user",
     *     description="Authenticates a user and returns an access token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="navyashri@hotmail.com"),
     *             @OA\Property(property="password", type="string", example="securepassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged in",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string", example="10|cSSJPjxOu2KIWv7V4XewS7gDYKAqfKrxNLKm9FC9cd227716")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized, invalid credentials",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The provided credentials are incorrect.")
     *         )
     *     )
     * )
     */
    // User Login
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
        }

        $token = $user->createToken('MyApp')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

/**
 * @OA\Post(
 *     path="/api/logout",
 *     tags={"Authentication"},
 *     summary="Log out the current user",
 *     description="Logs out the authenticated user by deleting all tokens associated with the user.",
 *     security={{"apiAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="User logged out successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User logged out successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * )
 */

    // User Logout
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully'], 200);
    }

    /**
 * @OA\Post(
 *     path="/api/resetpassword",
 *     tags={"Authentication"},
 *     summary="Reset user password",
 *     description="Allows a user to reset their password using a reset token, new password, and their email.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password", "token"},
 *             @OA\Property(property="email", type="string", format="email", example="navyashree@hotmail.com"),
 *             @OA\Property(property="password", type="string", example="navyashree#4567"),
 *             @OA\Property(property="token", type="string", example="reset_token")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset successful",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Password reset successful.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid token or expired token",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="The provided token is invalid or has expired.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="User with provided email not found.")
 *         )
 *     )
 * )
 */
    // Password Reset
    public function resetPassword(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ]);
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        if ($user->save()) {
            return response()->json(['message' => 'Password updated successfully.']);
        } else {
            return response()->json(['message' => 'Password update failed. Please try again later.'], 500);
        }
    }
    
}
