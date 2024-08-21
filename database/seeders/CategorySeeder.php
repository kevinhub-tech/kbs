<?php

namespace Database\Seeders;

use App\Models\roles;
use App\Models\users;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $role = roles::where('role_name', '=', 'admin')->first();
        $admin =  users::where('role_id', '=', $role->role_id)->first();
        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Fiction',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Novel',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Mystery',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Science-Fiction',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Romance Novel',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'History Fiction',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Non-fiction',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Genre-fiction',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);


        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Thriller',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Literacy fiction',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Self-help book',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Children literature',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Biography',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Young adult literature',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Fantasy',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Womans Fiction',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Short Story',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Horror Fiction',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Essay',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'History',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Autobiography',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Graphic novel',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Fantasy Fiction',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'History fantasy',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Poetry',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Horror',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Humour',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'True crime',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Spirituality',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Historical romance',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Science',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Contemporary Romance',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Western Fiction',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Action Fiction',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Magical Realism',
            'created_by' => $admin->user_id,
            'created_at' => now(),
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Travel literature',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Social Science',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Philosophy',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'New adult fiction',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Psychology',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);
        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Fairy Tale',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Drama',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Fan Fiction',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);

        DB::table('categories')->insert([
            'category_id' => Uuid::uuid4()->toString(),
            'category' => 'Science Fiction (Sci-fi)',
            'created_by' => $admin->user_id,
            'created_at' => now()
        ]);
    }
}
