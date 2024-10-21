<?php

namespace Database\Seeders;

use App\Models\Reply;
use App\Models\Thought;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 500; $i++) {
            Reply::factory()->for(User::inRandomOrder()->first())->for(Thought::inRandomOrder()->first())->create();
        }
    }
}
