<?php

namespace Database\Seeders;

use App\Models\Jenjang_Kelas;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('123456789')
        ]);

        Jenjang_Kelas::create([
            'jenjang' => 'XII',
            'status' => 1,
            'hidden' => 0,
            'action_by' => 1
        ]);

        Jenjang_Kelas::create([
            'jenjang' => 'XI',
            'status' => 1,
            'hidden' => 0,
            'action_by' => 1
        ]);

        Jenjang_Kelas::create([
            'jenjang' => 'X',
            'status' => 1,
            'hidden' => 0,
            'action_by' => 1
        ]);

        Jenjang_Kelas::create([
            'jenjang' => 'OFF',
            'status' => 1,
            'hidden' => 1,
            'action_by' => 1
        ]);
    }
}
