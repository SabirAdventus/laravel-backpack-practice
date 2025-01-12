<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        for ($i=0; $i < 100; $i++) { 
            $model = new \App\Models\Product();
            $model->name = fake()->domainName('product');
            $model->description = fake()->text(200);
            $model->image = fake()->imageUrl();
            $model->category_id = 1;
            $model->save();
            
        }
    }
}
