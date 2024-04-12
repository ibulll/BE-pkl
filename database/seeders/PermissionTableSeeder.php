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
        'roles.delete',
        'pengajuan.detail',
        // 'perusahaan.getPerusahaan',
        // 'perusahaan.index',
        // 'perusahaan.store',
        // 'perusahaan.show',
        // 'perusahaan.update',
        // 'perusahaan.destroy'
    ];

    protected $permissionSiswa = [
        'pengajuan.index',
        'pengajuan.store',
        'jurnal.index',
        'jurnal.store',
        'jurnal.update',
        'jurnal.destroy'
    ];

    protected $permissionPembimbing = [
        'pengajuan.index',
        'pengajuan.updateStatus',
        'pengajuan.detail',
        // Add more permissions for pembimbing here
    ];
    
    protected $permissionKaprog = [
        'pengajuan.index',
        'pengajuan.updateStatus',
        'pengajuan.detail',
        // Add more permissions for kaprog here
    ];

    /**
     * Run the database seeds.
     */
    public function run()
    {
        //permissions admin
        Permission::firstOrCreate(['name' => 'users.index', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'users.store', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'users.update', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'users.delete', 'guard_name' => 'api']);

        Permission::firstOrCreate(['name' => 'permissions.index', 'guard_name' => 'api']);

        Permission::firstOrCreate(['name' => 'roles.index', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'roles.create', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'roles.edit', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'roles.delete', 'guard_name' => 'api']);

        Permission::firstOrCreate(['name' => 'pengajuan.updateStatus', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'pengajuan.detail', 'guard_name' => 'api']);

        //permissions siswa
        Permission::firstOrcreate(['name' => 'pengajuan.index', 'guard_name' => 'api']);
        Permission::firstOrcreate(['name' => 'pengajuan.store', 'guard_name' => 'api']);
        Permission::firstOrcreate(['name' => 'jurnal.index', 'guard_name' => 'api']);
        Permission::firstOrcreate(['name' => 'jurnal.store', 'guard_name' => 'api']);
        Permission::firstOrcreate(['name' => 'jurnal.update', 'guard_name' => 'api']);
        Permission::firstOrcreate(['name' => 'jurnal.destroy', 'guard_name' => 'api']);

        //permissions pembimbing


        // Assign permissions to roles
        $roles = Role::all();

        foreach ($roles as $role) {
            // Check the role
            if ($role->name === 'admin') {
                $role->syncPermissions($this->permissionAdmin);
            } elseif ($role->name === 'siswa') {
                $role->syncPermissions($this->permissionSiswa);
            } elseif ($role->name === 'pembimbing') {
                $role->syncPermissions($this->permissionPembimbing);
            } elseif ($role->name === 'kaprog') {
                $role->syncPermissions($this->permissionKaprog);
            }
        }
    }
}

