<?php

namespace App\Services;

use App\Repositories\ReportRepository\ReportRepositoryInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ReportService extends BaseService
{
    protected $reportRepo;

    public function __construct(ReportRepositoryInterface $reportRepo)
    {
        $this->reportRepo = $reportRepo;
    }

    public function getPropertiesMonthly()
    {
        try {
            return $this->reportRepo->getPropertiesMonthly();
        } catch (Throwable $e) {
            $this->handleException($e, 'ReportService::getPropertiesMonthly');
            return null;
        }
    }

    public function getUsersMonthly()
    {
        try {
            return $this->reportRepo->getUsersMonthly();
        } catch (Throwable $e) {
            $this->handleException($e, 'ReportService::getUsersMonthly');
            return null;
        }
    }

    public function exportProperties(): ?StreamedResponse
    {
        try {
            $data = $this->reportRepo->getAllProperties();

            if ($data->isEmpty()) {
                return null;
            }

            $response = new StreamedResponse(function () use ($data) {
                try {
                    $handle = fopen('php://output', 'w');

                    if ($handle === false) {
                        throw new \RuntimeException('Không thể mở stream để ghi file CSV');
                    }

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
                } catch (\Exception $e) {
                    if (isset($handle) && is_resource($handle)) {
                        fclose($handle);
                    }
                    throw $e;
                }
            });

            // Set header cho response
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="properties.csv"');

            return $response;
        } catch (Throwable $e) {
            $this->handleException($e, 'ReportService::exportProperties');
            return null;
        }
    }

    public function exportUsers(): ?StreamedResponse
    {
        try {
            $data = $this->reportRepo->getAllUsers();

            if ($data->isEmpty()) {
                return null;
            }

            $response = new StreamedResponse(function () use ($data) {
                try {
                    $handle = fopen('php://output', 'w');

                    if ($handle === false) {
                        throw new \RuntimeException('Không thể mở stream để ghi file CSV');
                    }

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
                } catch (\Exception $e) {
                    if (isset($handle) && is_resource($handle)) {
                        fclose($handle);
                    }
                    throw $e;
                }
            });

            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="users.csv"');

            return $response;
        } catch (Throwable $e) {
            $this->handleException($e, 'ReportService::exportUsers');
            return null;
        }
    }
}
