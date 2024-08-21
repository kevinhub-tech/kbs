<?php

namespace Database\Seeders;

use App\Models\roles;
use App\Models\users;
use App\Models\vendorApplication;
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
        $role = roles::where('role_name', '=', 'vendor')->first();
        $vendor = users::where('role_id', '=', $role->role_id)->first();
        $vendor_application = vendorApplication::first();
        DB::table('vendor_partnership_informations')->insert([
            'vendor_info_id' => Uuid::uuid4()->toString(),
            'vendor_application_id' => $vendor_application->application_id,
            'vendor_name' => 'All to Know',
            'email' => 'vendor1@gmail.com',
            'phone_number' => '+66644585592',
            'vendor_description' => 'accepted',
            'facebook_link' => 'https://www.facebook.com/',
            'instagram_link' => 'https://www.instagram.com/',
            'youtube_link' => 'https://www.youtube.com/',
            'x_link' => 'https://x.com/',
            'other_link' => 'NA',
            'vendor_id' => $vendor->user_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
