<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ReviewException extends Exception
{

    use HasRender;
    /**
     * Report the exception.
     */
    public function report(): void
    {
        //
    }

    public static function checkEmptyReviews(?array $reviews)
    {
        if (empty($reviews['reviews'])) {
            throw new ReviewException('Отзывы не найдены. Возможно такой организации нет.', Response::HTTP_NOT_FOUND);
        }
    }
}
