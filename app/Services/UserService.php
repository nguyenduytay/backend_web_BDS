<?php
namespace App\Services;

use App\Repositories\UsersRepository\UsersRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    protected $userRepository;

    public function __construct(UsersRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->execute(function () {
            return $this->userRepository->getAll();
        }, 'UserService::getAllUsers');
    }

    public function createUser(array $userData)
    {
        return $this->execute(function () use ($userData) {
            return $this->userRepository->create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'role' => $userData['role'] ?? 'user',
            ]);
        }, 'UserService::createUser');
    }

    public function getUserById($id)
    {
        return $this->execute(function () use ($id) {
            return $this->userRepository->find($id);
        }, 'UserService::getUserById');
    }

    public function updateUser($id, array $data)
    {
        return $this->execute(function () use ($id, $data) {
            return $this->userRepository->updateUser($id, $data);
        }, 'UserService::updateUser');
    }

    public function deleteUser($id)
    {
        return $this->execute(function () use ($id) {
            return $this->userRepository->delete($id);
        }, 'UserService::deleteUser');
    }
}
