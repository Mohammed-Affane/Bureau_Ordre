<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $admin = Role::create(['name' => 'admin']);
        $bo = Role::create(['name' => 'bo']);
        $cab = Role::create(['name' => 'cab']);
        $sg = Role::create(['name' => 'sg']);
        $dai = Role::create(['name' => 'dai']);
        $chef_division = Role::create(['name' => 'chef_division']);

        // Create permissions
        $permissions = [
            'create courrier',
            'edit courrier',
            'delete courrier',
            'view courrier',
            'affect courrier',
            'treat courrier',
            'manage users',
            'manage roles',
            'view statistics'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $admin->givePermissionTo(Permission::all());
        
        $bo->givePermissionTo([
            'create courrier',
            'edit courrier',
            'view courrier'
        ]);

        $cab->givePermissionTo([
            'view courrier',
            'affect courrier'
        ]);

        $sg->givePermissionTo([
            'view courrier',
            'affect courrier'
        ]);

        $chef_division->givePermissionTo([
            'view courrier',
            'treat courrier'
        ]);
    }
}
