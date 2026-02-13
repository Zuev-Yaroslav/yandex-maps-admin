<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HttpWebException extends Exception
{
    use HasRender;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, private string $html)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::error($this->getMessage());
        Storage::put('html/debug'.time().'.html', $this->html);
    }

    public static function checkSessionIdOnNull(?string $sessionId, string $html): void
    {
        if (! $sessionId) {
            throw new HttpWebException(
                'Возможно вылетела капча. Попробуйте позже 10 минут.',
                Response::HTTP_FORBIDDEN,
                null,
                $html,
            );
        }
    }
}
