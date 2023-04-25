<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@buckhill.co.uk',
            'password' => Hash::make('admin'),
        ]);

        \App\Models\User::factory(10)->create();
    }
}
