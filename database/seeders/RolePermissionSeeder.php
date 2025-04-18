<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $staff = Role::create(['name' => 'staff']);
        $customer = Role::create(['name' => 'customer']);

        $permissions = ['manage users', 'manage products', 'view orders'];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $admin->givePermissionTo(Permission::all());
        $manager->givePermissionTo(['manage products', 'view orders']);
        $staff->givePermissionTo(['view orders']);
        $customer->givePermissionTo([' ']);
    }
}
