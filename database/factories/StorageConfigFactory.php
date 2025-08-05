<?php

namespace Database\Factories;

use App\Models\StorageConfig;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StorageConfigFactory extends Factory
{
    protected $model = StorageConfig::class;

    public function definition(): array
    {
        return [
            'provider_type' => $this->faker->word(),
            'options' => $this->faker->words(),
            'is_editable' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
