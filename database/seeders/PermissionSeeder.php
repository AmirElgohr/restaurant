<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::query()->firstOrCreate([
            'name'  => 'admin',
            'label' => 'Admin',
        ]);

        Role::query()->firstOrCreate([
            'name'  => 'restaurant',
            'label' => 'Restaurant',
        ]);

    }
}
