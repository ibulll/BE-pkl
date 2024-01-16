<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $kaprog = User::create([
            'name' => 'Kaprog',
            'email' => 'kaprog@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $kaprog->assignRole('kaprog');

        $pembimbing = User::create([
            'name' => 'Pembimbing',
            'email' => 'pembimbing@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $pembimbing->assignRole('pembimbing');

        $siswa = User::create([
            'name' => 'Siswa',
            'email' => 'siswa@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $siswa->assignRole('siswa');

    }
}
