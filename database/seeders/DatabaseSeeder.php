<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Category;
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
                $cards = collect([
                    ['name' => 'Nubank'],
                    ['name' => 'Inter'],
                ])->map(fn (array $card) => Card::create([
                    'name' => $card['name'],
                    'user_id' => $user->id,
                ]));

                $incomeCats = collect([
                    ['name' => 'Salário', 'type' => 'Receita'],
                    ['name' => 'Freelance', 'type' => 'Receita'],
                    ['name' => 'Rendimentos', 'type' => 'Receita'],
                    ['name' => 'Investimentos', 'type' => 'Receita'],
                ])->map(fn (array $category) => Category::create([
                    'name' => $category['name'],
                    'type' => $category['type'],
                    'user_id' => $user->id,
                ]));

                $expenseCats = collect([
                    ['name' => 'Alimentação', 'type' => 'Despesa'],
                    ['name' => 'Transporte', 'type' => 'Despesa'],
                    ['name' => 'Moradia', 'type' => 'Despesa'],
                    ['name' => 'Saúde', 'type' => 'Despesa'],
                    ['name' => 'Educação', 'type' => 'Despesa'],
                    ['name' => 'Lazer', 'type' => 'Despesa'],
                    ['name' => 'Assinaturas', 'type' => 'Despesa'],
                    ['name' => 'Supermercado', 'type' => 'Despesa'],
                ])->map(fn (array $category) => Category::create([
                    'name' => $category['name'],
                    'type' => $category['type'],
                    'user_id' => $user->id,
                ]));

                collect([
                    ['name' => 'Transferência', 'type' => 'Ambos'],
                    ['name' => 'Ajuste', 'type' => 'Ambos'],
                ])->each(fn (array $category) => Category::create([
                    'name' => $category['name'],
                    'type' => $category['type'],
                    'user_id' => $user->id,
                ]));

//                Transaction::factory()
//                    ->count(100)
//                    ->forUser($user)
//                    ->make()
//                    ->each(function (Transaction $t) use ($user, $cards, $incomeCats, $expenseCats) {
//                        if ($t->type === 'Receita') {
//                            $t->category_id = $incomeCats->random()->id;
//                            $t->card_id = null;
//                        } else {
//                            $t->category_id = $expenseCats->random()->id;
//                            $t->card_id = fake()->boolean(80) ? $cards->random()->id : null;
//                        }
//                        $t->save();
//                    });
            });
    }
}
