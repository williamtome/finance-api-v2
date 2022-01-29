<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RevenueController;
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

Route::get('/', function() {
    return "<h1>API de finanças pessoais</h1>";
});

Route::resource('revenue', RevenueController::class);
Route::resource('expense', ExpenseController::class);

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
