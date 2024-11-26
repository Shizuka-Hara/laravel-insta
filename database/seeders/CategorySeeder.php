<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    private $category;

    public function __construct(Category $category){
        $this->category = $category;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
            'name' => 'Magazine',
            'created_at' => now(),
            'updated_at' => now()
            ],
            [
            'name' => 'Bookds',
            'created_at' => now(),
            'updated_at' => now()
            ],
            [
            'name' => 'Fashion Magazine',
            'created_at' => now(),
            'updated_at' => now()
            ],
            [
            'name' => 'Sports Magazine',
            'created_at' => now(),
            'updated_at' => now()
            ]
            
        ];

        $this->category->insert($categories);
    }
}
