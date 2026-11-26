<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Api\ToolController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('directory');
})->name('home');

Route::get('/dashboard', function (Request $request) {
    return redirect()->route(
        $request->user()->isAdmin() ? 'admin.tools' : 'home',
    );
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
        Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/tools', [ToolController::class, 'admin'])->name('admin.tools');
        Route::post('/tools', [ToolController::class, 'store'])->name('admin.tools.store');
    });
});

require __DIR__.'/auth.php';
