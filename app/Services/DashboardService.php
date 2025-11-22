<?php

namespace App\Services;

use App\Repositories\DashboardRepository\DashboardRepositoryInterface;

class DashboardService extends BaseService
{
    protected $dashboardRepo;

    public function __construct(DashboardRepositoryInterface $dashboardRepo)
    {
        $this->dashboardRepo = $dashboardRepo;
    }

    public function getStats()
    {
        return $this->execute(function () {
            return $this->dashboardRepo->getStats();
        }, 'DashboardService::getStats');
    }

    public function getPropertyStats()
    {
        return $this->execute(function () {
            return $this->dashboardRepo->getPropertyStats();
        }, 'DashboardService::getPropertyStats');
    }

    public function getUserStats()
    {
        return $this->execute(function () {
            return $this->dashboardRepo->getUserStats();
        }, 'DashboardService::getUserStats');
    }

    public function getRecentProperties()
    {
        return $this->execute(function () {
            return $this->dashboardRepo->getRecentProperties();
        }, 'DashboardService::getRecentProperties');
    }

    public function getRecentUsers()
    {
        return $this->execute(function () {
            return $this->dashboardRepo->getRecentUsers();
        }, 'DashboardService::getRecentUsers');
    }
}
