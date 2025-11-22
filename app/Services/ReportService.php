<?php

namespace App\Services;

use App\Repositories\ReportRepository\ReportRepositoryInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportService extends BaseService
{
    protected $reportRepo;

    public function __construct(ReportRepositoryInterface $reportRepo)
    {
        $this->reportRepo = $reportRepo;
    }

    public function getPropertiesMonthly()
    {
        return $this->execute(function () {
            return $this->reportRepo->getPropertiesMonthly();
        }, 'ReportService::getPropertiesMonthly');
    }

    public function getUsersMonthly()
    {
        return $this->execute(function () {
            return $this->reportRepo->getUsersMonthly();
        }, 'ReportService::getUsersMonthly');
    }

    public function exportProperties(): ?StreamedResponse
    {
        $data = $this->reportRepo->getAllProperties();

        if ($data->isEmpty()) {
            return null;
        }

        $response = new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');

            // Header CSV
            fputcsv($handle, ['ID', 'Title', 'Status', 'Price', 'Area', 'Created At']);

            // Ghi từng dòng dữ liệu
            foreach ($data as $property) {
                fputcsv($handle, [
                    $property->id,
                    $property->title,
                    $property->status,
                    $property->price,
                    $property->area,
                    $property->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        });

        // Set header cho response
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="properties.csv"');

        return $response;
    }

    public function exportUsers(): ?StreamedResponse
    {
        $data = $this->reportRepo->getAllUsers();

        if ($data->isEmpty()) {
            return null;
        }

        $response = new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Created At']);
            foreach ($data as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->created_at
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="users.csv"');

        return $response;
    }
}
