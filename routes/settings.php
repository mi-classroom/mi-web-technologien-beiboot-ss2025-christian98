<?php

use App\Http\Controllers\Settings\IptcTagDefinitionController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\StorageConfigController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->name('settings.')->prefix('settings')->group(function () {
    Route::redirect('settings', '/profile');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance');

    Route::get('storage', [StorageConfigController::class, 'index'])->name('storage.index');
    Route::get('storage/create', [StorageConfigController::class, 'create'])->name('storage.create');
    Route::get('storage/{config}/edit', [StorageConfigController::class, 'edit'])->name('storage.edit');
    Route::post('storage', [StorageConfigController::class, 'store'])->name('storage.store');
    Route::put('storage/{config}', [StorageConfigController::class, 'update'])->name('storage.update');
    Route::post('storage/{config}/re-index', [StorageConfigController::class, 'reIndex'])->name('storage.re-index');
    Route::delete('storage/{config}', [StorageConfigController::class, 'destroy'])->name('storage.destroy');

    Route::resource('iptc-tag-definitions', IptcTagDefinitionController::class);
});
