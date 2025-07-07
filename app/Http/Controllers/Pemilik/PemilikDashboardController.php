<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PemilikDashboardController extends Controller
{
    public function index(): View
    {
        return view('owner.dashboard');
    }
}
