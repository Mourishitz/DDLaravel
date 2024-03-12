<?php

namespace App\Core\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * BaseException
 */
abstract class CoreException extends Exception
{
    protected array $customResponse = [];

    protected function context(): array
    {
        $context = [
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'previous_file' => $this?->getPrevious()?->getFile(),
            'previous_line' => $this?->getPrevious()?->getLine(),
            'previous_code' => $this->getPreviousOriginalCode($this?->getPrevious()),
            'previous_message' => $this->getPreviousOriginalMessage($this?->getPrevious()),
        ];

        if (! empty($this->customResponse)) {
            return array_merge($context, $this->customResponse);
        }

        return $context;
    }

    private function getPreviousOriginalMessage(?Throwable $previous = null): ?string
    {
        if ($this->getMessage() === $previous?->getMessage()) {
            return $this->getPreviousOriginalMessage($previous?->getPrevious());
        }

        return $previous?->getMessage();
    }

    private function getPreviousOriginalCode(?Throwable $previous = null): ?int
    {
        if ($this->getCode() === $previous?->getCode()) {
            return $this->getPreviousOriginalCode($previous?->getPrevious());
        }

        return $previous?->getCode();
    }

    public function report(): void
    {
        Log::error(static::class, $this->context());
    }
}
