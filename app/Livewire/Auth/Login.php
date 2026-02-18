<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Constants;
use App\Livewire\Concerns\HasToast;
use App\Livewire\Forms\Auth\LoginForm;
use App\Support\Toast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
final class Login extends Component
{
    public LoginForm $form;

    /**
     * Validate credentials and authenticate the user.
     */
    public function login()
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        Toast::success("Você fez login com sucesso!");

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Render the login page.
     */
    public function render()
    {
        /** @var View $view */
        $view = view('livewire.auth.login');

        return $view->layout('components.layouts.portal');
    }
}
