<?php

namespace App\Console\Commands;

use App\Services\News\NewsApiClient;
use Illuminate\Console\Command;
use function Laravel\Prompts\text;

class FetchNews extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch articles from NewsAPI and store them';

    public function handle()
    {
        $keyword = text(
            label: 'Keyword to search (leave blank for "news"):' 
        );

        $keyword = $keyword === '' ? 'news' : $keyword;

        $this->info("Searching articles for keyword: {$keyword}");

        $defaultStart = now()->startOfMonth()->toDateString();
        $defaultEnd   = now()->toDateString();

        $startDate = text(
            label: "Start date (YYYY-MM-DD) — blank = {$defaultStart}:",
            validate: fn($value) => $value === '' ? null : validateDate($value)
        );
        $startDate = $startDate === '' ? $defaultStart : $startDate;

        $endDate = text(
            label: "End date (YYYY-MM-DD) — blank = {$defaultEnd}:",
            validate: fn($value) => $value === '' ? null : (
                ($error = validateDate($value)) ? $error : (
                    strtotime($value) < strtotime($startDate) ? 'End date must not be earlier than start date.' : null
                )
            )
        );
        $endDate = $endDate === '' ? $defaultEnd : $endDate;

        $this->info("Fetching news from {$startDate} to {$endDate}...");

        $numberOfArticles = text(
            label: 'Number of articles to fetch (default 10):',
            validate: fn($value) => $value === '' ? null : (is_numeric($value) && $value >= 1 && $value <= 100 ? null : 'Must be a number between 1 and 100')
        );
        $numberOfArticles = $numberOfArticles === '' ? 10 : (int) $numberOfArticles;

        $this->info("Fetching {$numberOfArticles} articles...");

        $service = app(NewsApiClient::class);

        $response = $service->fetch($keyword, $startDate, $endDate, $numberOfArticles);

        if (!$response) {
            $this->error('Failed to fetch articles. Check logs for details.');
            return 1;
        }

        $service->store($response, $keyword);

        $this->info("Articles fetched and processed successfully.");
    }
}
