<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@wetest.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create Guru
        User::create([
            'name' => 'Guru Demo',
            'email' => 'guru@wetest.com',
            'password' => bcrypt('password'),
            'role' => 'guru',
        ]);

        // Create sample Siswa
        Siswa::create([
            'name' => 'Budi Santoso',
            'tanggal_lahir' => '2008-05-15',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII-A',
            'nisn' => '0012345678',
        ]);

        Siswa::create([
            'name' => 'Siti Nurhaliza',
            'tanggal_lahir' => '2008-08-20',
            'jenis_kelamin' => 'P',
            'kelas' => 'XII-A',
            'nisn' => '0012345679',
        ]);

        Siswa::create([
            'name' => 'Ahmad Rizky',
            'tanggal_lahir' => '2008-03-10',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII-B',
            'nisn' => '0012345680',
        ]);

        $this->call([
            SoalMatematikaSeeder::class,
        ]);
    }
}
