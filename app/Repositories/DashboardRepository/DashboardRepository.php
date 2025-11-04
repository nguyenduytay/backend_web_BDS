<?php

namespace App\Repositories\DashboardRepository;

use App\Models\DashboardStats;
use App\Models\Property;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class DashboardRepository extends BaseRepository implements DashboardRepositoryInterface
{

    public function getModel()
    {
        return DashboardStats::class;
    }
    public function getStats()
    {
        return $this->model::first();
    }

    public function getPropertyStats()
    {
        return Property::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();
    }

    public function getUserStats()
    {
        return User::selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->get();
    }

    public function getRecentProperties()
    {
        return Property::latest()->take(5)->get();
    }

    public function getRecentUsers()
    {
        return User::latest()->take(5)->get();
    }
}
