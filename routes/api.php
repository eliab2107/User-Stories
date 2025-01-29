<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransacaoController;


// Unauthenticated routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


 
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    //Dashboard route
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Welcome to the dashboard', 'user' => Auth::user()], 200);
    })->name('dashboard');
    
    //User routes
    Route::delete('/user/delete', [UserController::class, 'delete']);
    Route::put('/user/update', [UserController::class, 'update']);

    //Category routes
    Route::delete('/category/delete', [CategoryController::class, 'delete']);
    Route::get('/category/get', [CategoryController::class, 'getallbyuser']);
    Route::post('/category/create', [CategoryController::class, 'create']);
    Route::patch('/category/update', [CategoryController::class, 'update']); //pendente
    
    
    //Transaction routes
    Route::get('/transaction/getbyfilter', [TransacaoController::class, 'getbyfilter']);
    Route::patch('/transaction/update', [TransacaoController::class, 'update']);
    Route::get('/transaction/getall', [TransacaoController::class, 'getall']);
    Route::post('/transaction/create', [TransacaoController::class, 'create']);
    Route::delete('/transaction/delete', [TransacaoController::class, 'delete']);

   

    Route::get('/categoria_ids', [CategoryController::class, 'getIds']); // pensente
    
});
