<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Lang;

class BusinessException extends Exception
{
    public function render($request)
    {
        $errorCode = $this->getMessage();
        $message = Lang::get("messages." . $errorCode);
        return ApiResponse::error($message, $errorCode, 400);
    }
}
