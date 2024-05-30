<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class TableUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data pengguna yang akan dimasukkan ke dalam tabel 'users'
        $userData = [
            [
                'name' => 'Admin',
                'email' => 'admin1@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('admin1'), // Mengenkripsi password
                'username' => '105101',
            ],
            [
                'name' => 'Dosen 1',
                'email' => 'dosen1@gmail.com',
                'role' => 'dosen',
                'password' => Hash::make('dosen1'), // Mengenkripsi password
                'username' => '105201',
            ],
            [
                'name' => 'Mahasiswa 1',
                'email' => 'mahasiswa1@gmail.com',
                'role' => 'mahasiswa',
                'password' => Hash::make('mahasiswa1'), // Mengenkripsi password
                'username' => '105220001',
            ],
        ];

        // Melakukan iterasi melalui setiap elemen dalam array $userData
        foreach ($userData as $key => $val) {
            // Membuat entri baru di tabel 'users' dengan menggunakan model User
            $user = User::create($val);
            
            if($user -> role == 'admin'){
                Admin::create([
                    'nip' => $user->username,
                    'id_users' => $user->id_users
                ]);
            } else if($user -> role == 'dosen'){
                Dosen::create([
                    'nip' => $user->username,
                    'id_users' => $user->id_users
                ]);
            } else if($user -> role == 'mahasiswa'){
                Mahasiswa::create([
                    'nim' => $user->username,
                    'id_users' => $user->id_users
                ]);
            }
        }
    }
}
