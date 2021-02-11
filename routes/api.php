<?php

use App\Http\Controllers\Api\FamilyTreeController;
use App\Http\Controllers\Api\PersonController;
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

Route::middleware('auth:sanctum,api')->group(function () {
    Route::get('user', function () {
        return response()->json(auth()->user());
    });

    Route::get('family-trees', [FamilyTreeController::class, 'index']);
    Route::get('family-trees/{id}', [FamilyTreeController::class, 'show']);
    Route::post('family-trees', [FamilyTreeController::class, 'store']);
    Route::delete('family-trees/{id}', [FamilyTreeController::class, 'destroy']);

    Route::post('persons', [PersonController::class, 'store']);
    Route::get('persons/{id}/tree', [PersonController::class, 'getPersonSubTree']);
});
