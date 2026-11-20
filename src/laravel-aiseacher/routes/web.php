<?php

use App\Http\Controllers\Api\ToolController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('directory');
})->name('home');

Route::get('/dashboard', function (Illuminate\Http\Request $request) {
    return redirect()->route(
        $request->user()->isAdmin() ? 'admin.tools' : 'home',
    );
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/tools', [ToolController::class, 'admin'])->name('admin.tools');
        Route::post('/tools', [ToolController::class, 'store'])->name('admin.tools.store');
    });
});

require __DIR__.'/auth.php';
