<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FolderFileController;
use App\Http\Controllers\FolderFolderController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('folders', FolderController::class)
    ->middleware(['auth', 'verified'])
    ->only(['index', 'show']);
Route::post('/folders/{folder}', [FolderFolderController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('folders.folders.store');
Route::resource('folders.files', FolderFileController::class)
    ->middleware(['auth', 'verified'])
    ->only(['store']);
Route::resource('files', FileController::class)
    ->middleware(['auth', 'verified'])
    ->only(['show', 'update', 'destroy']);
Route::get('files/{file}/download', [FileController::class, 'download'])
    ->middleware(['auth', 'verified'])
    ->name('files.download');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
