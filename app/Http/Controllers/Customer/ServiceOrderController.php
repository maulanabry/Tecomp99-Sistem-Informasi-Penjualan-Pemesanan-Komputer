<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceOrderController extends Controller
{
    /**
     * Display the service order form
     */
    public function index()
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        // Check if customer has address
        $hasAddress = $customer->addresses()->exists();

        return view('customer.pesan-servis', compact('customer', 'hasAddress'));
    }

    /**
     * Store the service order (handled by Livewire component)
     */
    public function store(Request $request)
    {
        // This will be handled by the Livewire component
        return redirect()->back();
    }
}
