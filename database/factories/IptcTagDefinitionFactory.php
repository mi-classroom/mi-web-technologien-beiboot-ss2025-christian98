<?php

namespace Database\Factories;

use App\Models\IptcTagDefinition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IptcTagDefinitionFactory extends Factory
{
    protected $model = IptcTagDefinition::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'tag' => $this->faker->word(),
            'description' => $this->faker->text(),
            'spec' => [
                'data_type' => 'string',
                'min_length' => 0,
                'max_length' => 255,
                'multiple' => true,
                'required' => false,
                'enum_values' => null,
            ],
            'is_value_editable' => $this->faker->boolean(),

            'user_id' => User::factory(),
        ];
    }
}
