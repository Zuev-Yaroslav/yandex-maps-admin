<?php

namespace App\Jobs\YandexMap\Review;

use App\Models\Subsidiary;
use App\Repositories\YandexMap\ReviewRepository;
use App\Services\YandexMapReviewService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncReviews implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $businessId,
        private readonly Subsidiary $subsidiary,
        private readonly string $sessionId,
        private readonly int $startedPage,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        for ($i = $this->startedPage; true; $i++) {
            $reviewsRawData = YandexMapReviewService::fetchReviewsWithSessionId($this->businessId, $this->sessionId, $i);
            if (empty($reviewsRawData['reviews'])) {
                break;
            }
            foreach ($reviewsRawData['reviews'] as $review) {
                $review['subsidiary_id'] = $this->subsidiary->id;
                ReviewRepository::updateOrCreate($review);
            }
        }
    }
}
