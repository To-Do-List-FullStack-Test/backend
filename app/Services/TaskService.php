<?php
namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Events\TaskCreated;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function createTask(array $data, int $userId): Task
    {
        $data['user_id'] = $userId;
        $task = $this->taskRepository->create($data);

        event(new TaskCreated($task));

        return $task;
    }


    public function updateTask(int $taskId, array $data, int $userId): bool
    {
        $updated = $this->taskRepository->update($taskId, $data, $userId);

        if ($updated) {
            $task = $this->taskRepository->findByIdAndUser($taskId, $userId);
            event(new TaskUpdated($task));
        }

        return $updated;
    }


    public function deleteTask(int $taskId, int $userId): bool
    {
        $task = $this->taskRepository->findByIdAndUser($taskId, $userId);

        if ($task) {
            $deleted = $this->taskRepository->delete($taskId, $userId);
            if ($deleted) {
                event(new TaskDeleted($taskId, $task->title, $userId));
            }
            return $deleted;
        }

        return false;
    }


    public function markAsCompleted(int $taskId, int $userId): bool
    {
        return $this->taskRepository->update($taskId, ['status' => Task::STATUS_COMPLETED], $userId);
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
