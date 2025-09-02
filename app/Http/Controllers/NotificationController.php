<?php
namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {}


    public function index(Request $request): JsonResponse
    {
        try {
            $userId = auth()->id();
            $onlyUnread = $request->boolean('unread', false);

            if ($onlyUnread) {
                $notifications = $this->notificationService->getUnread($userId);
            } else {
                $notifications = auth()->user()->notifications()->paginate(20);
            }

            return response()->json([
                'success' => true,
                'data' => $notifications->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'type' => $notification->type,
                        'is_read' => $notification->is_read,
                        'data' => $notification->data,
                        'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                        'read_at' => $notification->read_at?->format('Y-m-d H:i:s'),
                    ];
                }),
                'unread_count' => $this->notificationService->getUnread($userId)->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during notification retrieval'
            ], 500);
        }
    }


    public function markAsRead(int $id): JsonResponse
    {
        try {
            $marked = $this->notificationService->markAsRead($id, auth()->id());

            if (!$marked) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during notification update'
            ], 500);
        }
    }


    public function markAllAsRead(): JsonResponse
    {
        try {
            $this->notificationService->markAllAsRead(auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'All notifications have been marked as read'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during notification update'
            ], 500);
        }
    }


    public function unreadCount(): JsonResponse
    {
        try {
            $count = $this->notificationService->getUnread(auth()->id())->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'unread_count' => $count
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during notification retrieval'
            ], 500);
        }
    }
}
