<?php

namespace App\Repositories\PropertyTypeRepository;

use App\Models\PropertyType;
use App\Repositories\BaseRepository;

class PropertyTypeRepository extends BaseRepository implements PropertyTypeRepositoryInterface
{
    public function getModel()
    {
        return PropertyType::class;
    }
    public function find($request)
    {
        $type = $request->input('type');
        return $this->model::where('type', $type)->firstOrFail();
    }
}
