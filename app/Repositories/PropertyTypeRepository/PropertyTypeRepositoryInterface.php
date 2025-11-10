<?php

namespace App\Repositories\PropertyTypeRepository;

use App\Repositories\RepositoryInterface;

interface PropertyTypeRepositoryInterface extends RepositoryInterface
{
    public function find($request);
}
