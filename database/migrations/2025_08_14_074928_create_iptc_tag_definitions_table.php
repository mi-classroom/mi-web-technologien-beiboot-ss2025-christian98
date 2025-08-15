<?php

use App\Models\IptcTagDefinition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('iptc_tag_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tag');
            $table->text('description')->nullable();
            $table->json('spec');
            $table->boolean('is_value_editable');
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });

        // Seed standard IPTC tag definitions
        $this->seedStandardDefinitions();
    }

    public function down(): void
    {
        Schema::dropIfExists('iptc_tag_definitions');
    }

    protected function seedStandardDefinitions(): void
    {
        $tags = require __DIR__.'/../data/iptc_core_tags.php'; // Assuming this file returns an array of IPTC tag objects

        foreach ($tags as $tag) {
            $tagId = Arr::get($tag, 'tag');
            $rest = Arr::except($tag, ['tag']);

            IptcTagDefinition::updateOrCreate(['tag' => $tagId], $rest);
        }
    }
};
