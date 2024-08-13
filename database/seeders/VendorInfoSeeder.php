<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class VendorInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendor_partnership_informations')->insert([
            'vendor_info_id' => Uuid::uuid4()->toString(),
            'vendor_application_id' => '4cc9c164-91fb-4d3a-ac57-95fc27bca085',
            'vendor_name' => 'All to Know',
            'email' => 'vendor1@gmail.com',
            'phone_number' => '+66644585592',
            'vendor_description' => 'accepted',
            'facebook_link' => 'https://www.facebook.com/',
            'instagram_link' => 'https://www.instagram.com/',
            'youtube_link' => 'https://www.youtube.com/',
            'x_link' => 'https://x.com/',
            'other_link' => 'NA',
            'vendor_id' => 'da3407f1-2971-4811-93fd-0c4afd117cbc',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
