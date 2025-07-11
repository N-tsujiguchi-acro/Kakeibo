<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
        public function definition(): array
    {
        // 日本語カテゴリーの配列を定義
        $categories = [
            ['id' => 'food', 'name' => '食費'],
            ['id' => 'transport', 'name' => '交通費'],
            ['id' => 'utility', 'name' => '光熱費'],
            ['id' => 'entertainment', 'name' => '娯楽'],
            ['id' => 'other', 'name' => 'その他'],
        ];

        // ランダムに1つ選ぶ
        $category = $this->faker->randomElement($categories);

        return [
            'category_id' => $category['id'],
            'category_name' => $category['name'],
        ];
    }

}
