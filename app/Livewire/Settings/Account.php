<?php

namespace App\Livewire\Settings;

use App\Models\User;
use App\Support\Toast;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use App\Livewire\Concerns\HasToast;

class Account extends Component
{
    use HasToast;

    #[Validate('required|string|min:3|max:12')]
    public string $name = '';

    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string|current_password')]
    public string $current_password = '';

    #[Validate('required|string|confirmed|min:8')]
    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Prefill account form fields for the current user.
     */
    public function mount(#[CurrentUser] User $user): void
    {
        $this->name = $user->name;
        $this->email = $user->email;
    }

    /**
     * Persist profile changes for the current user.
     */
    public function saveChanges(#[CurrentUser] User $user): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:12'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
        ]);

        $user->fill($validated);

        // If the email changed we need to make it unverified, for security reasons
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch(
            event: 'notify',
            type: 'success',
            message: 'Sua conta foi atualizada.'
        );
    }

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(#[CurrentUser] $user): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch(
            event: 'notify',
            type: 'success',
            message: 'Sua senha foi atualizada.'
        );
    }

    /**
     * Render the account settings page.
     */
    public function render(): View
    {
        return view('livewire.settings.account');
    }
}
