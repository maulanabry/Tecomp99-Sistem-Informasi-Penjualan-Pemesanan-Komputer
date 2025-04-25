<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TeknisiDashboardController extends Controller
{
    public function index(): View
    {
        return view('teknisi.dashboard');
    }
}
