<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubscriberController;
use App\Http\Middleware\EnsureApiKeyExist;
use App\Http\Middleware\RedirectIfApiKeyExist;
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

Route::get('/', [HomeController::class, 'home'])
    ->name('home')
    ->middleware(RedirectIfApiKeyExist::class);

Route::get('/subscribers',
    [SubscriberController::class, 'index'])->name('subscribers.index');
Route::get('/subscribers/create',
    [SubscriberController::class, 'create'])->name('subscribers.create');
Route::get('/subscribers/{id}/edit', [SubscriberController::class, 'edit']);

//Route::
