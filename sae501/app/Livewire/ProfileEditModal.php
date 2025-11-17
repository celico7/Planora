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
        // Validation stricte AVANT tout traitement
        try {
            $this->validate([
                'current_password' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8'],
                'password_confirmation' => ['required', 'string', 'same:password'],
            ], [
                'current_password.required' => 'Veuillez renseigner votre mot de passe actuel.',
                'password.required' => 'Veuillez renseigner un nouveau mot de passe.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'password_confirmation.required' => 'Veuillez confirmer votre nouveau mot de passe.',
                'password_confirmation.same' => 'La confirmation du mot de passe ne correspond pas.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Forcer l'affichage des erreurs sans fermer le modal
            throw $e;
        }

        // Vérifier que le mot de passe actuel est correct
        if (!Hash::check($this->current_password, Auth::user()->password)) {
            $this->addError('current_password', 'Le mot de passe actuel est incorrect.');
            return;
        }

        // Tout est OK, on met à jour
        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        session()->flash('message', 'Mot de passe mis à jour avec succès !');

        // Réinitialiser les champs
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.profile-edit-modal');
    }
}
