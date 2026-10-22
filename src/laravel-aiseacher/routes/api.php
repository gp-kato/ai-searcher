<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('/tools', [ToolController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
