<?php

use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\SubscriberApiController;
use App\Http\Middleware\JsonMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    });

Route::middleware(JsonMiddleware::class)
    ->group(function () {
        Route::get('/keys',
            [ApiKeyController::class, 'index'])->name('keys.all');
        Route::post('/keys',
            [ApiKeyController::class, 'store'])->name('keys.store');

        Route::get('/subscribers',
            [SubscriberApiController::class, 'all']);
        Route::post('/subscribers',
            [SubscriberApiController::class, 'store']);
        Route::put('/subscribers/{id}',
            [SubscriberApiController::class, 'update']);
        Route::delete('/subscribers/{id}',
            [SubscriberApiController::class, 'delete']);
    });

