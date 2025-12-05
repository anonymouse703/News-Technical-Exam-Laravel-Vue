<?php

namespace App\Http\Controllers\Public;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Public\ArticleResource;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function __construct( protected ArticleRepositoryInterface $articleRepository )
    {}

    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $articles = $this->articleRepository
            ->filterByKeyword($request->input('keyword'))
            ->filterByDate($startDate, $endDate)
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return Inertia::render('articles/Index', [
            'articles' => ArticleResource::collection($articles),
        ]);
    }
}
