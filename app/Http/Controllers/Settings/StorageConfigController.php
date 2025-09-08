<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorageConfigRequest;
use App\Http\Resources\StorageConfigResource;
use App\Jobs\IndexStorageJob;
use App\Models\StorageConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class StorageConfigController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('view-any', StorageConfig::class);

        return Inertia::render('settings/storageConfig/Index', [
            'configs' => StorageConfigResource::collection(auth()->user()->storageConfigs()->get()),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', StorageConfig::class);

        return Inertia::render('settings/storageConfig/Create');
    }

    public function store(StorageConfigRequest $request): RedirectResponse
    {
        Gate::authorize('create', StorageConfig::class);

        $storage = Auth::user()->storageConfigs()->create([
            ...$request->validated(),
            'is_editable' => true,
        ]);

        Bus::dispatch(new IndexStorageJob($storage));

        return redirect()->route('settings.storage.index')
            ->with('success', __('Storage configuration created successfully.'));
    }

    public function edit(StorageConfig $config): Response
    {
        Gate::authorize('update', $config);

        return Inertia::render('settings/storageConfig/Update', [
            'storageConfig' => new StorageConfigResource($config),
        ]);
    }

    public function update(StorageConfigRequest $request, StorageConfig $config): RedirectResponse
    {
        Gate::authorize('update', $config);

        $config->update($request->validated());

        return redirect()->route('settings.storage.index')
            ->with('success', __('Storage configuration updated successfully.'));
    }

    public function destroy(StorageConfig $config): RedirectResponse
    {
        Gate::authorize('delete', $config);

        $config->delete();

        return redirect()->route('settings.storage.index')
            ->with('success', __('Storage configuration deleted successfully.'));
    }

    public function reIndex(StorageConfig $config): RedirectResponse
    {
        Bus::dispatch(new IndexStorageJob($config));
        $config->update(['status' => 'indexing']);

        return redirect()
            ->back()
            ->with('success', __('Storage indexing started successfully.'));
    }
}
