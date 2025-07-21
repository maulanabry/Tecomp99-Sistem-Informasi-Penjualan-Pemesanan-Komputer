<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Customer;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

/**
 * Controller untuk menangani chat dari sisi Admin
 */
class ChatController extends Controller
{
    /**
     * Menampilkan halaman chat admin
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $chats = $admin->chats()
            ->where('is_active', true)
            ->with(['customer', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $chatsWithUnread = $admin->chats()
            ->whereHas('messages', function ($query) {
                $query->where('sender_type', 'customer')
                    ->where('is_read', false);
            })
            ->with(['customer', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('admin.chat', compact('chats', 'chatsWithUnread'));
    }

    /**
     * Mendapatkan daftar customer yang pernah chat
     */
    public function getCustomerChats(): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $chats = $admin->chats()
            ->where('is_active', true)
            ->with(['customer', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $customerChats = $chats->map(function ($chat) {
            $lastMessage = $chat->messages()->latest()->first();

            return [
                'chat_id' => $chat->id,
                'customer' => [
                    'id' => $chat->customer->customer_id,
                    'name' => $chat->customer->name,
                    'email' => $chat->customer->email,
                    'contact' => $chat->customer->contact,
                    'photo' => $chat->customer->photo,
                ],
                'last_message' => $lastMessage ? [
                    'message' => $lastMessage->message,
                    'sender_type' => $lastMessage->sender_type,
                    'created_at' => $lastMessage->created_at->diffForHumans(),
                ] : null,
                'unread_count' => $chat->unread_messages_for_admin,
                'last_message_at' => $chat->last_message_at?->diffForHumans(),
            ];
        });

        return response()->json($customerChats);
    }

    /**
     * Memulai atau mendapatkan chat dengan customer tertentu
     */
    public function startChatWithCustomer(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id'
        ]);

        $admin = Auth::guard('admin')->user();
        $customerId = $request->customer_id;

        // Cari atau buat chat
        $chat = Chat::findOrCreateChat($customerId, $admin->id);

        // Load relasi yang diperlukan
        $chat->load(['customer', 'messages' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        // Tandai pesan dari customer sebagai sudah dibaca
        $chat->markAsReadByAdmin();

        return response()->json([
            'success' => true,
            'chat' => [
                'id' => $chat->id,
                'customer' => [
                    'id' => $chat->customer->customer_id,
                    'name' => $chat->customer->name,
                    'email' => $chat->customer->email,
                    'contact' => $chat->customer->contact,
                    'photo' => $chat->customer->photo,
                ],
                'messages' => $chat->messages->map(function ($message) {
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
                }),
                'unread_count' => $chat->unread_messages_for_admin,
            ]
        ]);
    }

    /**
     * Mengirim pesan ke customer
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'required_without:file|string|max:1000',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:5120', // 5MB max
        ]);

        $admin = Auth::guard('admin')->user();
        $chat = Chat::findOrFail($request->chat_id);

        // Pastikan admin adalah pemilik chat ini
        if ($chat->admin_id !== $admin->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $messageData = [
            'chat_id' => $chat->id,
            'sender_type' => 'admin',
            'sender_id' => $admin->id,
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
                'sender_name' => $admin->name,
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
     * Mendapatkan riwayat chat dengan customer tertentu
     */
    public function getChatHistory(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id'
        ]);

        $admin = Auth::guard('admin')->user();
        $chat = $admin->chats()
            ->where('customer_id', $request->customer_id)
            ->where('is_active', true)
            ->first();

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat tidak ditemukan'
            ], 404);
        }

        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        // Tandai pesan dari customer sebagai sudah dibaca
        $chat->markAsReadByAdmin();

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

        $admin = Auth::guard('admin')->user();
        $chat = Chat::findOrFail($request->chat_id);

        // Pastikan admin adalah pemilik chat ini
        if ($chat->admin_id !== $admin->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $chat->markAsReadByAdmin();

        return response()->json([
            'success' => true,
            'message' => 'Pesan ditandai sebagai sudah dibaca'
        ]);
    }

    /**
     * Mendapatkan jumlah pesan yang belum dibaca dari semua customer
     */
    public function getUnreadCount(): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $unreadCount = $admin->unread_messages_count;

        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mencari customer untuk memulai chat baru
     */
    public function searchCustomers(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $query = $request->query;

        $customers = Customer::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('contact', 'like', "%{$query}%")
                ->orWhere('customer_id', 'like', "%{$query}%");
        })
            ->limit(10)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->customer_id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'contact' => $customer->contact,
                    'photo' => $customer->photo,
                ];
            });

        return response()->json($customers);
    }

    /**
     * Menghapus chat dengan customer tertentu
     */
    public function deleteChat(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id'
        ]);

        $admin = Auth::guard('admin')->user();
        $chat = Chat::findOrFail($request->chat_id);

        // Pastikan admin adalah pemilik chat ini
        if ($chat->admin_id !== $admin->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            // Hapus semua pesan dalam chat ini
            $chat->messages()->delete();

            // Hapus chat
            $chat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Chat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus chat'
            ], 500);
        }
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
}
