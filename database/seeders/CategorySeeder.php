<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
   // CategorySeeder.php
public function run(): void
{
    // 外部キー制約を一時無効化（MySQLの場合）
    \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    \App\Models\Category::truncate();

    \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    $categories = [
        ['category_id' => 'food', 'category_name' => '食費'],
        ['category_id' => 'transport', 'category_name' => '交通費'],
        ['category_id' => 'utility', 'category_name' => '光熱費'],
        ['category_id' => 'entertainment', 'category_name' => '娯楽'],
        ['category_id' => 'other', 'category_name' => 'その他'],
    ];

    foreach ($categories as $cat) {
        \App\Models\Category::create($cat);
    }
}


}
