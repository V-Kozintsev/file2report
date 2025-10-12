<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Отчёты (загрузка/скачивание)
    Route::get('/reports', [FileUploadController::class, 'index'])->name('reports.index');
    Route::post('/reports/upload', [FileUploadController::class, 'upload'])->name('reports.upload');
    Route::get('/reports/download/{filename}', [FileUploadController::class, 'downloadFiltered'])->name('reports.download');

    // Игра
    Route::get('/game', fn () => view('game.index')) -> name('game');
    Route::get('/game/game-fullscreen', fn () => view('game.game-fullscreen')) -> name('game.fullscreen');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
