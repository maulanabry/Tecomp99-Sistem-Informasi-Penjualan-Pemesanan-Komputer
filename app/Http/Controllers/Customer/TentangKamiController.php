<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FeedbackTestimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TentangKamiController extends Controller
{
    /**
     * Tampilkan halaman Tentang Kami
     */
    public function index()
    {
        // Ambil testimonial yang sudah disetujui untuk ditampilkan
        $testimonials = FeedbackTestimonial::approved()
            ->latest()
            ->limit(6)
            ->get();

        return view('customer.tentang-kami', compact('testimonials'));
    }

    /**
     * Simpan testimonial/feedback dari form
     */
    public function storeTestimonial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|max:1000',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'rating.required' => 'Rating wajib dipilih.',
            'rating.integer' => 'Rating harus berupa angka.',
            'rating.min' => 'Rating minimal 1.',
            'rating.max' => 'Rating maksimal 5.',
            'message.required' => 'Pesan testimonial wajib diisi.',
            'message.max' => 'Pesan maksimal 1000 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan dalam pengisian form. Silakan periksa kembali.');
        }

        try {
            FeedbackTestimonial::create([
                'name' => $request->name,
                'email' => $request->email,
                'rating' => $request->rating,
                'message' => $request->message,
                'status' => 'pending' // Default status menunggu persetujuan
            ]);

            return redirect()->back()->with('success', 'Terima kasih! Testimonial Anda telah berhasil dikirim dan akan ditinjau oleh tim kami.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi nanti.');
        }
    }
}
