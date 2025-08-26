<?php


use App\Http\Controllers\PhpDocTestController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-docs', [PhpDocTestController::class, 'index']);
