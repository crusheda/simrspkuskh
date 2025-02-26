<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/surkon/table', [App\Http\Controllers\Simgos\RegOnline\surkonController::class, 'table'])->name('surkon.table');
