<?php

namespace App\Repositories\PropertyRepository;

use App\Models\Property;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class PropertyRepository extends BaseRepository implements PropertyRepositoryInterface
{
    public function getModel()
    {
        return Property::class;
    }
    public function allProperty($request)
    {
        $query = $this->model::query();

        // lọc theo title nếu có
        if ($request->has('title') && !empty($request->title)) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // lọc theo location_id nếu có
        if ($request->has('location_id') && !empty($request->location_id)) {
            $query->where('location_id', $request->location_id);
        }

        // lọc theo price nếu có
        if ($request->has('price_min') && !empty($request->price_min)) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && !empty($request->price_max)) {
            $query->where('price', '<=', $request->price_max);
        }

        // số bản ghi trên mỗi trang (mặc định = 10)
        $perPage = $request->get('pagination', 10);

        $properties = $query->paginate($perPage);
        return $properties;
    }

    public function find($id)
    {
        return $this->model::with(['location', 'contact', 'features'])->findOrFail($id);
    }
    public function allByPropertyType($propertyTypeId, $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $query = $this->model::where('property_type_id', $propertyTypeId)
            ->select('id', 'title')
            ->where('properties.status', 'available')
            ->with(['images']);

        $data = $query->paginate($perPage, ['*'], 'page', $page);

        return $data;
    }
    public function allByLoaction($request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        // Subquery lấy max price của property available
        $subqueryMaxPrice = Property::select('lc.city', DB::raw('MAX(properties.price) as max_price'))
            ->join('locations as lc', 'lc.id', '=', 'properties.location_id')
            ->whereNull('properties.deleted_at')
            ->where('properties.status', 'available')
            ->groupBy('lc.city');

        // Subquery đếm số property available theo city
        $subqueryCount = Property::select('lc.city', DB::raw('COUNT(*) as total_properties'))
            ->join('locations as lc', 'lc.id', '=', 'properties.location_id')
            ->whereNull('properties.deleted_at')
            ->where('properties.status', 'available')
            ->groupBy('lc.city');

        $properties = $this->model
            ->where('properties.status', 'available')
            ->join('locations as lc', 'lc.id', '=', 'properties.location_id')
            ->joinSub($subqueryMaxPrice, 'top_price', function ($join) {
                $join->on('lc.city', '=', 'top_price.city')
                    ->on('properties.price', '=', 'top_price.max_price');
            })
            ->joinSub($subqueryCount, 'city_count', function ($join) {
                $join->on('lc.city', '=', 'city_count.city');
            })
            ->whereNull('properties.deleted_at')
            ->select(
                'properties.id',
                'properties.title',
                'properties.price',
                'properties.location_id',
                'lc.city',
                'city_count.total_properties'
            )
            ->with(['primaryImage'])
            ->paginate($perPage, ['*'], 'page', $page);

        return $properties;
    }

    public function allByOutstand($request) {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        return $this->model->select('id', 'title', 'description')
        ->where('properties.status', 'available')
        ->orderBy('price', 'desc')
        ->take(5)
        ->with(['primaryImage'])
        ->paginate($perPage, ['*'], 'page', $page);
    }

    public function restore($id)
    {
        $property = $this->model::withTrashed()->findOrFail($id);
        $property->restore();
        return $property;
    }

    public function forceDelete($id)
    {
        $property = $this->model::withTrashed()->findOrFail($id);
        $property->forceDelete();
        return $property;
    }
    public function getPropertiesByUser($userId)
    {
        return $this->model::where('created_by', $userId)->get();
    }
}
