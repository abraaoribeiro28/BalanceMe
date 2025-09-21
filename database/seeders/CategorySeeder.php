<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Alimentação',
                'type' => 'Despesa',
                'user_id' => 1,
            ],
            [
                'name' => 'Transporte',
                'type' => 'Despesa',
                'user_id' => 1,
            ],
            [
                'name' => 'Moradia',
                'type' => 'Despesa',
                'user_id' => 1,
            ],
            [
                'name' => 'Lazer',
                'type' => 'Despesa',
                'user_id' => 1,
            ],
            [
                'name' => 'Saúde',
                'type' => 'Despesa',
                'user_id' => 1,
            ],
            [
                'name' => 'Educação',
                'type' => 'Despesa',
                'user_id' => 1,
            ],
            [
                'name' => 'Salário',
                'type' => 'Receita',
                'user_id' => 1,
            ],
            [
                'name' => 'Freelance',
                'type' => 'Receita',
                'user_id' => 1,
            ],
            [
                'name' => 'Investimentos',
                'type' => 'Receita',
                'user_id' => 1,
            ],
            [
                'name' => 'Outros',
                'type' => 'Ambos',
                'user_id' => 1,
            ],
        ]);
    }
}
