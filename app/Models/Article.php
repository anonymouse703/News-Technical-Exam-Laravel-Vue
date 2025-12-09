<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
     protected $fillable = [
        'title',
        'description',
        'content',
        'author',
        'source_id',
        'url',
        'image_url',
        'published_at',
        'keyword',
    ];

    protected $dates = ['published_at'];

    public function source() : BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function bookmarks() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'book_marks', 'article_id', 'user_id');
    }
    
}
