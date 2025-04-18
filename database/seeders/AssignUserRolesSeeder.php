<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AssignUserRolesSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($user->role === 'admin') {
                $user->assignRole('admin');
            } elseif ($user->role === 'customer') {
                $user->assignRole('customer');
            }
        }
    }
}
