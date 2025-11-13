<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AvatarColorPicker extends Component
{
    public $showPalette = false;
    public $currentColor;
    public $colors = [
        '0cbaba' => 'Turquoise',
        '3498db' => 'Bleu',
        '9b59b6' => 'Violet',
        'e74c3c' => 'Rouge',
        'f39c12' => 'Orange',
        '2ecc71' => 'Vert',
        '34495e' => 'Gris foncé',
        'e91e63' => 'Rose',
        '00bcd4' => 'Cyan',
        'ff9800' => 'Orange vif',
        '795548' => 'Marron',
        '607d8b' => 'Gris bleu',
    ];

    public function mount()
    {
        $this->currentColor = Auth::user()->avatar_color ?? '0cbaba';
    }

    public function togglePalette()
    {
        $this->showPalette = !$this->showPalette;
    }

    public function selectColor($color)
    {
        $this->currentColor = $color;
        Auth::user()->update(['avatar_color' => $color]);
        $this->showPalette = false;

        $this->dispatch('avatar-updated');
        session()->flash('message', 'Couleur de l\'avatar mise à jour !');
    }

    public function render()
    {
        return view('livewire.avatar-color-picker');
    }
}
