<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/contacts', ContactController::class);
Route::put('/contacts/{contact}/restore', [ContactController::class, 'restore']);

Route::apiResource('/organizations', OrganizationController::class);
Route::put('/organizations/{organization}/restore', [OrganizationController::class, 'restore']);

Route::apiResource('/users', UserController::class);
Route::put('/users/{user}/restore', [UserController::class, 'restore']);

Route::get('/img/{path}', [ImageController::class, 'show'])
    ->where('path', '.*')
    ->name('image');
