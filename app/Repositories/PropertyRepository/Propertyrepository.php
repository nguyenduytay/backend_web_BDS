<?php

namespace App\Repositories\PropertyRepository;

use App\Models\Property;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        Log::info('allByPropertyType START', [
            'property_type_id' => $propertyTypeId,
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 10)
        ]);
        
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);

            Log::info('allByPropertyType: Building query', ['property_type_id' => $propertyTypeId]);

            $query = $this->model::where('properties.property_type_id', $propertyTypeId)
                ->where('properties.status', 'available')
                ->whereNull('properties.deleted_at')
                ->select('properties.id', 'properties.title')
                ->with(['images']);

            Log::info('allByPropertyType: Executing query', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            $data = $query->paginate($perPage, ['*'], 'page', $page);

            Log::info('allByPropertyType SUCCESS', [
                'property_type_id' => $propertyTypeId,
                'total' => $data->total(),
                'count' => $data->count()
            ]);

            return $data;
        } catch (\Exception $e) {
            Log::error('allByPropertyType repository error', [
                'property_type_id' => $propertyTypeId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            // Fallback: trả về empty pagination
            try {
                return $this->model::where('id', 0)->paginate($perPage ?? 10, ['*'], 'page', $page ?? 1);
            } catch (\Exception $fallbackError) {
                Log::error('allByPropertyType fallback error', [
                    'error' => $fallbackError->getMessage()
                ]);
                throw $e; // Re-throw original error
            }
        }
    }
    public function allByLoaction($request)
    {
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            
            // Đơn giản hóa query: Lấy properties theo location, nhóm theo city
            // Lấy property có giá cao nhất trong mỗi city bằng cách sử dụng DISTINCT ON (PostgreSQL)
            // Hoặc đơn giản hơn: lấy tất cả properties và group trong code
            
            // Sử dụng query đơn giản hơn, tương thích với cả MySQL và PostgreSQL
            $properties = $this->model
                ->select(
                    'properties.id',
                    'properties.title',
                    'properties.price',
                    'properties.location_id',
                    'locations.city',
                    'locations.district'
                )
                ->join('locations', 'locations.id', '=', 'properties.location_id')
                ->where('properties.status', 'available')
                ->whereNull('properties.deleted_at')
                ->with(['primaryImage'])
                ->orderBy('locations.city')
                ->orderBy('properties.price', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            // Thêm total_properties cho mỗi city (tính trong code để tránh subquery phức tạp)
            if ($properties->count() > 0) {
                $cities = $properties->pluck('city')->unique();
                $cityCounts = DB::table('properties')
                    ->join('locations', 'locations.id', '=', 'properties.location_id')
                    ->where('properties.status', 'available')
                    ->whereNull('properties.deleted_at')
                    ->whereIn('locations.city', $cities)
                    ->select('locations.city', DB::raw('COUNT(*) as total'))
                    ->groupBy('locations.city')
                    ->pluck('total', 'city')
                    ->toArray();

                // Thêm total_properties vào mỗi property
                $properties->getCollection()->transform(function ($property) use ($cityCounts) {
                    $property->total_properties = $cityCounts[$property->city] ?? 0;
                    return $property;
                });
            }

            return $properties;
        } catch (\Exception $e) {
            Log::error('allByLoaction repository error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            // Fallback: trả về properties đơn giản nhất
            try {
                return $this->model
                    ->select('properties.*', 'locations.city', 'locations.district')
                    ->join('locations', 'locations.id', '=', 'properties.location_id')
                    ->where('properties.status', 'available')
                    ->whereNull('properties.deleted_at')
                    ->with(['primaryImage'])
                    ->orderBy('locations.city')
                    ->orderBy('properties.price', 'desc')
                    ->paginate($perPage, ['*'], 'page', $page);
            } catch (\Exception $fallbackError) {
                Log::error('allByLoaction fallback error: ' . $fallbackError->getMessage());
                // Trả về empty pagination
                return $this->model->where('id', 0)->paginate($perPage, ['*'], 'page', $page);
            }
        }
    }

    public function allByOutstand($request)
    {
        Log::info('allByOutstand START', [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 10)
        ]);
        
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);

            Log::info('allByOutstand: Building query');

            $query = $this->model
                ->select('properties.id', 'properties.title', 'properties.description', 'properties.price')
                ->where('properties.status', 'available')
                ->whereNull('properties.deleted_at')
                ->orderBy('properties.price', 'desc')
                ->limit(5)
                ->with(['primaryImage']);

            Log::info('allByOutstand: Executing query', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            // Nếu cần pagination, dùng paginate, nếu không thì get
            if ($perPage > 0) {
                $data = $query->paginate($perPage, ['*'], 'page', $page);
                Log::info('allByOutstand SUCCESS (paginated)', [
                    'total' => $data->total(),
                    'count' => $data->count()
                ]);
                return $data;
            } else {
                $data = $query->get();
                Log::info('allByOutstand SUCCESS (collection)', [
                    'count' => $data->count()
                ]);
                return $data;
            }
        } catch (\Exception $e) {
            Log::error('allByOutstand repository error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            // Fallback: trả về empty collection hoặc pagination
            try {
                $fallbackData = $this->model->where('id', 0)->paginate($perPage ?? 10, ['*'], 'page', $page ?? 1);
                Log::info('allByOutstand: Using fallback (empty pagination)');
                return $fallbackData;
            } catch (\Exception $fallbackError) {
                Log::error('allByOutstand fallback error', [
                    'error' => $fallbackError->getMessage(),
                    'file' => $fallbackError->getFile(),
                    'line' => $fallbackError->getLine()
                ]);
                throw $e; // Re-throw original error
            }
        }
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
