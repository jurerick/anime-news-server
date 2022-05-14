<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\VoteController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/news/mock', [NewsController::class, 'mock']);

Route::get('/news/page/{page}/limit/{limit}', [NewsController::class, 'index'])
    ->where(['page' => '[0-9]+', 'limit' => '[0-9]+']);

Route::get('/news/popular/page/{page}/limit/{limit}', [NewsController::class, 'popular'])
    ->where(['page' => '[0-9]+', 'limit' => '[0-9]+']);

Route::post('/votes', [VoteController::class, 'store']);
Route::put('/votes', [VoteController::class, 'update']);
