<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        DB::table('users')->insert([
            'user_id' => Uuid::uuid4()->toString(),
            'name' => 'admin',
            'password' => Hash::make('admin'),
            'role_id' => '1455643c-c622-4191-b0e5-982b308db92c',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'user_id' => Uuid::uuid4()->toString(),
            'name' => 'vendor1',
            'password' => Hash::make('vendor1'),
            'role_id' => 'f59817b6-b4eb-43e0-af3d-9c8500f15dd1',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
