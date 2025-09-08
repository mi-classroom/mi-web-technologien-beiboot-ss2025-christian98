<?php

namespace App\Http\Controllers;

use App\Http\Requests\IptcTagDefinitionRequest;
use App\Http\Resources\IptcTagDefinitionResource;
use App\Models\IptcTagDefinition;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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

    public function store(IptcTagDefinitionRequest $request)
    {
        $this->authorize('create', IptcTagDefinition::class);

        return new IptcTagDefinitionResource(IptcTagDefinition::create($request->validated()));
    }

    public function show(IptcTagDefinition $iptcTagDefinition)
    {
        $this->authorize('view', $iptcTagDefinition);

        return new IptcTagDefinitionResource($iptcTagDefinition);
    }

    public function update(IptcTagDefinitionRequest $request, IptcTagDefinition $iptcTagDefinition)
    {
        $this->authorize('update', $iptcTagDefinition);

        $iptcTagDefinition->update($request->validated());

        return new IptcTagDefinitionResource($iptcTagDefinition);
    }

    public function destroy(IptcTagDefinition $iptcTagDefinition)
    {
        $this->authorize('delete', $iptcTagDefinition);

        $iptcTagDefinition->delete();

        return response()->json();
    }
}
