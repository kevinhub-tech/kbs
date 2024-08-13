<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class VendorApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendor_partnership_applications')->insert([
            'application_id' => Uuid::uuid4()->toString(),
            'email' => 'vendor1@gmail.com',
            'application_letter' => 'Hi there I wanna sell some books about philosophy and psychology.',
            'status' => 'accepted',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
