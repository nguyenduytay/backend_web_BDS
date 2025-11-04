<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Repositories\LocationsRepository\LocationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Location;
use App\Repositories\BaseRepository;
use App\Repositories\LocationsRepository\LocationRepositoryInterface;
use App\Repositories\RepositoryInterface;

class LocationService
{
    protected $locationRepository;

    public function __construct(LocationRepositoryInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    public function getAllLocations()
    {
        try {
            return $this->locationRepository->all();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function search(Request $request)
    {
        try {
            $city = $request->input('city');
            $locations = $this->locationRepository->findByCity($city);
            return $locations;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function show($id)
    {
        try {
            $location = $this->locationRepository->find($id);
            return $location;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function create(Request $request)
    {
        try {
            $data = $request->all();
            $data['slug'] = Str::slug($data['city'] . '-' . $data['district']);
            $location = $this->locationRepository->create($data);
            return $location;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $data['slug'] = Str::slug($data['city'] . '-' . $data['district']);
            $updated = $this->locationRepository->update($data['id'], $data);
            return $updated;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function delete(Request $request)
    {
        try {
            return $this->locationRepository->delete($request->id);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getUniqueCities(Request $request)
    {
        try {
            $keyword = $request->input('keyword');
            $cities = $this->locationRepository->uniqueCities($keyword);
            return $cities;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function districts(string $city)
    {
        try {
            $districts = $this->locationRepository->districtsByCity($city);
            return $districts;
        } catch (\Exception $e) {
            return null;
        }
    }
}
