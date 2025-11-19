<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileEditModal extends Component
{
    public $showModal = false;
    public $name = '';
    public $email = '';
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    public $updateMode = 'info';

    protected $listeners = ['openProfileModal' => 'handleOpenModal'];

    public function handleOpenModal($data = null)
    {
        $mode = 'info';
        if (is_array($data)) {
            $mode = $data['mode'] ?? 'info';
        } elseif (is_string($data)) {
            $mode = $data;
        }
        $this->openModal($mode);
    }

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function openModal($mode = 'info')
    {
        $this->updateMode = $mode;
        $this->showModal = true;
        $this->resetValidation();
        $this->resetErrorBag();

        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function updateProfile()
    {
        // Validation stricte avant soumission
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être une adresse valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
        ]);

        Auth::user()->update($validated);

        session()->flash('message', 'Profil mis à jour avec succès !');
        $this->closeModal();
        $this->dispatch('profile-updated');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(12)->numbers()->symbols()],
            'password_confirmation' => 'required',
        ], [
            'current_password.required' => 'Le mot de passe actuel est requis.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.required' => 'Le nouveau mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 12 caractères.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le mot de passe doit contenir au moins un symbole.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password_confirmation.required' => 'La confirmation du mot de passe est requise.',
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->showModal = false;
        session()->flash('message', 'Mot de passe mis à jour avec succès !');
    }

    public function render()
    {
        return view('livewire.profile-edit-modal');
    }
}
