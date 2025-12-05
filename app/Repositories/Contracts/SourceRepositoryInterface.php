<?php

namespace App\Repositories\Contracts;

use App\Models\Source;

/**
 * @method Source|null find(mixed $id)
 * @method Source|null first()
 */
interface SourceRepositoryInterface extends RepositoryInterface
{
	//define set of methods that SourceRepositoryInterface Repository must implement
	public function filterBySourceId(string $sourceId) :static;
	public function filterByName(string $name) :static;
}
