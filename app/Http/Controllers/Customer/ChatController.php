<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Admin;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

/**
 * Controller untuk menangani chat dari sisi Customer
 */
class ChatController extends Controller
{
    /**
     * Menampilkan halaman chat customer
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $chats = $customer->chats()
            ->where('is_active', true)
            ->with(['admin', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
        $admins = Admin::where('role', 'admin')->get();

        return view('customer.chat', compact('chats', 'admins'));
    }

    /**
     * Mendapatkan daftar admin yang tersedia untuk chat
     */
    public function getAvailableAdmins(): JsonResponse
    {
        $admins = Admin::where('role', 'admin')
            ->select('id', 'name', 'last_seen_at')
            ->get()
            ->map(function ($admin) {
                return [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'is_online' => $admin->isOnline(),
                    'last_seen_at' => $admin->last_seen_at?->diffForHumans(),
                ];
            });

        return response()->json($admins);
    }

    /**
     * Memulai atau mendapatkan chat dengan admin tertentu
     */
    public function startChatWithAdmin(Request $request): JsonResponse
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,id'
        ]);

        $customer = Auth::guard('customer')->user();
        $adminId = $request->admin_id;

        // Cari atau buat chat
        $chat = Chat::findOrCreateChat($customer->customer_id, $adminId);

        // Load relasi yang diperlukan
        $chat->load(['admin', 'messages' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        // Tandai pesan dari admin sebagai sudah dibaca
        $chat->markAsReadByCustomer();

        return response()->json([
            'success' => true,
            'chat' => [
                'id' => $chat->id,
                'admin' => [
                    'id' => $chat->admin->id,
                    'name' => $chat->admin->name,
                    'is_online' => $chat->admin->isOnline(),
                ],
                'messages' => $chat->messages->map(fn($m) => $this->formatMessage($m)),
                'unread_count' => $chat->unread_messages_for_customer,
            ]
        ]);
    }

    /**
     * Mengirim pesan ke admin
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'required_without:file|string|max:1000',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:5120', // 5MB max
        ]);

        $customer = Auth::guard('customer')->user();
        $chat = Chat::findOrFail($request->chat_id);

        // Pastikan customer adalah pemilik chat ini
        if ($chat->customer_id !== $customer->customer_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $messageData = [
            'chat_id' => $chat->id,
            'sender_type' => 'customer',
            'sender_id' => $customer->customer_id,
            'message' => $request->message ?? '',
            'message_type' => 'text',
        ];

        // Handle file upload jika ada
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('chat-files', $fileName, 'public');

            $messageData['message_type'] = $this->getFileType($file);
            $messageData['file_path'] = $filePath;
            $messageData['file_name'] = $file->getClientOriginalName();

            if (empty($messageData['message'])) {
                $messageData['message'] = 'Mengirim file: ' . $file->getClientOriginalName();
            }
        }

        // Buat pesan baru
        $message = ChatMessage::create($messageData);

        // Load relasi sender untuk mendapatkan nama
        $message->load(['chat']);

        // Broadcast pesan ke channel chat
        broadcast(new MessageSent($message));

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_type' => $message->sender_type,
                'sender_name' => $customer->name,
                'message' => $message->message,
                'message_type' => $message->message_type,
                'file_url' => $message->file_url,
                'file_name' => $message->file_name,
                'is_image' => $message->isImage(),
                'formatted_time' => $message->formatted_time,
                'formatted_date' => $message->formatted_date,
                'created_at' => $message->created_at->toISOString(),
            ]
        ]);
    }

    /**
     * Mendapatkan riwayat chat dengan admin tertentu
     */
    public function getChatHistory(Request $request): JsonResponse
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,id'
        ]);

        $customer = Auth::guard('customer')->user();
        $chat = $customer->chats()
            ->where('admin_id', $request->admin_id)
            ->where('is_active', true)
            ->first();

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat tidak ditemukan'
            ], 404);
        }

        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        // Tandai pesan dari admin sebagai sudah dibaca
        $chat->markAsReadByCustomer();

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $message->sender_name,
                    'message' => $message->message,
                    'message_type' => $message->message_type,
                    'file_url' => $message->file_url,
                    'file_name' => $message->file_name,
                    'is_image' => $message->isImage(),
                    'formatted_time' => $message->formatted_time,
                    'formatted_date' => $message->formatted_date,
                    'created_at' => $message->created_at->toISOString(),
                ];
            })
        ]);
    }

    /**
     * Menandai pesan sebagai sudah dibaca
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id'
        ]);

        $customer = Auth::guard('customer')->user();
        $chat = Chat::findOrFail($request->chat_id);

        // Pastikan customer adalah pemilik chat ini
        if ($chat->customer_id !== $customer->customer_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $chat->markAsReadByCustomer();

        return response()->json([
            'success' => true,
            'message' => 'Pesan ditandai sebagai sudah dibaca'
        ]);
    }

    /**
     * Mendapatkan jumlah pesan yang belum dibaca
     */
    public function getUnreadCount(): JsonResponse
    {
        $customer = Auth::guard('customer')->user();
        $unreadCount = $customer->unread_messages_count;

        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Menentukan tipe file berdasarkan ekstensi
     */
    private function getFileType($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
            return 'image';
        }

        return 'file';
    }

    /**
     * Memformat pesan untuk dikirim ke klien
     */
    private function formatMessage(ChatMessage $message)
    {
        return [
            'id' => $message->id,
            'sender_type' => $message->sender_type,
            'sender_name' => $message->sender_name,
            'message' => $message->message,
            'message_type' => $message->message_type,
            'file_url' => $message->file_url,
            'file_name' => $message->file_name,
            'is_image' => $message->isImage(),
            'formatted_time' => $message->formatted_time,
            'formatted_date' => $message->formatted_date,
            'created_at' => $message->created_at->toISOString(),
        ];
    }
}
