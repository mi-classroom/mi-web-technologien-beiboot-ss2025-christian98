<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\StorageConfigController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->name('settings.')->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance');

    Route::get('settings/storage', [StorageConfigController::class, 'index'])->name('storage.index');
    Route::get('settings/storage/create', [StorageConfigController::class, 'create'])->name('storage.create');
    Route::get('settings/storage/{config}/edit', [StorageConfigController::class, 'edit'])->name('storage.edit');
    Route::post('settings/storage', [StorageConfigController::class, 'store'])->name('storage.store');
    Route::put('settings/storage/{config}', [StorageConfigController::class, 'update'])->name('storage.update');
    Route::post('settings/storage/{config}/re-index', [StorageConfigController::class, 'reIndex'])->name('storage.re-index');
    Route::delete('settings/storage/{config}', [StorageConfigController::class, 'destroy'])->name('storage.destroy');
});
