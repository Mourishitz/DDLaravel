<?php

namespace App\Core\Exceptions;

use Illuminate\Http\JsonResponse;

class ControllerException extends CoreException
{
    public function render(): JsonResponse
    {
        return response_message($this->getMessage(), $this->getCode());
    }
}
