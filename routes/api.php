<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogController;

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

Route::post('/login', [AuthController::class, 'login'])->middleware('check.token');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Transactions
    Route::controller(TransactionController::class)->prefix('transactions')->group(function () {
        Route::get('', 'index')->name('transactions.index');
        Route::get('{id}', 'show')->name('transactions.show');
        Route::post('create', 'create')->name('transactions.create');
        Route::put('update/{id}', 'update')->name('transactions.update');
        Route::delete('delete/{id}', 'delete')->name('transactions.delete');
        Route::get('type/{type}', 'transactionsByType');
        Route::get('date/{date}', 'transactionsByDate')->name('transactions.transactionsByDate');
        Route::get('date/get/{date}', 'findTransactionByDate')->name('transactions.findTransactionByDate');
    });

    // goals
    Route::controller(GoalController::class)->prefix('goals')->group(function () {
        Route::get('', 'index')->name('goals.index');
        Route::get('{id}', 'show')->name('goals.show');
        Route::post('create', 'create')->name('goals.create');
        Route::put('update/{id}', 'update')->name('goals.update');
        Route::delete('delete/{id}', 'delete')->name('goals.delete');
        Route::get('search?{name}', 'search')->name('goals.search');
    });

    // Profile
    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('', 'profile')->name('profile');
        Route::put('update', 'updateProfile')->name('profile.update');
        Route::put('reset-password', 'resetPassword');
        Route::delete('deleteAccount', 'deleteAccount')->name('profile.delete');
    });

    // Logs and notifications

    Route::controller(LogController::class)->prefix('notifications')->group(function () { Route::get('', 'notifications'); });

    Route::post('/logout', [AuthController::class, 'logout']);
});
