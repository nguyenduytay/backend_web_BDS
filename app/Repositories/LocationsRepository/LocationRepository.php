<?php

namespace App\Repositories\LocationsRepository;

use App\Models\Location;
use App\Repositories\BaseRepository;
use App\Repositories\LocationsRepository\LocationRepositoryInterface;

class LocationRepository extends BaseRepository implements LocationRepositoryInterface
{
    public function getModel()
    {
        return Location::class;
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findByCity($city)
    {
        return $this->model
            ->whereRaw('LOWER(city) LIKE ?', ['%' . mb_strtolower($city, 'UTF-8') . '%'])
            ->get();
    }

    public function uniqueCities($keyword = null)
    {
        $query = $this->model->select('city')->distinct();
        if ($keyword) {
            $query->where('city', 'LIKE', '%' . $keyword . '%');
        }
        return $query->pluck('city');
    }

    public function districtsByCity(string $city)
    {
        return $this->model
            ->where('city', $city)
            ->select('district')
            ->distinct()
            ->pluck('district');
    }
}
