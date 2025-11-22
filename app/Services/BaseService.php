<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

abstract class BaseService
{
    /**
     * Xử lý exception và log lỗi
     *
     * @param Exception $e
     * @param string $context
     * @return null
     */
    protected function handleException(Exception $e, string $context = 'Service error'): ?array
    {
        Log::error($context, [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return null;
    }

    /**
     * Wrapper để thực thi và xử lý exception
     *
     * @param callable $callback
     * @param string $context
     * @return mixed
     */
    protected function execute(callable $callback, string $context = 'Service error')
    {
        try {
            return $callback();
        } catch (Exception $e) {
            $this->handleException($e, $context);
            return null;
        }
    }
}
