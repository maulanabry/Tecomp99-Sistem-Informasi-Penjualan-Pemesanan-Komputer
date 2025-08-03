<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Menampilkan halaman notifikasi untuk pemilik
     */
    public function index()
    {
        /** @var \App\Models\User $owner */
        $owner = auth('pemilik')->user();

        if (!$owner) {
            return redirect()->route('login');
        }

        return view('owner.notifications.index');
    }
}
