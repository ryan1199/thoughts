<?php

namespace Database\Seeders;

use App\Models\Thought;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThoughtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            Thought::factory()->for(User::inRandomOrder()->first())->create();
        }
    }
}
