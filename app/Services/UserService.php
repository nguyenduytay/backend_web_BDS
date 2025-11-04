<?php
namespace App\Services;

use App\Http\Validations\UserValidation;
use App\Repositories\UsersRepository\UsersRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UsersRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }
     /**
     * Create new user
     * @param array $userData
     * @return mixed
     */
    public function createUser(array $userData)
    {
        return $this->userRepository->create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'role' => $userData['role'] ?? 'user',
        ]); 
    }
    /**
     * Get user by ID
     * @param int $id
     * @return \App\Models\User|null
     */
    public function getUserById($id)
    {
        return $this->userRepository->find($id);
    }
   public function updateUser($id, array $data)
{
    return $this->userRepository->updateUser($id, $data);
}
}
