<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\IptcItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class IptcItemFactory extends Factory
{
    protected $model = IptcItem::class;

    public function definition(): array
    {
        return [
            'tag' => $this->faker->word(),
            'value' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'file_id' => File::factory(),
        ];
    }
}
