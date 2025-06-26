<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Admin;
use Livewire\Component;

class ThemeSettings extends Component
{
    public $theme;

    public function mount()
    {
        $admin = auth('admin')->user();
        $this->theme = $admin->theme;
    }

    public function updateTheme()
    {
        $admin = auth('admin')->user();

        $validated = $this->validate([
            'theme' => ['required', 'in:light,dark,system'],
        ]);

        Admin::where('id', $admin->id)->update([
            'theme' => $validated['theme']
        ]);

        session()->flash('success', 'Preferensi tema berhasil diperbarui');
        return redirect()->route('settings.index');
    }

    public function render()
    {
        return view('livewire.admin.settings.theme-settings');
    }
}
