<?php

namespace Database\Seeders;

use App\Models\Channels;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Channels::factory()->create([
            'name' => 'Google',
            'clientsCount' => 725
        ]);

        Channels::factory()->create([
            'name' => 'Facebook',
            'clientsCount' => 225
        ]);

        Channels::factory()->create([
            'name' => 'Instagram',
            'clientsCount' => 15
        ]);

        Channels::factory()->create([
            'name' => 'Twitter',
            'clientsCount' => 30
        ]);
        
        Channels::factory()->create([
            'name' => 'LinkedIn',
            'clientsCount' => 45
        ]);

    }
}
