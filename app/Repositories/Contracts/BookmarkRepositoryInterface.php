<?php

namespace App\Repositories\Contracts;

use App\Models\Bookmark;

/**
 * @method Bookmark|null find(mixed $id)
 * @method Bookmark|null first()
 */
interface BookmarkRepositoryInterface extends RepositoryInterface
{
	//define set of methods that BookmarkRepositoryInterface Repository must implement
	public function filterByUserId(int $userId): self;
	public function filterByArticleId(int $articleId): self;
	public function filterByDate(?string $startDate, ?string $endDate): self;
	public function filterByKeyword(?string $keyword = null): self;
}
