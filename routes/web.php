<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Local\FileController;
use App\Http\Controllers\Local\FolderController;
use App\Http\Controllers\Local\FolderFileController;
use App\Http\Controllers\Local\FolderFolderController;
use App\Http\Controllers\Local\IptcItemController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'show'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('editor', [DashboardController::class, 'show'])->middleware(['auth', 'verified'])->name('editor');
Route::redirect('local', 'local/folders')->middleware(['auth', 'verified'])->name('local');

// region local storage
Route::middleware(['auth', 'verified'])->prefix('local')->name('local.')->group(function () {
    Route::redirect('', 'local/folders')->name('index');
    Route::resource('folders', FolderController::class)->only(['index', 'destroy']);
    Route::get('/folders/{folder}', [FolderController::class, 'show'])
        ->where('folder', '.*')->name('folders.show');
    Route::post('/folders/{folder}', [FolderFolderController::class, 'store'])->name('folders.folders.store');
    Route::resource('folders.files', FolderFileController::class)->only(['store']);

    Route::resource('files', FileController::class)->only(['show', 'destroy']);
    Route::get('files/{file}/download', [FileController::class, 'download'])->name('files.download');
    Route::post('files/{file}/re-index', [FileController::class, 'reIndex'])->name('files.re-index');

    Route::resource('files.iptc', IptcItemController::class)->only(['store', 'update', 'destroy'])->shallow();
});
// endregion


Route::post('/locale', [LocaleController::class, 'setLocale'])
    ->name('locale.set');
Route::get('/locale', [LocaleController::class, 'getLocale'])
    ->name('locale.get');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
