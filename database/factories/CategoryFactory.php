<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
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
        $type = fake()->randomElement(['Receita', 'Despesa', 'Ambos']);

        $nameByType = [
            'Receita' => ['Salário', 'Freelance', 'Rendimentos', 'Investimentos', 'Reembolso', 'Bônus'],
            'Despesa' => ['Alimentação', 'Transporte', 'Moradia', 'Saúde', 'Educação', 'Lazer', 'Assinaturas', 'Supermercado'],
            'Ambos'   => ['Transferência', 'Ajuste', 'Outros'],
        ];

        return [
            'name'    => fake()->randomElement($nameByType[$type]),
            'type'    => $type,
            'user_id' => User::factory(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn () => ['user_id' => $user->id]);
    }

    public function income(): static
    {
        return $this->state(fn () => [
            'type' => 'Receita',
            'name' => fake()->randomElement(['Salário', 'Freelance', 'Rendimentos', 'Investimentos', 'Reembolso', 'Bônus']),
        ]);
    }

    public function expense(): static
    {
        return $this->state(fn () => [
            'type' => 'Despesa',
            'name' => fake()->randomElement(['Alimentação', 'Transporte', 'Moradia', 'Saúde', 'Educação', 'Lazer', 'Assinaturas', 'Supermercado']),
        ]);
    }

    public function both(): static
    {
        return $this->state(fn () => [
            'type' => 'Ambos',
            'name' => fake()->randomElement(['Transferência', 'Ajuste', 'Outros']),
        ]);
    }
}
