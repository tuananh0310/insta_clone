<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Post Route
Route::get('/',[PostController::class,'index'])->name('post.index');
Route::get('/p/create',[PostController::class,'create'])->name('post.create');
Route::post('/p',[PostController::class,'store'])->name('post.store');


// Profile route
Route::get('profile/{user}',[ProfileController::class,'index'])->name('profile.index');
Route::get('profile/{user}/edit',[ProfileController::class,'edit'])->name('profile.edit');
Route::put('profile/{user}',[ProfileController::class,'update'])->name('profile.update');
Route::any('/search', [ProfileController::class,'search'])->name('profile.search');


// Follow route
Route::post('follow/{user}',[FollowController::class, 'store']);
