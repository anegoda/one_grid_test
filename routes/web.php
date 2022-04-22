<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PostController::class, 'index'])->name('post.index');
Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
Route::post('/post/store', [PostController::class, 'store'])->name('post.store');
Route::get('/post/edit/{post}', [PostController::class, 'edit'])->name('post.edit');
Route::put('/post/update', [PostController::class, 'update'])->name('post.update');
Route::get('/post/delete/{post}', [PostController::class, 'destroy'])->name('post.delete');

Route::get('/post/rating/up/{post}', [RatingController::class, 'up'])->name('post.up');
Route::get('/post/rating/down/{post}', [RatingController::class, 'down'])->name('post.down');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
