<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Database\Seeders\PermissionTableSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'admin',
            'guard_name' => 'api'
        ]);

        Role::create([
            'name' => 'kaprog',
            'guard_name' => 'api'
        ]);
        Role::create([
            'name' => 'pembimbing',
            'guard_name' => 'api'
        ]);
        Role::create([
            'name' => 'siswa',
            'guard_name' => 'api'
        ]);
    }
}
