<?php

use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\FolderController;
use App\Http\Controllers\Api\FolderFolderController;
use App\Http\Controllers\Api\IptcItemController;
use App\Http\Controllers\Api\IptcTagDefinitionController;
use App\Http\Controllers\Api\StorageConfigController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('storage-config', StorageConfigController::class)->only(['index', 'show']);
        Route::apiResource('folders', FolderController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::get('/folders/{folder}/folders', [FolderFolderController::class, 'index'])->name('folders.folders.index');
        Route::post('/folders/{folder}/folders', [FolderFolderController::class, 'store'])->name('folders.folders.store');
        Route::apiResource('folders.files', FileController::class)->shallow();
        Route::apiResource('files.iptc', IptcItemController::class)->shallow();
        Route::get('/files', [FileController::class, 'indexRoot'])->name('files.index');
        Route::get('/files/{file}/preview', [FileController::class, 'preview'])->name('files.preview');

        Route::apiResource('iptc-tag-definitions', IptcTagDefinitionController::class);
    });
});
