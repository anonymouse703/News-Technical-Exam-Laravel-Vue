<?php

namespace App\Repositories;

use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\BookmarkRepositoryInterface;

class BookmarkRepository extends BaseRepository implements BookmarkRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(app(Bookmark::class));
    }

    public function filterByKeyword(?string $keyword = null): self
    {
        return $this->filter(static function (Builder $builder) use ($keyword) {
            $builder->when($keyword, fn($q) =>
                $q->whereHas('article', fn($q) =>
                    $q->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('author', 'LIKE', "%{$keyword}%")
                )
            );
        });
    }

    public function filterByUserId(int $userId): self
    {
        return $this->filter(static fn (Builder $builder) => $builder->where('user_id', $userId));
    }

    public function filterByArticleId(int $articleId): self
    {
        return $this->filter(static fn (Builder $builder) => $builder->where('article_id', $articleId));
    }

    public function filterByDate(?string $startDate, ?string $endDate): self
    {
        return $this->filter(function (Builder $builder) use ($startDate, $endDate) {
            $builder->whereHas('article', function (Builder $q) use ($startDate, $endDate) {
                if (!empty($startDate)) {
                    $q->where('published_at', '>=', $startDate);
                }

                if (!empty($endDate)) {
                    $q->where('published_at', '<=', $endDate);
                }
            });
        });
    }

}
