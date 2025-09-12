<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Storage\FileController;
use App\Http\Controllers\Storage\FolderController;
use App\Http\Controllers\Storage\FolderFileController;
use App\Http\Controllers\Storage\FolderFolderController;
use App\Http\Controllers\Storage\IptcItemController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'show'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('editor', [EditorController::class, 'show'])->middleware(['auth', 'verified'])->name('editor');
Route::redirect('local', 'local/folders')->middleware(['auth', 'verified'])->name('local');

Route::middleware(['auth', 'verified'])->prefix('storage/{storageConfig}')->name('storage.')->group(function () {
    // Folders
    Route::resource('folders', FolderController::class)->only(['index', 'destroy']);
    Route::get('/folders/{folder}', [FolderController::class, 'show'])->name('folders.show');
    Route::post('/folders/{folder}/folders', [FolderFolderController::class, 'store'])->name('folders.folders.store');
    Route::resource('folders.files', FolderFileController::class)->only(['store']);

    Route::resource('files', FileController::class)->only(['show', 'update', 'destroy']);
    Route::get('files/{file}/download', [FileController::class, 'download'])->name('files.download');
    Route::post('files/{file}/re-index', [FileController::class, 'reIndex'])->name('files.re-index');

    Route::resource('files.iptc', IptcItemController::class)->only(['store', 'update', 'destroy'])->shallow();
});

Route::post('/locale', [LocaleController::class, 'setLocale'])
    ->name('locale.set');
Route::get('/locale', [LocaleController::class, 'getLocale'])
    ->name('locale.get');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
