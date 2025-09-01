<?php
namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    public function findByUser(int $userId): Collection;
    public function findByIdAndUser(int $id, int $userId): ?Task;
    public function create(array $data): Task;
    public function update(int $id, array $data, int $userId): bool;
    public function delete(int $id, int $userId): bool;
    public function paginate(int $userId, int $perPage = 15): LengthAwarePaginator;
}
