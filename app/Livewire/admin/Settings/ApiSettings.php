<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\File;

class ApiSettings extends Component
{
    public $rajaongkir_api_key;
    public $show_api_key = false;

    public function mount()
    {
        $this->rajaongkir_api_key = config('rajaongkir.api_key') ?? env('RAJAONGKIR_API_KEY');
    }

    public function updateApiSettings()
    {
        $validated = $this->validate([
            'rajaongkir_api_key' => ['required', 'string', 'min:10'],
        ]);

        $this->updateEnvFile('RAJAONGKIR_API_KEY', $validated['rajaongkir_api_key']);

        session()->flash('success', 'API key RajaOngkir berhasil diperbarui');
        return redirect()->route('settings.index');
    }

    public function toggleApiKeyVisibility()
    {
        $this->show_api_key = !$this->show_api_key;
    }

    private function updateEnvFile($key, $value)
    {
        $envFile = base_path('.env');
        $envContent = File::get($envFile);

        // Escape special characters in the value
        $value = '"' . str_replace('"', '\"', $value) . '"';

        // Check if the key exists
        if (preg_match("/^{$key}=.*$/m", $envContent)) {
            // Update existing key
            $envContent = preg_replace("/^{$key}=.*$/m", "{$key}={$value}", $envContent);
        } else {
            // Add new key
            $envContent .= "\n{$key}={$value}";
        }

        File::put($envFile, $envContent);
    }

    public function render()
    {
        return view('livewire.admin.settings.api-settings');
    }
}
