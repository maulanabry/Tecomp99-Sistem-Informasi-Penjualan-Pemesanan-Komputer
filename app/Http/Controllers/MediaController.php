<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderService;
use App\Models\OrderServiceMedia;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller
{
    /**
     * Serve order service media files
     */
    public function serveOrderServiceMedia(Request $request, $orderId, $filename)
    {
        try {
            // Check if user is authenticated as customer
            if (!Auth::guard('customer')->check()) {
                abort(403, 'Unauthorized access');
            }

            $customer = Auth::guard('customer')->user();

            // Find the order service
            $orderService = OrderService::where('order_service_id', $orderId)->first();

            if (!$orderService) {
                abort(404, 'Order not found');
            }

            // Check if the customer owns this order
            if ($orderService->customer_id !== $customer->customer_id) {
                abort(403, 'Access denied');
            }

            // Find the media file
            $media = OrderServiceMedia::where('order_service_id', $orderId)
                ->where('media_path', 'like', "%{$filename}")
                ->first();

            if (!$media) {
                abort(404, 'Media file not found');
            }

            // Check if file exists in storage
            if (!Storage::disk('local')->exists($media->media_path)) {
                abort(404, 'File not found in storage');
            }

            // Get file content
            $fileContent = Storage::disk('local')->get($media->media_path);

            // Determine MIME type
            $mimeType = $this->getMimeType($media->file_type);

            // Return file response
            return response($fileContent, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $media->media_name . '"')
                ->header('Cache-Control', 'public, max-age=3600');
        } catch (\Exception $e) {
            \Log::error('Error serving media file: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'filename' => $filename,
                'customer_id' => Auth::guard('customer')->id()
            ]);

            abort(500, 'Error serving file');
        }
    }

    /**
     * Serve order service media files for admin/staff
     */
    public function serveOrderServiceMediaAdmin(Request $request, $orderId, $filename)
    {
        try {
            // Check if user is authenticated as admin, teknisi, or pemilik
            if (!Auth::guard('admin')->check() && !Auth::guard('teknisi')->check() && !Auth::guard('pemilik')->check()) {
                abort(403, 'Unauthorized access');
            }

            // Find the order service
            $orderService = OrderService::where('order_service_id', $orderId)->first();

            if (!$orderService) {
                abort(404, 'Order not found');
            }

            // Find the media file
            $media = OrderServiceMedia::where('order_service_id', $orderId)
                ->where('media_path', 'like', "%{$filename}")
                ->first();

            if (!$media) {
                abort(404, 'Media file not found');
            }

            // Check if file exists in storage
            if (!Storage::disk('local')->exists($media->media_path)) {
                abort(404, 'File not found in storage');
            }

            // Get file content
            $fileContent = Storage::disk('local')->get($media->media_path);

            // Determine MIME type
            $mimeType = $this->getMimeType($media->file_type);

            // Return file response
            return response($fileContent, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $media->media_name . '"')
                ->header('Cache-Control', 'public, max-age=3600');
        } catch (\Exception $e) {
            \Log::error('Error serving media file for admin: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'filename' => $filename,
                'user_type' => Auth::guard('admin')->check() ? 'admin' : (Auth::guard('teknisi')->check() ? 'teknisi' : 'pemilik'),
                'user_id' => Auth::guard('admin')->id() ?? Auth::guard('teknisi')->id() ?? Auth::guard('pemilik')->id()
            ]);

            abort(500, 'Error serving file');
        }
    }

    /**
     * Get MIME type based on file extension
     */
    private function getMimeType($extension)
    {
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
            'pdf' => 'application/pdf',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }
}
