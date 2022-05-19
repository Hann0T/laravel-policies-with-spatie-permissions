<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[
            \Spatie\Permission\PermissionRegistrar::class
        ]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit own posts']);
        Permission::create(['name' => 'edit all posts']);
        Permission::create(['name' => 'delete own posts']);
        Permission::create(['name' => 'delete any posts']);
        Permission::create(['name' => 'create posts']);
        Permission::create(['name' => 'view unpublish posts']);
        Permission::create(['name' => 'view publish posts']);

        // this can be done as separate statements
        $role = Role::create(['name' => 'writer']);

        $role->givePermissionTo(
            'edit own posts',
            'create posts',
            'delete own posts'
        );

        $role = Role::create(['name' => 'super-admin']);

        $role->givePermissionTo(Permission::all());

        $user = \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make(123),
        ]);

        $user->assignRole('super-admin');

        $user = \App\Models\User::factory()->create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make(123),
        ]);

        \App\Models\Post::factory(4)->create([
            'user_id' => $user->id,
        ]);

        $user->assignRole('writer');

        $users = \App\Models\User::factory(5)
            ->create()
            ->each(function ($user) {
                $user
                    ->posts()
                    ->saveMany(\App\Models\Post::factory(2)->create());
            });

        $users->map(function ($user) {
            $user->assignRole('writer');
        });
    }
}
