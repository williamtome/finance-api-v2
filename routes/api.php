<?php

use App\Http\Controllers\{
    AuthController,
    ExpenseController,
    ResumeController,
    RevenueController
};
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

Route::prefix('auth')->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    Route::resource('revenue', RevenueController::class);

    Route::resource('expense', ExpenseController::class);

    Route::get('resume/{year}/{month}', [ResumeController::class, 'show']);

    Route::get('revenue/{year}/{month}', [RevenueController::class, 'listPerMonth']);
    Route::get('expense/{year}/{month}', [ExpenseController::class, 'listPerMonth']);
});
