<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::delete('/user/delete', [UserController::class, 'deleteCount']);
Route::delete('/user/get', [UserController::class, 'getAllUsers']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Welcome to the dashboard', 'user' => Auth::user()], 200);
    })->name('dashboard');

    Route::get('/category/get', [CategoryController::class, 'get']);
   
    
    Route::post('/category/create', [CategoryController::class, 'create']);
    Route::get('/transaction/get', [TransactionController::class, 'get']);
    Route::post('/transaction/create', [TransactionController::class, 'create']);
});


Route::delete('/category/delete', [CategoryController::class, 'delete']);
Route::get('/category/get', [CategoryController::class, 'get'])->name('categoryget');

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});