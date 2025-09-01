<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $perPage = $request->get('per_page', 15);

            $tasks = $this->taskService->getUserTasksPaginated($userId, $perPage);

            return response()->json([
                'tasks' => $tasks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $task = $this->taskService->createTask($request->validated(), $userId);

            return response()->json([
                'message' => 'Task created successfully',
                'task' => $task
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Task creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $userId = Auth::id();
            $tasks = $this->taskService->getUserTasks($userId);
            $task = $tasks->find($id);

            if (!$task) {
                return response()->json([
                    'message' => 'Task not found'
                ], 404);
            }

            return response()->json([
                'task' => $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        try {
            $userId = Auth::id();
            $updated = $this->taskService->updateTask($id, $request->validated(), $userId);

            if (!$updated) {
                return response()->json([
                    'message' => 'Task not found or update failed'
                ], 404);
            }

            return response()->json([
                'message' => 'Task updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Task update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $userId = Auth::id();
            $deleted = $this->taskService->deleteTask($id, $userId);

            if (!$deleted) {
                return response()->json([
                    'message' => 'Task not found or deletion failed'
                ], 404);
            }

            return response()->json([
                'message' => 'Task deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Task deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markCompleted(int $id): JsonResponse
    {
        try {
            $userId = Auth::id();
            $updated = $this->taskService->markAsCompleted($id, $userId);

            if (!$updated) {
                return response()->json([
                    'message' => 'Task not found or update failed'
                ], 404);
            }

            return response()->json([
                'message' => 'Task marked as completed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to mark task as completed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
