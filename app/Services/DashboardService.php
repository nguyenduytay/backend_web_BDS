<?php

namespace App\Services;

use App\Repositories\DashboardRepository\DashboardRepository;
use App\Repositories\DashboardRepository\DashboardRepositoryInterface;
use Exception;

class DashboardService
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
        } catch (Exception $e) {
            return null;
        }
    }

    public function getPropertyStats()
    {
        try {
            return $this->dashboardRepo->getPropertyStats();
        } catch (Exception $e) {
            return null;
        }
    }

    public function getUserStats()
    {
        try {
            return $this->dashboardRepo->getUserStats();
        } catch (Exception $e) {
            return null;
        }
    }

    public function getRecentProperties()
    {
        try {
            return $this->dashboardRepo->getRecentProperties();
        } catch (Exception $e) {
            return null;
        }
    }

    public function getRecentUsers()
    {
        try {
            return $this->dashboardRepo->getRecentUsers();
        } catch (Exception $e) {
            return null;
        }
    }
}
