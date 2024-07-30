<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('roles')->insert([
            'role_id' => Uuid::uuid4()->toString(),
            'role_name' => 'admin',
            'role_description' => 'The Admin has comprehensive control over both vendors and users within the system. Key responsibilities include overseeing the registration, modification, and deactivation of vendor and user accounts, evaluating and handling partnerships with vendors to ensure alignment with company standards and goals, and accessing detailed dashboards to monitor and analyze the interactions and activities of both users and vendors. This role ensures the seamless integration and interaction between vendors and users, maintaining the overall functionality and quality of the platform.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('roles')->insert([
            'role_id' => Uuid::uuid4()->toString(),
            'role_name' => 'vendor',
            'role_description' => 'A Vendor can manage orders and update their status. They have access to a personalized dashboard where they can oversee their stores operations and monitor the status of all their orders.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('roles')->insert([
            'role_id' => Uuid::uuid4()->toString(),
            'role_name' => 'user',
            'role_description' => 'A User can browse through the app to discover and order books, add books to their favorites, and manage their shopping cart.',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
