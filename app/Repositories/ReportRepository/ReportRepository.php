<?php

namespace App\Repositories\ReportRepository;

use App\Models\Property;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class ReportRepository extends BaseRepository implements ReportRepositoryInterface
{
    public function getModel()
    {
        return Property::class;
    }
    // Đếm properties theo tháng (12 tháng gần nhất)
    public function getPropertiesMonthly()
    {
        return Property::query()->select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
    }

    // Đếm users theo tháng (12 tháng gần nhất)
    public function getUsersMonthly()
    {
        return User::query()->select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
    }

    // Lấy toàn bộ properties (dùng export CSV)
    public function getAllProperties()
    {
        return Property::all();
    }

    // Lấy toàn bộ users (dùng export CSV)
    public function getAllUsers()
    {
        return User::all();
    }
}
