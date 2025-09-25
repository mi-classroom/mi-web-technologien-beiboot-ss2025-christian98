<?php

use App\Models\Folder;
use App\Models\StorageConfig;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status')->default('connected');
            $table->timestamp('last_indexed_at')->nullable();
            $table->foreignIdFor(Folder::class, 'root_folder_id')->nullable()->constrained()->nullOnUpdate()->nullOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('provider_type');
            $table->text('provider_options');
            $table->boolean('is_editable');
            $table->timestamps();
        });

        Schema::table('folders', function (Blueprint $table) {
            $table->foreignIdFor(StorageConfig::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });

        Schema::table('files', function (Blueprint $table) {
            $table->foreignIdFor(StorageConfig::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['storage_config_id']);
            $table->dropColumn('storage_config_id');
        });
        Schema::table('folders', function (Blueprint $table) {
            $table->dropForeign(['storage_config_id']);
            $table->dropColumn('storage_config_id');
        });
        Schema::dropIfExists('storage_configs');
    }
};
