<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ToolFavoriteController;
use App\Http\Controllers\Api\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('/tools', [ToolController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/favorites', [ToolFavoriteController::class, 'index']);
Route::put('/favorites/{tool}', [ToolFavoriteController::class, 'store']);
Route::delete('/favorites/{tool}', [ToolFavoriteController::class, 'destroy']);
