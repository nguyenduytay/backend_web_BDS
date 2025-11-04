<?php

namespace App\Repositories\SearchRepository;

use App\Models\Property;
use App\Repositories\BaseRepository;

class SearchRepository extends BaseRepository implements SearchRepositoryInterface
{
    public function getModel()
    {
        return Property::class;
    }
    public function search($request)
    {
        $keyword = $request->input('q');        // từ khóa tìm kiếm
        $perPage = $request->input('per_page', 10); // số lượng mỗi trang (mặc định 10)
        $page    = $request->input('page', 1);      // trang hiện tại (mặc định 1)

        return $this->model::where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('description', 'LIKE', "%{$keyword}%")
            ->orWhere('address', 'LIKE', "%{$keyword}%")
            ->with(['location', 'images'])
            ->paginate($perPage, ['*'], 'page', $page);
    }


    public function filter($request)
    {
        $filters = $request->all();

        $query = $this->model::query();

        if (!empty($filters['property_type_id'])) {
            if (is_array($filters['property_type_id'])) {
                $query->whereIn('property_type_id', $filters['property_type_id']);
            } else {
                $query->where('property_type_id', $filters['property_type_id']);
            }
        }

        if (!empty($filters['location_id'])) {
            if (is_array($filters['location_id'])) {
                $query->whereIn('location_id', $filters['location_id']);
            } else {
                $query->where('location_id', $filters['location_id']);
            }
        }
        if (!empty($filters['feature_id'])) {
            $query->whereHas('features', function ($q) use ($filters) {
                $q->whereIn('features.id', $filters['feature_id']);
            });
        }

        if (!empty($filters['price_min'])) {
            $query->where('price', '>=', $filters['price_min']);
        }

        if (!empty($filters['price_max'])) {
            $query->where('price', '<=', $filters['price_max']);
        }

        if (!empty($filters['bedrooms_min'])) {
            $query->where('bedrooms', '>=', $filters['bedrooms_min']);
        }

        if (!empty($filters['bedrooms_max'])) {
            $query->where('bedrooms', '<=', $filters['bedrooms_max']);
        }

        if (!empty($filters['bathrooms_min'])) {
            $query->where('bathrooms', '>=', $filters['bathrooms_min']);
        }

        if (!empty($filters['bathrooms_max'])) {
            $query->where('bathrooms', '<=', $filters['bathrooms_max']);
        }
        if (!empty($filters['area_min'])) {
            $query->where('area', '>=', $filters['area_min']);
        }

        if (!empty($filters['area_max'])) {
            $query->where('area', '<=', $filters['area_max']);
        }

        // Lấy số lượng mỗi trang và trang hiện tại từ request
        $perPage = $request->input('per_page', 10);
        $page    = $request->input('page', 1);

        return $query->with(['location', 'images', 'features'])
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function autocomplete($request)
    {
        $keyword = $request->input('q', '');         // lấy từ khóa tìm kiếm
        $perPage = $request->input('per_page', 10);  // số item mỗi trang (default 10)
        $page    = $request->input('page', 1);       // trang hiện tại (default 1)

        $query = Property::where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('address', 'LIKE', "%{$keyword}%");

        return $query->with(['location', 'images', 'features'])
            ->paginate($perPage, ['*'], 'page', $page);
    }


    public function nearby($request)
    {
        // Lấy tham số từ query string
        $lat = $request->lat;
        $lng = $request->lng;
        $radius = $request->input('radius', 10);     // mặc định 10 km
        $perPage = $request->input('per_page', 10);  // mặc định 10 item mỗi trang
        $page = $request->input('page', 1);          // mặc định trang 1

        // Truy vấn dữ liệu bất động sản theo khoảng cách
        $query = $this->model::selectRaw(
            "properties.*,
        (6371 * acos(
            cos(radians(?)) * cos(radians(latitude))
            * cos(radians(longitude) - radians(?))
            + sin(radians(?)) * sin(radians(latitude))
        )) AS distance",
            [$lat, $lng, $lat]
        )
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->with(['location', 'images']); // load quan hệ location + images

        // Phân trang
        $results = $query->paginate($perPage, ['*'], 'page', $page);

        // Trả về JSON chuẩn
        return $results;
    }
}
