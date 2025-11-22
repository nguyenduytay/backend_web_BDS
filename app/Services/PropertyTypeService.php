<?php

namespace App\Services;

use App\Repositories\PropertyTypeRepository\PropertyTypeRepositoryInterface;
use Illuminate\Http\Request;

class PropertyTypeService extends BaseService
{
    protected $propertyTypeRepository;

    public function __construct(PropertyTypeRepositoryInterface $propertyTypeRepository)
    {
        $this->propertyTypeRepository = $propertyTypeRepository;
    }

    public function getAll()
    {
        return $this->execute(function () {
            return $this->propertyTypeRepository->all();
        }, 'PropertyTypeService::getAll');
    }

    public function getByType(Request $request)
    {
        return $this->execute(function () use ($request) {
            return $this->propertyTypeRepository->find($request);
        }, 'PropertyTypeService::getByType');
    }

    public function create(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $request->all();
            return $this->propertyTypeRepository->create($data);
        }, 'PropertyTypeService::create');
    }

    public function update(Request $request, $id)
    {
        return $this->execute(function () use ($request, $id) {
            $data = $request->all();
            return $this->propertyTypeRepository->update($id, $data);
        }, 'PropertyTypeService::update');
    }

    public function delete($id)
    {
        return $this->execute(function () use ($id) {
            return $this->propertyTypeRepository->delete($id);
        }, 'PropertyTypeService::delete');
    }
}
