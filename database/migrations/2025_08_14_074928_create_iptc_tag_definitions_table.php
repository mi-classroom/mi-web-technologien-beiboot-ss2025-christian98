<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
    }

    public function down(): void
    {
        Schema::dropIfExists('iptc_tag_definitions');
    }
};
