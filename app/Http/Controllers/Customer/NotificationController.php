<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display a listing of customer notifications.
     */
    public function index(): View
    {
        return view('customer.notifications.index');
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, string $notificationId)
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();

        $notification = $customer->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->markAsRead();

            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }
        }

        return back();
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();

        $customer->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca');
    }

    /**
     * Delete a notification.
     */
    public function destroy(Request $request, string $notificationId)
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();

        $notification = $customer->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->delete();

            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }
        }

        return back()->with('success', 'Notifikasi berhasil dihapus');
    }
}
