<?php

namespace App\Services;

use App\Repositories\DashboardRepository\DashboardRepositoryInterface;
use Throwable;

class DashboardService extends BaseService
{
    protected $dashboardRepo;

    public function __construct(DashboardRepositoryInterface $dashboardRepo)
    {
        $this->dashboardRepo = $dashboardRepo;
    }

    public function getStats()
    {
        try {
            return $this->dashboardRepo->getStats();
        } catch (Throwable $e) {
            $this->handleException($e, 'DashboardService::getStats');
            return null;
        }
    }

    public function getPropertyStats()
    {
        try {
            return $this->dashboardRepo->getPropertyStats();
        } catch (Throwable $e) {
            $this->handleException($e, 'DashboardService::getPropertyStats');
            return null;
        }
    }

    public function getUserStats()
    {
        try {
            return $this->dashboardRepo->getUserStats();
        } catch (Throwable $e) {
            $this->handleException($e, 'DashboardService::getUserStats');
            return null;
        }
    }

    public function getRecentProperties()
    {
        try {
            return $this->dashboardRepo->getRecentProperties();
        } catch (Throwable $e) {
            $this->handleException($e, 'DashboardService::getRecentProperties');
            return null;
        }
    }

    public function getRecentUsers()
    {
        try {
            return $this->dashboardRepo->getRecentUsers();
        } catch (Throwable $e) {
            $this->handleException($e, 'DashboardService::getRecentUsers');
            return null;
        }
    }
}
