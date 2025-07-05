<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        // Implement index logic
        return view('teknisi.customer.index');
    }

    public function show(Request $request, Customer $customer)
    {
        $previousUrl = url()->previous();
        return view('teknisi.customer.show', compact('customer', 'previousUrl'));
    }
}
