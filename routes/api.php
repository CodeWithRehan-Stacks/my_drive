-- Active: 1770986222765@@127.0.0.1@3306@my-drive
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DriveController;

Route::post('signUp', [AuthController::class, 'register']);
Route::post('logIn', [AuthController::class, 'logIn']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'userProfile']);
    Route::post('/logOut', [AuthController::class, 'logOut']);

    Route::get('/drive', [DriveController::class, 'index']);

    Route::post('/upload', [DriveController::class, 'upload']);
    Route::post('/create',[DriveController::class, 'createFolder']);

   Route::delete('/file/delete', [DriveController::class, 'destroyFile']);

   Route::delete('/folder/delete', [DriveController::class, 'destroyFolder']);
});