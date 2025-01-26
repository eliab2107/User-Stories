<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/user/create', [UserController::class, 'create']);

Route::middleware('auth:sanctum')->group(function () {
    
    Route::delete('/user/delete', [UserController::class, 'delete']);
});

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});