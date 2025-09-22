<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ])
            ->each(function (User $user) {
                $cards = Card::factory()->count(2)->forUser($user)->create();

                $incomeCats  = Category::factory()->count(4)->income()->forUser($user)->create();
                $expenseCats = Category::factory()->count(8)->expense()->forUser($user)->create();

                Category::factory()->count(2)->both()->forUser($user)->create();

                Transaction::factory()
                    ->count(100)
                    ->forUser($user)
                    ->make()
                    ->each(function (Transaction $t) use ($user, $cards, $incomeCats, $expenseCats) {
                        if ($t->type === 'Receita') {
                            $t->category_id = $incomeCats->random()->id;
                            $t->card_id = null;
                        } else {
                            $t->category_id = $expenseCats->random()->id;
                            $t->card_id = fake()->boolean(80) ? $cards->random()->id : null;
                        }
                        $t->save();
                    });
            });
    }
}
