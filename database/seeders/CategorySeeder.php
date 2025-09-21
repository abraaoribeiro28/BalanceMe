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
                'color' => '#ef4444', // vermelho
                'user_id' => 1,
            ],
            [
                'name' => 'Transporte',
                'type' => 'Despesa',
                'color' => '#f97316', // laranja
                'user_id' => 1,
            ],
            [
                'name' => 'Moradia',
                'type' => 'Despesa',
                'color' => '#84cc16', // verde claro
                'user_id' => 1,
            ],
            [
                'name' => 'Lazer',
                'type' => 'Despesa',
                'color' => '#0d9488', // ciano
                'user_id' => 1,
            ],
            [
                'name' => 'Saúde',
                'type' => 'Despesa',
                'color' => '#9333ea', // roxo
                'user_id' => 1,
            ],
            [
                'name' => 'Educação',
                'type' => 'Despesa',
                'color' => '#ec4899', // rosa
                'user_id' => 1,
            ],
            [
                'name' => 'Salário',
                'type' => 'Receita',
                'color' => '#22c55e', // verde
                'user_id' => 1,
            ],
            [
                'name' => 'Freelance',
                'type' => 'Receita',
                'color' => '#10b981', // verde esmeralda
                'user_id' => 1,
            ],
            [
                'name' => 'Investimentos',
                'type' => 'Receita',
                'color' => '#3b82f6', // azul
                'user_id' => 1,
            ],
            [
                'name' => 'Outros',
                'type' => 'Ambos',
                'color' => '#6b7280', // cinza
                'user_id' => 1,
            ],
        ]);
    }
}
