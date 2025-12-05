<?php

namespace App\Services\News;

use App\Models\Source;
use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\SourceRepositoryInterface;
use App\Repositories\Contracts\ArticleRepositoryInterface;

class NewsApiClient
{
    protected string $key;
    protected string $url;

    public function __construct(
        protected SourceRepositoryInterface $sourceRepository,
        protected ArticleRepositoryInterface $articleRepository,
    ) {
        $this->key = config('newsapi.key');
        $this->url = config('newsapi.url');
    }

    /**
     * Fetch articles from NewsAPI.
     */
    public function fetch(string $keyword, string $startDate, string $endDate, int $pageSize = 10)
    {
        $query = [
            'apiKey'    => $this->key,
            'q'         => $keyword,
            'from'      => $startDate,
            'to'        => $endDate,
            'sortBy'    => 'publishedAt',
            'language'  => 'en',
            'pageSize'  => $pageSize,
        ];

        $requestUrl = $this->url . '?' . http_build_query($query);
        Log::info("[NewsApiClient] Request URL: {$requestUrl}");

        $response = Http::get($this->url, $query);

        if ($response->failed()) {
            Log::error('[NewsApiClient] API request failed', ['body' => $response->body()]);
            return null;
        }

        return $response->json();
    }

    /**
     * Store articles and sources from API response.
     */

    public function store(array $response, string $keyword)
    {
        if (!isset($response['articles']) || empty($response['articles'])) {
            Log::warning('[NewsApiClient] No articles found in response.');
            return;
        }

        $savedCount = 0;
        $failedCount = 0;

        foreach ($response['articles'] as $item) {

            $validator = Validator::make($item, [
                'title'       => 'required|string|max:255',
                'url'         => 'required|url',
                'author'      => 'nullable|string|max:255',
                'urlToImage'  => 'nullable|url',
                'publishedAt' => 'nullable|date',
                'description' => 'nullable|string',
                'content'     => 'nullable|string',
                'source.id'   => 'nullable|string|max:50',
                'source.name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                Log::warning('[NewsApiClient] Article validation failed', [
                    'errors' => $validator->errors()->all(),
                    'data'   => $item,
                ]);
                $failedCount++;
                continue;
            }

            // 2️⃣ Sanitize data
            $data = $validator->validated();

            $source = Source::updateOrCreate(
                ['name' => strip_tags(trim($data['source']['name']))],
                ['api_source_id' => $data['source']['id'] ?? null]
            );

            $article = $this->articleRepository->filterByUrl($data['url'])->first() ?? new Article();

            $article->title        = strip_tags(trim($data['title']));
            $article->description  = isset($data['description']) ? strip_tags(trim($data['description'])) : $article->description;
            $article->content      = isset($data['content']) ? strip_tags(trim($data['content'])) : ($article->content ?? $article->description ?? 'No content');
            $article->author       = isset($data['author']) ? strip_tags(trim($data['author'])) : $article->author;
            $article->source_id    = $source->id;
            $article->url          = $data['url'];
            $article->image_url    = $data['urlToImage'] ?? $article->image_url;
            $article->published_at = isset($data['publishedAt'])
                ? date('Y-m-d H:i:s', strtotime($data['publishedAt']))
                : $article->published_at;
            $article->keyword      = $keyword ?? $article->keyword;

            try {
                $this->articleRepository->save($article);
                $savedCount++;
            } catch (\Throwable $e) {
                $failedCount++;
                Log::warning('[NewsApiClient] Failed to save an article', [
                    'exception_message' => $e->getMessage(),
                    'url' => $data['url']
                ]);
            }
        }

        Log::info('[NewsApiClient] API interaction summary', [
            'total_articles_fetched' => count($response['articles']),
            'total_articles_saved'   => $savedCount,
            'total_articles_failed'  => $failedCount,
            'keyword'                => $keyword,
        ]);
    }
}
