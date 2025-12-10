<?php

namespace App\Services;

use App\Repositories\PropertyTypeRepository\PropertyTypeRepositoryInterface;
use Illuminate\Http\Request;
use Throwable;

class PropertyTypeService extends BaseService
{
    protected $propertyTypeRepository;

    public function __construct(PropertyTypeRepositoryInterface $propertyTypeRepository)
    {
        $this->propertyTypeRepository = $propertyTypeRepository;
    }

    public function getAll()
    {
        try {
            return $this->propertyTypeRepository->all();
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyTypeService::getAll');
            return null;
        }
    }

    public function getByType(Request $request)
    {
        try {
            return $this->propertyTypeRepository->find($request);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyTypeService::getByType');
            return null;
        }
    }

    public function create(Request $request)
    {
        try {
            $data = $request->all();
            return $this->propertyTypeRepository->create($data);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyTypeService::create');
            return null;
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            return $this->propertyTypeRepository->update($id, $data);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyTypeService::update');
            return null;
        }
    }

    public function delete($id)
    {
        try {
            return $this->propertyTypeRepository->delete($id);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyTypeService::delete');
            return null;
        }
    }
}
