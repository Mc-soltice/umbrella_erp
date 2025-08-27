<?php

use App\Http\Middleware\CheckUserLock;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\AuthController;
use App\Http\Controllers\PhpDocTestController;
use App\Http\Controllers\Users\AttendanceController;


/***** Route publique de register le login */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware(CheckUserLock::class);


Route::middleware(['auth:sanctum'])->prefix('users')->group(function () {

    Route::get('/', [UserController::class, 'index'])
        ->middleware('permission:user.view');

    Route::put('/{user}', [UserController::class, 'update'])
        ->middleware('permission:user.update');

    Route::delete('/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:user.delete');

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::patch('/{user}/toggle-lock', [UserController::class, 'toggleLock'])
        ->middleware('permission:admin.toggle-lock');

    Route::get('/{user}/activity', [UserController::class, 'activity'])
        ->middleware('permission:admin.view-activity');

    // Pour tous les utilisateurs
    Route::get('/attendance', [AttendanceController::class, 'report']);
        // ->middleware('permission:attendance.viewAll');

    // Pour un utilisateur spécifique
    Route::get('/attendance/{user}', [AttendanceController::class, 'getByUserAndPeriod'])
        ->middleware('permission:attendance.viewUser');

});


Route::get('/test-docs', [PhpDocTestController::class, 'index']);
