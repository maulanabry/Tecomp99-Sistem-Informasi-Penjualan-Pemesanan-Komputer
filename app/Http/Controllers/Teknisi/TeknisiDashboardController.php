<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TeknisiDashboardController extends Controller
{
    public function index(): View
    {
        $teknisi = auth('teknisi')->user();

        return view('teknisi.dashboard', [
            'teknisi' => $teknisi,
            // Future data points will be added here
            'todayTasks' => 0,
            'completedTasks' => 0,
            'pendingTasks' => 0,
            'averageRating' => 0
        ]);
    }
}
