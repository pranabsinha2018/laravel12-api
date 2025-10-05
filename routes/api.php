<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestApiController;
use App\Http\Controllers\StudentApiController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/test', [TestApiController::class, 'test'])->name('test-api');

Route::apiResource('students', StudentApiController::class);
