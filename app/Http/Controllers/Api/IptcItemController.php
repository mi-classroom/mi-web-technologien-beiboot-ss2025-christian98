<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateIptcItemRequest;
use App\Http\Requests\UpdateIptcItemRequest;
use App\Http\Resources\IptcItemResource;
use App\Models\File;
use App\Models\IptcItem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IptcItemController extends Controller
{
    use AuthorizesRequests;

    // region nested resources
    public function index(File $file): AnonymousResourceCollection
    {
        $this->authorize('view', $file);

        return IptcItemResource::collection($file->iptcItems);
    }

    public function store(CreateIptcItemRequest $request, File $file): IptcItemResource
    {
        $this->authorize('create', [IptcItem::class, $file]);

        $iptcItem = $file->iptcItems()->updateOrCreate(
            [
                'iptc_tag_definition_id' => $request->validated('tag')
            ],
            $request->except('tag')
        );
        $file->touch();

        return new IptcItemResource($iptcItem);
    }
    // endregion

    // region single resource
    public function show(IptcItem $iptc): IptcItemResource
    {
        $this->authorize('view', $iptc);

        return new IptcItemResource($iptc);
    }

    public function update(UpdateIptcItemRequest $request, IptcItem $iptc): IptcItemResource
    {
        $this->authorize('update', $iptc);

        $iptc->update($request->validated());
        $iptc->file->touch();

        return new IptcItemResource($iptc);
    }

    public function destroy(IptcItem $iptc): JsonResponse
    {
        $this->authorize('delete', $iptc);

        $iptc->delete();

        return response()->json(['message' => 'IPTC item deleted successfully.']);
    }
    // endregion
}
