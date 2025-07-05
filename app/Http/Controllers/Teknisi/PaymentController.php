<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        // Implement index logic
        return view('teknisi.payment.index');
    }

    public function show(Request $request, $payment_id)
    {
        $payment = PaymentDetail::findOrFail($payment_id);
        $previousUrl = url()->previous();
        return view('teknisi.payment.show', compact('payment', 'previousUrl'));
    }
}
