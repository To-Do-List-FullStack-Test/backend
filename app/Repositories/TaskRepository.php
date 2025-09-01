<?php
namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
    public function findByUser(int $userId): Collection
    {
        return Task::where('user_id', $userId)
                  ->orderBy('created_at', 'desc')
                  ->get();
    }

    public function findByIdAndUser(int $id, int $userId): ?Task
    {
        return Task::where('id', $id)
                  ->where('user_id', $userId)
                  ->first();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data, int $userId): bool
    {
        return Task::where('id', $id)
                  ->where('user_id', $userId)
                  ->update($data) > 0;
    }

    public function delete(int $id, int $userId): bool
    {
        return Task::where('id', $id)
                  ->where('user_id', $userId)
                  ->delete() > 0;
    }

    public function paginate(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Task::where('user_id', $userId)
                  ->with('category')
                  ->orderBy('created_at', 'desc')
                  ->paginate($perPage);
    }
}
