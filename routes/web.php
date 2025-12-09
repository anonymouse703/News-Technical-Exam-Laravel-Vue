<?php

use Inertia\Inertia;
use App\Models\Article;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\ArticleController;
use App\Http\Controllers\Public\BookMarkController;

Route::get('/', function () {

    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
        'articles' => Article::latest()->paginate(12),
    ]);
})->name('home');

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

Route::middleware(['auth'])->group(function () {
    Route::post('/bookmark/{article}', [BookMarkController::class, 'toggle'])->name('bookmark.toggle');
    Route::get('/bookmarks', [BookMarkController::class, 'index'])->name('bookmarks.index');
});


Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
