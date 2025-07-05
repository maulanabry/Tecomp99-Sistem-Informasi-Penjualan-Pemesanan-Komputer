<?php

namespace App\Livewire\Teknisi\Settings;

use App\Models\Admin;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileSettings extends Component
{
    public $name;
    public $email;
    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $teknisi = auth('teknisi')->user();
        $this->name = $teknisi->name;
        $this->email = $teknisi->email;
    }

    public function updateProfile()
    {
        $teknisi = auth('teknisi')->user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $teknisi->id],
        ]);

        Admin::where('id', $teknisi->id)->update([
            'name' => $validated['name'],
            'email' => $validated['email']
        ]);

        session()->flash('success', 'Profil berhasil diperbarui');
        return redirect()->route('teknisi.settings.index');
    }

    public function updatePassword()
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password:teknisi'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $teknisi = auth('teknisi')->user();
        Admin::where('id', $teknisi->id)->update([
            'password' => Hash::make($validated['password'])
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('success', 'Password berhasil diperbarui');
        return redirect()->route('teknisi.settings.index');
    }

    public function render()
    {
        return view('livewire.teknisi.settings.profile-settings');
    }
}
