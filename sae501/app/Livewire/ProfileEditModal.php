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
    }

    public function updateProfile()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ]);

        Auth::user()->update($validated);

        session()->flash('message', 'Profil mis à jour avec succès !');
        $this->closeModal();
        $this->dispatch('profile-updated');
    }

    public function updatePassword()
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        session()->flash('message', 'Mot de passe mis à jour avec succès !');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.profile-edit-modal');
    }
}
