<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculationsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/test', [CalculationsController::class, 'testMethod']);

Route::post('/calculate-net-to-gross', [CalculationsController::class, 'calculateNetToGross']);
Route::post('/calculate-gross-to-net', [CalculationsController::class, 'calculateGrossToNet']);
Route::post('/calculate-ac-oc', [CalculationsController::class, 'calculateAcOc']);