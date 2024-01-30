<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    // Default permissions for different roles.
    protected $permissionAdmin = [
        'users.index',
        'users.store',
        'users.update',
        'users.delete',
        'permissions.index',
        'roles.index',
        'roles.create',
        'roles.edit',
        'roles.delete'
    ];

    protected $permissionSiswa = [
        'pengajuan.searchSiswa',
        'pengajuan.store'
    ];

    /**
     * Run the database seeds.
     */
    public function run()
    {
        //permissions admin
        Permission::Create(['name' => 'users.index', 'guard_name' => 'api']);
        Permission::Create(['name' => 'users.store', 'guard_name' => 'api']);
        Permission::Create(['name' => 'users.update', 'guard_name' => 'api']);
        Permission::Create(['name' => 'users.delete', 'guard_name' => 'api']);

        Permission::Create(['name' => 'permissions.index', 'guard_name' => 'api']); 

        Permission::Create(['name' => 'roles.index', 'guard_name' => 'api']);
        Permission::Create(['name' => 'roles.create', 'guard_name' => 'api']);
        Permission::Create(['name' => 'roles.edit', 'guard_name' => 'api']);
        Permission::Create(['name' => 'roles.delete', 'guard_name' => 'api']);

        //permissions siswa
        Permission::create(['name' => 'pengajuan.searchSiswa', 'guard_name' => 'api']);
        Permission::create(['name' => 'pengajuan.store', 'guard_name' => 'api']);

        
        // Assign permissions to roles
        $roles = Role::all();

        foreach ($roles as $role) {
            // Check the role
            if ($role->name === 'admin') {
                $role->syncPermissions($this->permissionAdmin);
            } elseif ($role->name === 'siswa') {
                $role->syncPermissions($this->permissionSiswa);
            } 
        }
    }
}
