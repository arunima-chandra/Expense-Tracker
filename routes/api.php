<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportantDateController;
use App\Http\Controllers\ReportController;
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

    // Important Dates / Reminders
    Route::get('/important-dates/alarms/today', [ImportantDateController::class, 'alarmsToday']);
    Route::apiResource('important-dates', ImportantDateController::class);

    // Reports
    Route::get('/reports/monthly/{year}/{month}', [ReportController::class, 'monthly']);
    Route::get('/reports/yearly/{year}', [ReportController::class, 'yearly']);

    // In-app Notifications
    Route::get('/notifications', function (Request $request) {
        return response()->json([
            'unread_count' => $request->user()->unreadNotifications()->count(),
            'notifications' => $request->user()->notifications()->take(50)->get(),
        ]);
    });

    Route::post('/notifications/{id}/read', function (Request $request, string $id) {
        $notification = $request->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['message' => 'Marked as read']);
    });

    Route::post('/notifications/read-all', function (Request $request) {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read']);
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});