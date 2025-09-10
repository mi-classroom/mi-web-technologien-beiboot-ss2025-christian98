<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IptcTagDefinitionResource;
use App\Models\IptcTagDefinition;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class IptcTagDefinitionController extends Controller
{
    use AuthorizesRequests;

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', IptcTagDefinition::class);

        return IptcTagDefinitionResource::collection(
            IptcTagDefinition::whereNull('user_id')
                ->orWhere('user_id', Auth::id())
                ->get()
        );
    }

    public function store(Request $request): IptcTagDefinitionResource
    {
        $this->authorize('create', IptcTagDefinition::class);

        $iptcTagDefinition = Auth::user()->iptcTagDefinitions()->create([
            'is_value_editable' => true,
            ...$request->all(),
        ]);

        return new IptcTagDefinitionResource($iptcTagDefinition);
    }

    public function show(IptcTagDefinition $iptcTagDefinition): IptcTagDefinitionResource
    {
        $this->authorize('view', $iptcTagDefinition);

        return new IptcTagDefinitionResource($iptcTagDefinition);
    }

    public function update(Request $request, IptcTagDefinition $iptcTagDefinition): IptcTagDefinitionResource
    {
        $this->authorize('update', $iptcTagDefinition);

        $iptcTagDefinition->update($request->all());

        return new IptcTagDefinitionResource($iptcTagDefinition);
    }

    public function destroy(IptcTagDefinition $iptcTagDefinition): IptcTagDefinitionResource
    {
        $this->authorize('delete', $iptcTagDefinition);

        $iptcTagDefinition->delete();

        return new IptcTagDefinitionResource($iptcTagDefinition);
    }
}
