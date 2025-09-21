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
                'user_id' => 1,
            ],
            [
                'name' => 'Inter',
                'user_id' => 1,
            ],
            [
                'name' => 'Itaú',
                'user_id' => 1,
            ],
            [
                'name' => 'Bradesco',
                'user_id' => 1,
            ],
            [
                'name' => 'Santander',
                'user_id' => 1,
            ],
        ]);
    }
}
