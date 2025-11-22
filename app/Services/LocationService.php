<?php

namespace App\Services;

use App\Repositories\LocationsRepository\LocationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationService extends BaseService
{
    protected $locationRepository;

    public function __construct(LocationRepositoryInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    public function getAllLocations()
    {
        return $this->execute(function () {
            return $this->locationRepository->all();
        }, 'LocationService::getAllLocations');
    }

    public function search(Request $request)
    {
        return $this->execute(function () use ($request) {
            $city = $request->input('city');
            return $this->locationRepository->findByCity($city);
        }, 'LocationService::search');
    }

    public function show($id)
    {
        return $this->execute(function () use ($id) {
            return $this->locationRepository->find($id);
        }, 'LocationService::show');
    }

    public function create(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $request->all();
            $data['slug'] = Str::slug($data['city'] . '-' . $data['district']);
            return $this->locationRepository->create($data);
        }, 'LocationService::create');
    }

    public function update(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $request->all();
            $data['slug'] = Str::slug($data['city'] . '-' . $data['district']);
            return $this->locationRepository->update($data['id'], $data);
        }, 'LocationService::update');
    }

    public function delete(Request $request)
    {
        return $this->execute(function () use ($request) {
            return $this->locationRepository->delete($request->id);
        }, 'LocationService::delete');
    }

    public function getUniqueCities(Request $request)
    {
        return $this->execute(function () use ($request) {
            $keyword = $request->input('keyword');
            return $this->locationRepository->uniqueCities($keyword);
        }, 'LocationService::getUniqueCities');
    }

    public function districts(string $city)
    {
        return $this->execute(function () use ($city) {
            return $this->locationRepository->districtsByCity($city);
        }, 'LocationService::districts');
    }
}
