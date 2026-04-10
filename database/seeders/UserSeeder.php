<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Petugas
        User::updateOrCreate(
            ['username' => 'petugas'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'alamat' => 'Alamat Petugas',
                'no_telp' => '081234567890',
            ]
        );

        // Akun Kepala
        User::updateOrCreate(
            ['username' => 'kepala'],
            [
                'name' => 'Kepala Perpustakaan',
                'password' => Hash::make('password'),
                'role' => 'kepala',
                'alamat' => 'Alamat Kepala',
                'no_telp' => '081234567891',
            ]
        );
        
        // Akun Anggota
        User::updateOrCreate(
            ['username' => 'anggota'],
            [
                'name' => 'Anggota Perpustakaan',
                'password' => Hash::make('password'),
                'role' => 'anggota',
                'alamat' => 'Alamat Anggota',
                'no_telp' => '081234567892',
            ]
        );
    }
}
