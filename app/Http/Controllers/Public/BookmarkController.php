<?php

namespace App\Http\Controllers\Public;

use Inertia\Inertia;
use App\Models\Article;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bookmark\StoreRequest;
use App\Http\Resources\Bookmark\BookmarkResource;
use App\Http\Resources\Public\ArticleResource;
use Carbon\Carbon;

class BookmarkController extends Controller
{   
    public function __construct(protected \App\Repositories\Contracts\BookmarkRepositoryInterface $bookmarkRepository) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        if ($startDate) {
            $startDate = Carbon::parse($startDate)->startOfDay(); 
        }

        if ($endDate) {
            $endDate = Carbon::parse($endDate)->endOfDay(); 
        }


        $bookmarks = $this->bookmarkRepository
            ->filterByUserId($user->id)
            ->filterByKeyword($search)
            ->filterByDate($startDate, $endDate)
            ->with(['article'])
            ->paginate(12)
            ->withQueryString();
            
        return Inertia::render('bookmarks/Index', [
            'bookmarks' => BookmarkResource::collection($bookmarks),
        ]);
    }

    public function toggle(StoreRequest $request, $article)
    {
        $user = $request->user();

        $bookmark = $this->bookmarkRepository
            ->filterByUserId($user->id)
            ->filterByArticleId($article)
            ->first();

        if ($bookmark) {
            $bookmark->delete();;

        } else {
            $bookmark = new Bookmark();
            $bookmark->article_id = $article;
            $bookmark->user_id = $user->id;

            $this->bookmarkRepository->save($bookmark);
        }
    }
}
