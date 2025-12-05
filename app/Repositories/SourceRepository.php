<?php

namespace App\Repositories;

use App\Models\Source;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\SourceRepositoryInterface;

class SourceRepository extends BaseRepository implements SourceRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(app(Source::class));
    }

    public function filterBySourceId(string $sourceId): static
    {
        return $this->filter(static fn (Builder $builder) => $builder->where('api_source_id', $sourceId));
    }

    public function filterByName(string $name): static
    {
        return $this->filter(static fn (Builder $builder) => $builder->where('name', $name));
    }
}
