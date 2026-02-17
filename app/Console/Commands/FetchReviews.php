<?php

namespace App\Console\Commands;

use App\Services\YandexMapReviewService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class FetchReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:reviews';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reviews = YandexMapReviewService::crawlAndFetchReviews('1010501395', 11);

        dump($reviews);
    }
}
