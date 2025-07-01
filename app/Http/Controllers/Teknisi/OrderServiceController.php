<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderServiceController extends Controller
{
    /**
     * Display a listing of order services assigned to the logged-in teknisi.
     */
    public function index()
    {
        return view('teknisi.order-service.index');
    }

    /**
     * Display the specified order service.
     */
    public function show(OrderService $orderService)
    {
        // Check if the order service has tickets assigned to the current teknisi
        $hasAssignedTicket = $orderService->tickets()
            ->where('admin_id', Auth::id())
            ->exists();

        if (!$hasAssignedTicket) {
            abort(403, 'Unauthorized access to this order service.');
        }

        return view('teknisi.order-service.show', compact('orderService'));
    }
}
