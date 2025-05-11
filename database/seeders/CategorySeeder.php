<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            "Teknologi",
            "Gaya Hidup",
            "Kesehatan",
            "Makanan & Minuman",
            "Travel",
            "Bisnis & Keuangan",
            "Pendidikan",
            "Seni & Budaya",
            "Otomotif",
            "Hiburan",
            "Olahraga",
            "Parenting",
            "Review Produk",
            "Hobi & Minat",
            "Psikologi",
            "Lingkungan",
            "Sejarah",
            "Filosofi",
            "Fotografi",
            "Desain",
            "Keamanan Siber",
            "Blockchain & Kripto",
        ];

        $now = now();
        $formated = [];
        foreach ($categories as $category) {
            $formated[] = [
                'name'          => $category,
                'slug'          => Str::slug($category),
                'created_at'    => $now,
            ];
        }

        Category::insert($formated);
    }
}
