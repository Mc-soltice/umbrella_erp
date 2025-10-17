<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Users\UserController;
use App\Http\Controllers\Api\Candidatures\CandidatureController;
use App\Http\Controllers\Api\Sites\SiteController;
use App\Http\Controllers\Api\Agents\AgentController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);


Route::middleware(['auth:sanctum','checkIfLocked'])->group(function () {

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])
            ->middleware('permission:view');


        Route::patch('/{user}', [UserController::class, 'update'])
            ->middleware('permission:update');

        Route::delete('/{user}', [UserController::class, 'destroy'])
            ->middleware(middleware: 'permission:delete');

        Route::post('logout', [AuthController::class, 'logout']);
    });


    // 📂 Module Candidatures
    Route::prefix('candidatures')->group(function () {
        Route::get('/', [CandidatureController::class, 'index'])
            ->middleware('role:gestionnaire|responsable');

        Route::post('/', [CandidatureController::class, 'store'])
            ->middleware('role:gestionnaire|responsable');

        Route::get('/{candidature}', [CandidatureController::class, 'show'])
            ->middleware('role:gestionnaire|responsable');

        Route::put('/{candidature}', [CandidatureController::class, 'update'])
            ->middleware('role:gestionnaire|responsable');

        Route::delete('/{candidature}', [CandidatureController::class, 'destroy'])
            ->middleware('role:gestionnaire|responsable');

        Route::post('/{candidature}/validate', [CandidatureController::class, 'validateCandidature'])
            ->middleware('role:gestionnaire|responsable');
    });

    // 📂 Module Sites
    Route::prefix('sites')->group(function () {
        Route::get('/', [SiteController::class, 'index'])
            ->middleware('role:responsable');

        Route::post('/', [SiteController::class, 'store'])
            ->middleware('role:responsable');

        Route::get('/{site}', [SiteController::class, 'show'])
            ->middleware('role:responsable');

        Route::put('/{site}', [SiteController::class, 'update'])
            ->middleware('role:responsable');

        Route::delete('/{site}', [SiteController::class, 'destroy'])
            ->middleware('role:responsable');
    });

    // 📂 Module Agents
    Route::prefix('agent')->group(function () {
        Route::get('/', [AgentController::class, 'index'])
            ->middleware('role:responsable');

        Route::post('/', [AgentController::class, 'store'])
            ->middleware('role:responsable');

        Route::get('/{agent}', [AgentController::class, 'show'])
            ->middleware('role:responsable');

        Route::put('/{agent}', [AgentController::class, 'update'])
            ->middleware('role:responsable');

        Route::delete('/{agent}', [AgentController::class, 'destroy'])
            ->middleware('role:responsable');
    });


});
