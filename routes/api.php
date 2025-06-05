<?php

use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\FolderController;
use App\Http\Controllers\Api\FolderFolderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // TODO endpoint to fetch various files by id (editor), check needed for permissions
    Route::apiResource('folders', FolderController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::get('/folders/{folder}/folders', [FolderFolderController::class, 'index'])->name('folders.folders.index');
    Route::post('/folders/{folder}/folders', [FolderFolderController::class, 'store'])->name('folders.folders.store');
    Route::apiResource('folders.files', FileController::class)->shallow();
    Route::apiResource('files.iptc', FileController::class)->shallow();
});
