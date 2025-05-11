<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('post', PostController::class)->except('show')->scoped([
        'post' => 'slug'
    ]);
    Route::resource('post.comment', CommentController::class)->only(['store', 'destroy'])
        ->scoped(['post' => 'slug']);
});

Route::resource('post', PostController::class)->only('show')->scoped([
    'post' => 'slug'
]);

require __DIR__ . '/auth.php';
