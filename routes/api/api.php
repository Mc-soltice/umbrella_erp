<?php

use App\Http\Middleware\CheckUserLock;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\AuthController;


/***** Route publique de register le login */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware(CheckUserLock::class);


Route::middleware(['auth:sanctum'])->prefix('users')->group(function () {

    Route::get('/', [UserController::class, 'index'])
        ->middleware('permission:user.view');

    // Route::post('/', [UserController::class, 'store'])
    //     ->middleware('permission:user.create');

    Route::put('/{user}', [UserController::class, 'update'])
        ->middleware('permission:user.update');

    Route::delete('/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:user.delete');
        
    Route::post('/logout', [AuthController::class, 'logout']);
});
