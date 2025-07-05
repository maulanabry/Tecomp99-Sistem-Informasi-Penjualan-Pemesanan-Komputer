<?php

namespace App\Livewire\Teknisi\Settings;

use App\Models\Setting;
use Livewire\Component;

class SystemSettings extends Component
{
    public $timezone;
    public $date_format;

    protected $timezones = [
        'Asia/Jakarta' => '(UTC+07:00) Jakarta',
        'Asia/Makassar' => '(UTC+08:00) Makassar',
        'Asia/Jayapura' => '(UTC+09:00) Jayapura',
        'Asia/Pontianak' => '(UTC+07:00) Pontianak',
        'Asia/Balikpapan' => '(UTC+08:00) Balikpapan'
    ];

    protected $dateFormats = [
        'd/m/Y' => '31/12/2023',
        'Y-m-d' => '2023-12-31',
        'd-m-Y' => '31-12-2023',
        'd M Y' => '31 Dec 2023',
        'd F Y' => '31 December 2023'
    ];

    public function mount()
    {
        $this->timezone = Setting::get('app_timezone', 'Asia/Jakarta');
        $this->date_format = Setting::get('app_date_format', 'd/m/Y');
    }

    public function updateSystemSettings()
    {
        $validated = $this->validate([
            'timezone' => ['required', 'string', 'in:' . implode(',', array_keys($this->timezones))],
            'date_format' => ['required', 'string', 'in:' . implode(',', array_keys($this->dateFormats))],
        ]);

        Setting::set('app_timezone', $validated['timezone'], 'system');
        Setting::set('app_date_format', $validated['date_format'], 'system');

        session()->flash('success', 'Pengaturan sistem berhasil diperbarui');
        return redirect()->route('teknisi.settings.index');
    }

    public function getTimezonesProperty()
    {
        return $this->timezones;
    }

    public function getDateFormatsProperty()
    {
        return $this->dateFormats;
    }

    public function render()
    {
        return view('livewire.teknisi.settings.system-settings');
    }
}
