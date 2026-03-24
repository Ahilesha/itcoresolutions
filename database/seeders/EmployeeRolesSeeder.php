<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeRole;

class EmployeeRolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Worker', 'description' => 'General worker / shop-floor staff'],
            ['name' => 'Supervisor', 'description' => 'Team supervisor'],
            ['name' => 'Storekeeper', 'description' => 'Inventory/store responsible'],
        ];

        foreach ($roles as $role) {
            EmployeeRole::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
