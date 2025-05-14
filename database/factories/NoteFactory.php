<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug(),
            'body' => $this->faker->realText(),
            'view_limit' => $this->faker->randomNumber(),
            'expires_at' => Carbon::now()->addDays($this->faker->randomNumber()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
