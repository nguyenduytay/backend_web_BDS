<?php

namespace App\Repositories\UsersRepository;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;

class UsersRepository extends BaseRepository implements UsersRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }
     public function createUser(array $attributes)
    {
        $attributes['password'] = Hash::make($attributes['password']);
        return $this->model->create($attributes);
    }
     /**
     * Find user by ID
     * @param int $id
     * @return \App\Models\User|null
     */
    public function find($id)
    {
        return $this->model->select('id', 'name', 'email', 'role', 'created_at', 'updated_at')
                          ->find($id);
    }
     public function updateUser($id, array $data)
    {
        $user = $this->find($id);
        if (!$user) {
            return null;
        }

        // Nếu có password thì hash lại
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return $user;
    }
    public function deleteById($id)
    {
        $user = $this->find($id);
        if (!$user) {
            return null;
        }
        return $user->delete();
    }
}
