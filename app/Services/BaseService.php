<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class BaseService
{
    /**
     * Xử lý exception và log lỗi
     *
     * @param Throwable $e
     * @param string $context
     * @return array|null
     */
    protected function handleException(Throwable $e, string $context = 'Service error'): ?array
    {
        Log::error($context, [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        return null;
    }

    /**
     * Wrapper để thực thi và xử lý exception
     *
     * @param callable $callback
     * @param string $context
     * @param bool $throwException Nếu true, sẽ throw exception thay vì trả về null
     * @return mixed
     * @throws Exception
     */
    protected function execute(callable $callback, string $context = 'Service error', bool $throwException = false)
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            $this->handleException($e, $context);
            
            if ($throwException) {
                throw $e;
            }
            
            return null;
        }
    }

    /**
     * Wrapper để thực thi và throw exception khi có lỗi
     *
     * @param callable $callback
     * @param string $context
     * @return mixed
     * @throws Exception
     */
    protected function executeOrThrow(callable $callback, string $context = 'Service error')
    {
        return $this->execute($callback, $context, true);
    }
}
