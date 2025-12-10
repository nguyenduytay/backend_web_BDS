<?php

namespace App\Services;

use App\Repositories\LocationsRepository\LocationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class LocationService extends BaseService
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
        } catch (Throwable $e) {
            $this->handleException($e, 'LocationService::getAllLocations');
            return null;
        }
    }

    public function search(Request $request)
    {
        try {
            $city = $request->input('city');
            return $this->locationRepository->findByCity($city);
        } catch (Throwable $e) {
            $this->handleException($e, 'LocationService::search');
            return null;
        }
    }

    public function show($id)
    {
        try {
            return $this->locationRepository->find($id);
        } catch (Throwable $e) {
            $this->handleException($e, 'LocationService::show');
            return null;
        }
    }

    public function create(Request $request)
    {
        try {
            $data = $request->all();
            $data['slug'] = Str::slug($data['city'] . '-' . $data['district']);
            return $this->locationRepository->create($data);
        } catch (Throwable $e) {
            $this->handleException($e, 'LocationService::create');
            return null;
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $data['slug'] = Str::slug($data['city'] . '-' . $data['district']);
            return $this->locationRepository->update($data['id'], $data);
        } catch (Throwable $e) {
            $this->handleException($e, 'LocationService::update');
            return null;
        }
    }

    public function delete(Request $request)
    {
        try {
            return $this->locationRepository->delete($request->id);
        } catch (Throwable $e) {
            $this->handleException($e, 'LocationService::delete');
            return null;
        }
    }

    public function getUniqueCities(Request $request)
    {
        try {
            $keyword = $request->input('keyword');
            return $this->locationRepository->uniqueCities($keyword);
        } catch (Throwable $e) {
            $this->handleException($e, 'LocationService::getUniqueCities');
            return null;
        }
    }

    public function districts(string $city)
    {
        try {
            return $this->locationRepository->districtsByCity($city);
        } catch (Throwable $e) {
            $this->handleException($e, 'LocationService::districts');
            return null;
        }
    }
}
