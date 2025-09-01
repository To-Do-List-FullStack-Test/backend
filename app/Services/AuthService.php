<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}


    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        if (isset($data['image'])) {
            $data['image'] = $this->handleImageUpload($data['image']);
        }

        return $this->userRepository->create($data);
    }


    public function login(array $credentials): ?string
    {
        $user = $this->userRepository->findByEmail($credentials['email']);
    
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return null;
        }
    
        return auth('api')->login($user);
    }


    public function updateProfile(int $userId, array $data): bool
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleImageUpload($data['image']);
        }

        return $this->userRepository->update($userId, $data);
    }

    
    private function handleImageUpload($image): string
    {
        return Storage::disk('public')->put('users', $image);
    }
}
