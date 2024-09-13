<?php

namespace Database\Seeders;

use App\Models\roles;
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
        $admin = roles::where('role_name', '=', 'admin')->first();
        $vendor = roles::where('role_name', '=', 'vendor')->first();

        DB::table('users')->insert([
            'user_id' => Uuid::uuid4()->toString(),
            'name' => 'admin',
            'password' => Hash::make('admin'),
            'role_id' => $admin->role_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'user_id' => Uuid::uuid4()->toString(),
            'name' => 'vendor1',
            'email' => 'vendor1@gmail.com',
            'password' => Hash::make('vendor1'),
            'role_id' => $vendor->role_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
