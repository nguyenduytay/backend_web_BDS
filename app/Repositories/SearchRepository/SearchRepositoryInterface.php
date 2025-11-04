<?php

namespace App\Repositories\SearchRepository;

use App\Models\Property;
use App\Repositories\RepositoryInterface;

interface SearchRepositoryInterface extends RepositoryInterface
{
    public function search($keyword);

    public function filter($filters);

    public function autocomplete($keyword);

    public function nearby($request);
}
