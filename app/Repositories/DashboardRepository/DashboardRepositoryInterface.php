<?php

namespace App\Repositories\DashboardRepository;

use App\Models\Property;
use App\Models\User;
use App\Repositories\RepositoryInterface;
use Carbon\Carbon;

interface DashboardRepositoryInterface extends RepositoryInterface
{
    public function getStats();

    public function getPropertyStats();

    public function getUserStats();

    public function getRecentProperties();

    public function getRecentUsers();
}
