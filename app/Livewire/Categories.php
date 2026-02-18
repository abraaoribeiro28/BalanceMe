<?php

namespace App\Livewire;

use App\Models\Category;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class Categories extends Component
{
    public Collection $categories;
    public ?int $categoryToDeleteId = null;
    public string $categoryToDeleteName = '';

    #[On('load-categories')]
    public function loadCategories(): void
    {
        $this->categories = Category::where('user_id', auth()->id())->get();
    }

    public function mount(): void
    {
        $this->loadCategories();
    }

    public function editCategory(int $categoryId): void
    {
        $category = Category::query()->find($categoryId);

        if ($category === null) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Categoria não encontrada.',
                duration: 4000
            );

            return;
        }

        if (Gate::denies('update', $category)) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Você não tem permissão para editar esta categoria.',
                duration: 4000
            );

            return;
        }

        $this->dispatch('edit-category', categoryId: $category->id);
    }

    public function confirmDeleteCategory(int $categoryId): void
    {
        $category = Category::query()->find($categoryId);

        if ($category === null) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Categoria não encontrada.',
                duration: 4000
            );

            return;
        }

        if (Gate::denies('delete', $category)) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Você não tem permissão para remover esta categoria.',
                duration: 4000
            );

            return;
        }

        $this->categoryToDeleteId = $category->id;
        $this->categoryToDeleteName = $category->name;

        Flux::modal('confirm-delete-category')->show();
    }

    public function closeDeleteCategoryModal(): void
    {
        $this->resetDeleteCategoryState();
        Flux::modal('confirm-delete-category')->close();
    }

    public function resetDeleteCategoryState(): void
    {
        $this->categoryToDeleteId = null;
        $this->categoryToDeleteName = '';
    }

    public function deleteCategoryConfirmed(): void
    {
        if ($this->categoryToDeleteId === null) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Nenhuma categoria selecionada para exclusão.',
                duration: 4000
            );

            return;
        }

        if ($this->removeCategoryById($this->categoryToDeleteId)) {
            $this->closeDeleteCategoryModal();
        }
    }

    public function deleteCategory(int $categoryId): void
    {
        $this->removeCategoryById($categoryId);
    }

    private function removeCategoryById(int $categoryId): bool
    {
        $category = Category::query()->find($categoryId);

        if ($category === null) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Categoria não encontrada.',
                duration: 4000
            );

            return false;
        }

        if (Gate::denies('delete', $category)) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Você não tem permissão para remover esta categoria.',
                duration: 4000
            );

            return false;
        }

        try {
            $category->delete();

            $this->dispatch('notify',
                type: 'success',
                message: 'Categoria removida com sucesso!',
                duration: 4000
            );

            $this->loadCategories();
            $this->dispatch('transaction-saved');
        } catch (Throwable $exception) {
            Log::error('Ocorreu erro ao remover categoria: ' . $exception->getMessage());
            $this->dispatch('notify',
                type: 'error',
                message: 'Ocorreu um erro ao remover a categoria.',
                duration: 4000
            );

            return false;
        }

        return true;
    }
}