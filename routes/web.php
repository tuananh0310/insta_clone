<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Post Route
Route::get('/p/create',[PostController::class,'create'])->name('post.create');
Route::get('/',[PostController::class,'index'])->name('post.index');
Route::post('/p',[PostController::class,'store'])->name('post.store');
Route::delete('/p/{post}', [PostController::class,'destroy'])->name('post.destroy');
Route::get('/explore', [PostController::class,'explore'])->name('post.explore');
Route::get('/p/{post}', [PostController::class,'show'])->name('post.show');
Route::post('/p/{post}', [PostController::class,'updatelikes'])->name('post.update');
Route::post('/like/{like}', [LikeController::class,'update'])->name('like.create');
Route::post('/comment', [CommentController::class,'store'])->name('comments.store');

// Profile route
Route::get('/profile/{user}',[ProfileController::class,'index'])->name('profile.index');
Route::get('/profile/{user}/edit',[ProfileController::class,'edit'])->name('profile.edit');
Route::put('/profile/{user}',[ProfileController::class,'update'])->name('profile.update');
Route::any('/search', [ProfileController::class,'search'])->name('profile.search')->middleware('auth');


// Follow route
Route::post('/follow/{user}',[FollowController::class, 'store']);
