<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Create the initial CPC Super Admin account.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'superadmin@cpc.local',
            ],
            [
                'epf_number' => 'SUPERADMIN',
                'name' => 'CPC Super Admin',
                'phone' => null,
                'role' => 'super_admin',
                'password' => Hash::make('CPC@SuperAdmin#2026!'),
                'employee_id' => null,
            ]
        );
    }
}