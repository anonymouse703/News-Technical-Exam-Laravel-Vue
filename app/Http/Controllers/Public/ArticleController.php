<?php

namespace App\Http\Controllers\Public;

use Inertia\Inertia;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Public\ArticleResource;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Carbon\Carbon;

class ArticleController extends Controller
{
    public function __construct(protected ArticleRepositoryInterface $articleRepository) {}

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        if ($startDate) {
            $startDate = Carbon::parse($startDate)->startOfDay(); 
        }

        if ($endDate) {
            $endDate = Carbon::parse($endDate)->endOfDay(); 
        }

        $articles = $this->articleRepository
            ->filterByKeyword($search)
            ->filterByDate($startDate, $endDate)
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        dd( $articles );

        return Inertia::render('articles/Index', [
            'articles' => ArticleResource::collection($articles)
        ]);
    }
}
