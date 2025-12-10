<?php

namespace App\Http\Resources\Bookmark;

use App\Http\Resources\Public\ArticleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
     {

        $authUser = $request->user();

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'article_id' => $this->article_id,


            // RELATIONSHIPS
            'article' => new ArticleResource($this->whenLoaded('article')),
        ];
    }
}
