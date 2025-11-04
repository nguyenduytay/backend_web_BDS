<?php
namespace App\Repositories\UsersRepository;

use App\Repositories\RepositoryInterface;

interface UsersRepositoryInterface extends RepositoryInterface
{
     public function updateUser($id, array $data);
}
