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
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            
            // Đơn giản hóa query: Lấy properties theo location, nhóm theo city
            // Lấy property có giá cao nhất trong mỗi city
            $properties = $this->model
                ->select(
                    'properties.id',
                    'properties.title',
                    'properties.price',
                    'properties.location_id',
                    'locations.city',
                    DB::raw('(
                        SELECT COUNT(*) 
                        FROM properties p2 
                        INNER JOIN locations l2 ON l2.id = p2.location_id
                        WHERE l2.city = locations.city
                        AND p2.status = \'available\' 
                        AND p2.deleted_at IS NULL
                    ) as total_properties')
                )
                ->join('locations', 'locations.id', '=', 'properties.location_id')
                ->where('properties.status', 'available')
                ->whereNull('properties.deleted_at')
                ->whereRaw('properties.price = (
                    SELECT MAX(p3.price)
                    FROM properties p3
                    INNER JOIN locations l3 ON l3.id = p3.location_id
                    WHERE l3.city = locations.city
                    AND p3.status = \'available\'
                    AND p3.deleted_at IS NULL
                )')
                ->with(['primaryImage'])
                ->orderBy('locations.city')
                ->orderBy('properties.price', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return $properties;
        } catch (\Exception $e) {
            \Log::error('allByLoaction repository error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            // Fallback: trả về properties đơn giản hơn
            return $this->model
                ->select('properties.*', 'locations.city')
                ->join('locations', 'locations.id', '=', 'properties.location_id')
                ->where('properties.status', 'available')
                ->whereNull('properties.deleted_at')
                ->with(['primaryImage'])
                ->orderBy('locations.city')
                ->orderBy('properties.price', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
        }
    }

    public function allByOutstand($request)
    {
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
