<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('accounts', AccountController::class);

    Route::get('/accounts/{account}/transactions', [TransactionController::class, 'index']);
    Route::post('/accounts/{account}/transactions', [TransactionController::class, 'store']);
    Route::delete('/accounts/{account}/transactions/{transaction}', [TransactionController::class, 'destroy']);
    Route::get('/accounts/{account}/balance', [TransactionController::class, 'balance']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});