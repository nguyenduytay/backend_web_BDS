<?php

namespace App\Repositories\ReportRepository;

use App\Models\Property;
use App\Models\User;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\DB;

interface ReportRepositoryInterface extends RepositoryInterface
{
    // Đếm properties theo tháng (12 tháng gần nhất)
    public function getPropertiesMonthly();

    // Đếm users theo tháng (12 tháng gần nhất)
    public function getUsersMonthly();

    // Lấy toàn bộ properties (dùng export CSV)
    public function getAllProperties();

    // Lấy toàn bộ users (dùng export CSV)
    public function getAllUsers();
}
