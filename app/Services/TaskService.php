<?php
namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Services\NotificationService;
use App\Events\TaskCreated;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private NotificationService $notificationService
    ) {}

    public function createTask(array $data, int $userId): Task
    {
        $data['user_id'] = $userId;
        $task = $this->taskRepository->create($data);

        $user = User::find($userId);

        $this->notificationService->create(
            $userId,
            'Task Created',
            "Task '{$task->title}' has been created successfully!",
            'success',
            [
                'task_id' => $task->id,
                'task_title' => $task->title,
                'task_status' => $task->status,
                'created_at' => $task->created_at->toISOString()
            ]
        );

        event(new TaskCreated($task));

        return $task;
    }


    public function updateTask(int $taskId, array $data, int $userId): bool
    {
         $updated = $this->taskRepository->update($taskId, $data, $userId);

        if ($updated) {
            $task = $this->taskRepository->findByIdAndUser($taskId, $userId);
            if ($task) {
                $this->notificationService->create(
                    $userId,
                    'Task Updated',
                    "Task '{$task->title}' has been updated successfully!",
                    'info',
                    [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'task_status' => $task->status,
                        'updated_at' => now()->toISOString()
                    ]
                );
            }
        }

        return $updated;
    }


    public function deleteTask(int $taskId, int $userId): bool
    {
          $task = $this->taskRepository->findByIdAndUser($taskId, $userId);

        if ($task) {
            $deleted = $this->taskRepository->delete($taskId, $userId);

            if ($deleted) {
                $this->notificationService->create(
                    $userId,
                    'Task Deleted',
                    "Task '{$task->title}' has been deleted successfully!",
                    'warning',
                    [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        
                    ]
                );
            }

            return $deleted;
        }

        return false;
    }


    public function markAsCompleted(int $taskId, int $userId): bool
    {
        $updated = $this->taskRepository->update($taskId, ['status' => Task::STATUS_COMPLETED], $userId);

        if ($updated) {
            $task = $this->taskRepository->findByIdAndUser($taskId, $userId);
            if ($task) {
                $this->notificationService->create(
                    $userId,
                    'Task Completed',
                    "Congratulations! Task '{$task->title}' has been completed!",
                    'success',
                    [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'completed_at' => now()->toISOString()
                    ]
                );
            }
        }

        return $updated;
    }


    public function getUserTasks(int $userId): Collection
    {
        return $this->taskRepository->findByUser($userId);
    }


    public function getUserTasksPaginated(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->taskRepository->paginate($userId, $perPage);
    }


}
