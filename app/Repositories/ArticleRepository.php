<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\ArticleRepositoryInterface;

class ArticleRepository extends BaseRepository implements ArticleRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(app(Article::class));
    }

    public function filterByKeyword(?string $keyword = null): self
    {
        return $this->filter(static function (Builder $builder) use($keyword){
            $builder->when($keyword, fn($q) =>
                    $q->where(function($q) use ($keyword) {
                        $q->where('title', 'LIKE', "%{$keyword}%")
                            ->orWhere('author', 'LIKE', "%{$keyword}%");
                    })
                );
        });
    }

    public function filterByUrl(string $url): static
    {
        return $this->filter(static fn (Builder $builder) => $builder->where('url', $url));
    }

    public function filterByDate($startDate, $endDate): self
    {
        return $this->filter(function (Builder $builder) use ($startDate, $endDate) {

            if (!empty($startDate)) {
                $builder->where('published_at', '>=', $startDate);
            }

            if (!empty($endDate)) {
                $builder->where('published_at', '<=', $endDate);
            }
        });
    }

}
