<?php

use App\Http\Controllers\{ Auth\Github,
    DashboardController,
    ProfileController,
    Question,
    QuestionController};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    if (app()->isLocal() && Auth::check() !== false) {
        auth()->loginUsingId(1);

        return to_route('dashboard');
    }

    return view('welcome');
});

Route::get('/github/login', Github\RedirectController::class)->name('github.login');
Route::get('/github/callback', Github\CallbackController::class)->name('github.callback');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    #region Question Routes
    Route::prefix('/question')->name('question.')->group(function () {
        Route::get('/{question}/edit', [QuestionController::class, 'edit'])->name('edit');
        Route::get('/', [QuestionController::class, 'index'])->name('index');
        Route::post('/store', [QuestionController::class, 'store'])->middleware('auth')->name('store');
        Route::delete('/question/{question}', [QuestionController::class, 'destroy'])->name('destroy');
        Route::post('/like/{question}', Question\LikeController::class)->name('like');
        Route::post('/unlike/{question}', Question\UnlikeController::class)->name('unlike');
        Route::put('/publish/{question}', Question\PublishController::class)->name('publish');
        Route::put('/update/{question}', [QuestionController::class, 'update'])->name('update');
        Route::patch('/question/{question}', [QuestionController::class, 'archive'])->name('archive');
        Route::patch('/question/{question}/restore', [QuestionController::class, 'restore'])->name('restore');

    });
    #endregion

    #region Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    #endregion
});

# retorno caso nao encontre a url
/*Route::fallback(function(){
    return "<h1>Vish fudeu, a URL nao existe</h1>
            <a class='font-bold' href='".route('dashboard')."'> ⬅ Back to Dashboard</a>
            ";
});*/

require __DIR__ . '/auth.php';
