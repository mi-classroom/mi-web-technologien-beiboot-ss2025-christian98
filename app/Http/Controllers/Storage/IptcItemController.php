<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateIptcItemRequest;
use App\Http\Resources\IptcItemResource;
use App\Models\File;
use App\Models\IptcItem;
use App\Models\StorageConfig;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class IptcItemController extends Controller
{
    use AuthorizesRequests;

    /**
     * @throws AuthorizationException
     */
    public function store(UpdateIptcItemRequest $request, StorageConfig $storageConfig, File $file): RedirectResponse
    {
        $this->authorize('create', [IptcItem::class, $file]);

        $iptcItem = $file->iptcItems()->updateOrCreate(
            $request->only('tag'),
            $request->except('tag')
        );
        $file->touch();

        return redirect()->back();
        // return new IptcItemResource($iptcItem);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateIptcItemRequest $request, StorageConfig $storageConfig, File $file, IptcItem $iptc): RedirectResponse
    {
        $this->authorize('update', $iptc);

        $iptc->update($request->validated());
        $file->touch();

        // return new IptcItemResource($iptcItem);
        return redirect()->back();
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(StorageConfig $storageConfig, File $file, IptcItem $iptc): RedirectResponse
    {
        $this->authorize('delete', $iptc);

        $iptc->delete();

        return redirect()->back();
        // return response()->json();
    }
}
