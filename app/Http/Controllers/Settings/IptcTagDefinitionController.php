<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\IptcTagDefinitionRequest;
use App\Http\Resources\IptcTagDefinitionResource;
use App\Models\IptcTagDefinition;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class IptcTagDefinitionController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        $this->authorize('viewAny', IptcTagDefinition::class);

        return Inertia::render('settings/iptcTagDefinitions/Index', [
            'globalDefinitions' => IptcTagDefinitionResource::collection(IptcTagDefinition::whereNull('user_id')->get()),
            'customDefinitions' => IptcTagDefinitionResource::collection(IptcTagDefinition::whereNotNull('user_id')->get()),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('settings/iptcTagDefinitions/Create');
    }

    public function store(IptcTagDefinitionRequest $request): RedirectResponse
    {
        $this->authorize('create', IptcTagDefinition::class);

        Auth::user()->iptcTagDefinitions()->create([
            'is_value_editable' => true,
            ...$request->validated(),
        ]);

        return redirect()->route('settings.iptc-tag-definitions.index')
            ->with('success', __('The IPTC tag definition has been created.'));
    }

    public function show(IptcTagDefinition $iptcTagDefinition)
    {
        $this->authorize('view', $iptcTagDefinition);

        return new IptcTagDefinitionResource($iptcTagDefinition);
    }

    public function edit(IptcTagDefinition $iptcTagDefinition): Response
    {
        return Inertia::render('settings/iptcTagDefinitions/Edit', [
            'iptcTagDefinition' => new IptcTagDefinitionResource($iptcTagDefinition),
        ]);
    }

    public function update(IptcTagDefinitionRequest $request, IptcTagDefinition $iptcTagDefinition): RedirectResponse
    {
        $this->authorize('update', $iptcTagDefinition);

        $iptcTagDefinition->update($request->validated());

        return redirect()->route('settings.iptc-tag-definitions.index')
            ->with('success', __('The IPTC tag definition has been updated.'));
    }

    public function destroy(IptcTagDefinition $iptcTagDefinition): RedirectResponse
    {
        $this->authorize('delete', $iptcTagDefinition);

        $iptcTagDefinition->delete();

        return redirect()->route('settings.iptc-tag-definitions.index')
            ->with('success', __('The IPTC tag definition has been deleted.'));
    }
}
