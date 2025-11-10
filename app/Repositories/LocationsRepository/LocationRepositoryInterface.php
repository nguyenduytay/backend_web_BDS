<?php

namespace App\Repositories\LocationsRepository;

use App\Models\Location;
use App\Repositories\RepositoryInterface;

interface LocationRepositoryInterface extends RepositoryInterface
{
    public function find($id);

    public function findByCity($city);

    public function uniqueCities($keyword = null);

    public function districtsByCity(string $city);
}
