<?php

namespace App\Livewire\Modals;

use App\Models\Category as CategoryModel;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class Category extends Component
{
    public ?string $name = null;
    public string $type = '';

    public CategoryModel|null $category = null;

    /**
     * Validation rules for category form.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:50',
            'type' => 'required|in:Receita,Despesa,Ambos',
        ];
    }

    /**
     * Validate and persist the category.
     *
     * @return void
     *
     * @throws ValidationException
     * @throws Throwable
     */
    public function save(): void
    {
        $validated = $this->validate();
        $validated['user_id'] = auth()->id();

        try {
            CategoryModel::updateOrCreate(
                ['id' => $this->category?->id],
                $validated
            );

            $this->dispatch('notify',
                type: 'success',
                message: 'Categoria salva com sucesso!.',
                duration: 4000
            );
        } catch (Throwable $exception) {
            Log::error('Ocorreu erro ao registrar categoria: ' . $exception->getMessage());
            $this->dispatch('notify',
                type: 'error',
                message: 'Ocorreu um erro ao salvar a categoria.',
                duration: 4000
            );
        }

        $this->resetForm();
        Flux::modals()->close();
        $this->dispatch('load-categories');
    }

    /**
     * Reset the form state when the modal is closed.
     *
     * @return void
     */
    public function resetForm(): void
    {
        $this->reset();
        $this->resetValidation();
    }
}

