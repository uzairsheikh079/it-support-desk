<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Demo Administrator',
            'email' => config('supportdesk.demo_email'),
            'password' => config('supportdesk.demo_password'),
        ]);

        $this->call(TicketSeeder::class);
    }
}
