<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # Load categories from json
        $categories = json_decode(file_get_contents(database_path().'/categories.json'), True);

        $timestamp = Carbon\Carbon::now()->toDateTimeString();

        foreach($categories as $category) {
            Category::insert([
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
                'name' => $category['name'],
                'search_term' => $category['term'],
            ]);
        }
    }
}
