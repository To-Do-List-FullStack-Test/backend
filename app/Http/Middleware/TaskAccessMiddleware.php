<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TaskAccessMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (!Auth::guard('api')->check()) {
                Log::warning('Unauthorized access attempt to tasks endpoint', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'path' => $request->path(),
                    'method' => $request->method()
                ]);
                
                return $this->unauthorizedResponse(
                    'Unauthorized. JWT token required.',
                    'MISSING_JWT_TOKEN',
                    401
                );
            }

            $token = $request->bearerToken();
            if (!$token) {
                Log::warning('Missing Bearer token in request', [
                    'ip' => $request->ip(),
                    'path' => $request->path(),
                    'method' => $request->method()
                ]);
                
                return $this->unauthorizedResponse(
                    'Unauthorized. Bearer token required.',
                    'MISSING_BEARER_TOKEN',
                    401
                );
            }

            $user = Auth::guard('api')->user();
            if (!$user) {
                Log::error('Invalid JWT token provided', [
                    'ip' => $request->ip(),
                    'path' => $request->path(),
                    'method' => $request->method()
                ]);
                
                return $this->unauthorizedResponse(
                    'Invalid JWT token.',
                    'INVALID_JWT_TOKEN',
                    401
                );
            }

          
            $request->merge(['current_user_id' => $user->id]);
            
            $request->merge(['authenticated_user' => $user]);

            if ($request->route('task')) {
                $taskId = $request->route('task');
                $hasAccess = $this->checkTaskAccess($user->id, $taskId);
                
                if (!$hasAccess) {
                    Log::warning('User attempted to access task not belonging to them', [
                        'user_id' => $user->id,
                        'task_id' => $taskId,
                        'ip' => $request->ip(),
                        'path' => $request->path()
                    ]);
                    
                    return $this->unauthorizedResponse(
                        'Access denied. You can only access your own tasks.',
                        'TASK_ACCESS_DENIED',
                        403
                    );
                }
            }

            Log::info('Authorized task access', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'path' => $request->path(),
                'method' => $request->method(),
                'ip' => $request->ip()
            ]);

            return $next($request);

        } catch (\Exception $e) {
            Log::error('TaskAccessMiddleware error: ' . $e->getMessage(), [
                'exception' => $e,
                'ip' => $request->ip(),
                'path' => $request->path(),
                'method' => $request->method()
            ]);
            
            return $this->unauthorizedResponse(
                'Token validation failed.',
                'TOKEN_VALIDATION_FAILED',
                500
            );
        }
    }

 
    private function checkTaskAccess(int $userId, int $taskId): bool
    {
        try {
            $task = \App\Models\Task::where('id', $taskId)
                ->where('user_id', $userId)
                ->first();
            
            return $task !== null;
        } catch (\Exception $e) {
            Log::error('Error checking task access', [
                'user_id' => $userId,
                'task_id' => $taskId,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

  
    private function unauthorizedResponse(string $message, string $errorCode, int $statusCode): Response
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => $errorCode,
            'timestamp' => now()->toISOString(),
            'path' => request()->path(),
            'method' => request()->method()
        ], $statusCode);
    }
}