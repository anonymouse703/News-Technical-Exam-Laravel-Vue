<?php

namespace App\Http\Controllers\Public;

use Inertia\Inertia;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bookmark\StoreRequest;

class BookmarkController extends Controller
{   
    public function __construct(protected \App\Repositories\Contracts\BookmarkRepositoryInterface $bookmarkRepository) {}

    public function index(Request $request)
    {
        $user = $request->user();

        $bookmarks = $this->bookmarkRepository
            ->filterByUserId($user->id)
            ->with(['article'])
            ->paginate(12);
        
        return Inertia::render('bookmarks/Index', [
            'bookmarks' => $bookmarks,
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

    public function bookmarks(Request $request)
    {
        $user = $request->user();

        $bookmarks = $this->bookmarkRepository
            ->filterByUserId($user->id)
            ->with(['article'])
            ->paginate(10);
        
        return Inertia::render('bookmarks/Index', [
            'bookmarks' => $bookmarks,
        ]);
    }
}
