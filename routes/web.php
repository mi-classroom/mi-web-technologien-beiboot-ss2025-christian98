<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Local\FileController;
use App\Http\Controllers\Local\FolderController;
use App\Http\Controllers\Local\FolderFileController;
use App\Http\Controllers\Local\FolderFolderController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'show'])->middleware(['auth', 'verified'])->name('dashboard');

// region local storage
Route::middleware(['auth', 'verified'])->prefix('local')->name('local.')->group(function () {
    Route::resource('folders', FolderController::class)->only(['index', 'destroy']);
    Route::get('/folders/{folder}', [FolderController::class, 'show'])
        ->where('folder', '.*')->name('folders.show');
    Route::post('/folders/{folder}', [FolderFolderController::class, 'store'])->name('folders.folders.store');
    Route::resource('folders.files', FolderFileController::class)->only(['store']);
    Route::resource('files', FileController::class)->only(['show', 'update', 'destroy']);
    Route::get('files/{file}/download', [FileController::class, 'download'])->name('files.download');
});
// endregion


Route::post('/locale', [LocaleController::class, 'setLocale'])
    ->name('locale.set');
Route::get('/locale', [LocaleController::class, 'getLocale'])
    ->name('locale.get');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
