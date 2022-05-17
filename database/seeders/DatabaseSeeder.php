<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make(123),
        ]);

        \App\Models\User::factory(5)
            ->create()
            ->each(function ($user) {
                $user
                    ->posts()
                    ->saveMany(\App\Models\Post::factory(2)->create());
            });
    }
}
