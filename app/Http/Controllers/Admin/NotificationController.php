<?php

namespace App\Http\Controllers\Admin;

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
     * Display notifications page
     */
    public function index()
    {
        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();

        if (!$admin) {
            return redirect()->route('login');
        }

        return view('admin.notifications.index');
    }
}
