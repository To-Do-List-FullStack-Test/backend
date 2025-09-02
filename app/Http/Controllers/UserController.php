<?php
namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Services\AuthService;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private NotificationService $notificationService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->register($request->validated());

            $token = JWTAuth::fromUser($user);

            $this->notificationService->create(
                $user->id,
                'Welcome !',
                'Your account has been created successfully. Welcome to the application!',
                'success'
            );

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'full_name' => $user->full_name,
                        'email' => $user->email,
                        'phone_number' => $user->phone_number,
                        'address' => $user->address,
                        'image_url' => $user->image ? asset('storage/' . $user->image) : null,
                    ],
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => config('jwt.ttl') * 60
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during registration',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }


    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();

            if (!$token = auth()->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $user = auth()->user();

            return response()->json([
                'success' => true,
                'message' => 'Connection successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'full_name' => $user->full_name,
                        'email' => $user->email,
                        'phone_number' => $user->phone_number,
                        'address' => $user->address,
                        'image_url' => $user->image ? asset('storage/' . $user->image) : null,
                    ],
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during connection',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }


    public function logout(): JsonResponse
    {
        try {
            auth()->logout();

            return response()->json([
                'success' => true,
                'message' => 'Disconnection successful'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during disconnection'
            ], 500);
        }
    }

    public function refresh(): JsonResponse
    {
        try {
            $token = auth()->refresh();

            return response()->json([
                'success' => true,
                'data' => [
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during token refresh'
            ], 500);
        }
    }

    public function user(): JsonResponse
    {
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'address' => $user->address,
                'image_url' => $user->image ? asset('storage/' . $user->image) : null,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }


    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $updated = $this->authService->updateProfile(
                auth()->id(),
                $request->validated()
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error during profile update'
                ], 500);
            }

            $user = auth()->user()->fresh();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'address' => $user->address,
                    'image_url' => $user->image ? asset('storage/' . $user->image) : null,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during profile update',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }
}
