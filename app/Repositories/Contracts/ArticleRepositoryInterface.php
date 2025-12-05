<?php

namespace App\Repositories\Contracts;

use App\Models\Article;

/**
 * @method Article|null find(mixed $id)
 * @method Article|null first()
 */
interface ArticleRepositoryInterface extends RepositoryInterface
{
	//define set of methods that ArticleRepositoryInterface Repository must implement
	public function filterByKeyword(?string $keyword = null): self;
	public function filterByUrl(string $url) :static;
	public function filterByDate($startDate, $endDate) :self;
}
