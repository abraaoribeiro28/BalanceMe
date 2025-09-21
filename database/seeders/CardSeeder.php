<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cards')->insert([
            [
                'name' => 'Nubank',
                'color' => '#9333ea', // roxo
                'user_id' => 1,
            ],
            [
                'name' => 'Inter',
                'color' => '#f97316', // laranja
                'user_id' => 1,
            ],
            [
                'name' => 'Itaú',
                'color' => '#1e3a8a', // azul escuro
                'user_id' => 1,
            ],
            [
                'name' => 'Bradesco',
                'color' => '#ef4444', // vermelho
                'user_id' => 1,
            ],
            [
                'name' => 'Santander',
                'color' => '#dc2626', // vermelho mais forte
                'user_id' => 1,
            ],
        ]);
    }
}
