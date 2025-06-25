<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings');
    }

    public function general()
    {
        return view('admin.settings.general');
    }

    public function system()
    {
        return view('admin.settings.system');
    }

    public function notification()
    {
        return view('admin.settings.notification');
    }

    public function updateGeneral(Request $request)
    {
        // TODO: Implement general settings update
        return redirect()->route('settings.general')->with('success', 'Pengaturan umum berhasil diperbarui');
    }

    public function updateSystem(Request $request)
    {
        // TODO: Implement system settings update
        return redirect()->route('settings.system')->with('success', 'Pengaturan sistem berhasil diperbarui');
    }

    public function updateNotification(Request $request)
    {
        // TODO: Implement notification settings update
        return redirect()->route('settings.notification')->with('success', 'Pengaturan notifikasi berhasil diperbarui');
    }
}
