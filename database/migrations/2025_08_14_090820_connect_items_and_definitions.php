<?php

use App\Models\IptcItem;
use App\Models\IptcTagDefinition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iptc_items', function (Blueprint $table) {
            $table->after('tag', function (Blueprint $table) {
                $table->foreignIdFor(IptcTagDefinition::class)->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            });
        });

        IptcItem::with(['file', 'file.storageConfig'])->each(function (IptcItem $item) {
            $dbTag = IptcTagDefinition::find($item->tag, $item->file->storageConfig->user);

            $item->update([
                'iptc_tag_definition_id' => $dbTag->id,
            ]);
        });

        // Make new column non-nullable
        Schema::table('iptc_items', function (Blueprint $table) {
            $table->foreignIdFor(IptcTagDefinition::class)->nullable(false)->change();
        });

        Schema::dropColumns('iptc_items', ['tag']);
    }
};
