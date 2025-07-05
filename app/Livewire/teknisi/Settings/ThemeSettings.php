<?php

namespace App\Livewire\Teknisi\Settings;

use App\Models\Admin;
use Livewire\Component;

class ThemeSettings extends Component
{
    public $theme;

    public function mount()
    {
        $teknisi = auth('teknisi')->user();
        $this->theme = $teknisi->theme ?? 'system';
    }

    public function updateTheme()
    {
        $validated = $this->validate([
            'theme' => ['required', 'string', 'in:light,dark,system'],
        ]);

        $teknisi = auth('teknisi')->user();
        Admin::where('id', $teknisi->id)->update([
            'theme' => $validated['theme']
        ]);

        $this->dispatch('theme-updated', $validated['theme']);

        session()->flash('success', 'Preferensi tema berhasil diperbarui');
        return redirect()->route('teknisi.settings.index');
    }

    public function render()
    {
        return view('livewire.teknisi.settings.theme-settings');
    }
}
