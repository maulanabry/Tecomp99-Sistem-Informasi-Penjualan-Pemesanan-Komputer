<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for teknisi.
     */
    public function index()
    {
        return view('teknisi.notifications.index');
    }
}
