<?php

namespace App\Services;

use App\Http\Validations\PropertyTypeValidation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PropertyType;
use App\Repositories\PropertyTypeRepository\PropertyTypeRepositoryInterface;

class PropertyTypeService
{
    protected $propertyTypeRepository;


    public function __construct(PropertyTypeRepositoryInterface $propertyTypeRepository)
    {
        $this->propertyTypeRepository = $propertyTypeRepository;
    }

    public function getAll()
    {
        try{
            return $this->propertyTypeRepository->all();
        }catch(Exception $e){
            return null;
        }
    }

    public function getByType(Request $request)
    {
        try{
            $typeObj = $this->propertyTypeRepository->find($request);
            return $typeObj;
        }catch(Exception){
            return null;
        }

    }

    public function create(Request $request)
    {
        try{
            $data = $request->all();
            $propertyType = $this->propertyTypeRepository->create($data);
            return $propertyType;
        }catch(Exception $e){
            return null;
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $data = $request->all();
            $updated = $this->propertyTypeRepository->update($id, $data);
            return $updated;
        }catch(Exception $e){
            return null;
        }
    }

    public function delete($id)
    {
        try{
             $status = $this->propertyTypeRepository->delete($id);
            return $status;
        }catch(Exception $e){
            return null;
        }
    }
}
