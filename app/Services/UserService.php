<?php

namespace App\Services;

use App\Repositories\UsersRepository\UsersRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserService extends BaseService
{
    protected $userRepository;

    public function __construct(UsersRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        try {
            return $this->userRepository->getAll();
        } catch (Throwable $e) {
            $this->handleException($e, 'UserService::getAllUsers');
            return null;
        }
    }

    public function createUser(array $userData)
    {
        try {
            return $this->userRepository->create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'] ?? null,
                'password' => Hash::make($userData['password']),
                'role' => $userData['role'] ?? 'user',
            ]);
        } catch (Throwable $e) {
            $this->handleException($e, 'UserService::createUser');
            return null;
        }
    }

    public function getUserById($id)
    {
        try {
            return $this->userRepository->find($id);
        } catch (Throwable $e) {
            $this->handleException($e, 'UserService::getUserById');
            return null;
        }
    }

    public function updateUser($id, array $data)
    {
        try {
            return $this->userRepository->updateUser($id, $data);
        } catch (Throwable $e) {
            $this->handleException($e, 'UserService::updateUser');
            return null;
        }
    }

    public function deleteUser($id)
    {
        try {
            return $this->userRepository->delete($id);
        } catch (Throwable $e) {
            $this->handleException($e, 'UserService::deleteUser');
            return null;
        }
    }
}
