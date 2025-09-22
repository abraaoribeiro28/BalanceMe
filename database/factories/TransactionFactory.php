<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['Receita', 'Despesa']);

        $name = $type === 'Receita'
            ? fake()->randomElement(['Salário', 'Freelance', 'Dividendos', 'Rendimento', 'Bônus'])
            : fake()->randomElement(['Aluguel', 'Supermercado', 'Restaurante', 'Transporte', 'Saúde', 'Educação', 'Lazer', 'Assinatura']);

        $amount = fake()->randomFloat(2, 10, 1000);

        return [
            'name' => $name,
            'amount' => $amount,
            'type' => $type,
            'date' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'description' => fake()->boolean(35) ? fake()->sentence() : null,
            'card_id' => null,
            'category_id' => null,
            'user_id' => User::factory(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn () => ['user_id' => $user->id]);
    }

    public function income(): static
    {
        return $this->state(fn () => ['type' => 'Receita']);
    }

    public function expense(): static
    {
        return $this->state(fn () => ['type' => 'Despesa']);
    }
}
