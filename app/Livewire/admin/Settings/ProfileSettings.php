<?php

namespace App\Livewire\Admin\Settings;

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
        $admin = auth('admin')->user();
        $this->name = $admin->name;
        $this->email = $admin->email;
    }

    public function updateProfile()
    {
        $admin = auth('admin')->user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
        ]);

        Admin::where('id', $admin->id)->update([
            'name' => $validated['name'],
            'email' => $validated['email']
        ]);

        session()->flash('success', 'Profil berhasil diperbarui');
        return redirect()->route('settings.index');
    }

    public function updatePassword()
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $admin = auth('admin')->user();
        Admin::where('id', $admin->id)->update([
            'password' => Hash::make($validated['password'])
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('success', 'Password berhasil diperbarui');
        return redirect()->route('settings.index');
    }

    public function render()
    {
        return view('livewire.admin.settings.profile-settings');
    }
}
