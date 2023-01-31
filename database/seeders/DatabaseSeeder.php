<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make(env('TEST_USER_PASSWORD'))
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Test User2',
            'email' => 'test2@example.com',
            'password' => Hash::make(env('TEST_USER_PASSWORD'))
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Test User3',
            'email' => 'test3@example.com',
            'password' => Hash::make(env('TEST_USER_PASSWORD'))
        ]);

        Task::factory(100)->create();
    }
}
