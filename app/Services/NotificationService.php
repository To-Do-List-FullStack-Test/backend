<?php
namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function create(int $userId, string $title, string $message, string $type = 'info', ?array $data = null): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data,
        ]);
    }

    public function markAsRead(int $notificationId, int $userId): bool
    {
        return Notification::where('id', $notificationId)
                          ->where('user_id', $userId)
                          ->update([
                              'is_read' => true,
                              'read_at' => now()
                          ]) > 0;
    }


    public function markAllAsRead(int $userId): bool
    {
        return Notification::where('user_id', $userId)
                          ->where('is_read', false)
                          ->update([
                              'is_read' => true,
                              'read_at' => now()
                          ]) > 0;
    }


    public function getUnread(int $userId)
    {
        return Notification::where('user_id', $userId)
                          ->where('is_read', false)
                          ->orderBy('created_at', 'desc')
                          ->get();
    }


    public function taskCreatedNotification(User $user, $taskTitle): Notification
    {
        return $this->create(
            $user->id,
            'Task created',
            "Task '{$taskTitle}' created with success",
            'success',
            ['task_title' => $taskTitle]
        );
    }
}
