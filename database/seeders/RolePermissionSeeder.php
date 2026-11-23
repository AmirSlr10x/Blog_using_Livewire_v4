<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset chashed roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        //create permissions
        $permissions = [
            'create post',
            'edit own post',
            'edit all posts',
            'delete own post',
            'delete all posts',
            'publish posts',
            'manage users',
            'manage posts',
        ];

        foreach ($permissions as $permission) {
            # code ...
            Permission::create(['name' => $permission]);
        }

        //create role and assign permission
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $editorRole = Role::create(['name' => 'editor']);
        $editorRole->givePermissionTo([
            'create post',
            'edit all posts',
            'delete all posts',
            'publish posts',
        ]);

        $authorRole = Role::create(['name' => 'author']);
        $authorRole->givePermissionTo([
            'create post',
            'edit own post',
            'delete own post',
        ]);

        $subscriberRole = Role::create(['name' => 'subscriber']);
        // subscriber has no permissions , they can just read ( view )

    }
}
