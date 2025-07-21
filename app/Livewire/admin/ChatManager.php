<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Chat;
use App\Models\Customer;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class ChatManager extends Component
{
    public $selectedCustomerId = null;
    public $currentChat = null;
    public $messages = [];
    public $newMessage = '';
    public $customers = [];
    public $searchQuery = '';
    public $showCustomerList = true;

    public function mount()
    {
        $this->loadCustomerChats();
    }

    public function loadCustomerChats()
    {
        $admin = Auth::guard('admin')->user();

        // Load chats yang sudah ada dengan admin ini
        $chats = Chat::where('admin_id', $admin->id)
            ->where('is_active', true)
            ->with(['customer'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $this->customers = $chats->map(function ($chat) {
            $unreadCount = ChatMessage::where('chat_id', $chat->id)
                ->where('sender_type', 'customer')
                ->where('is_read_by_admin', false)
                ->count();

            // Get last message manually
            $lastMessage = ChatMessage::where('chat_id', $chat->id)
                ->orderBy('created_at', 'desc')
                ->first();

            return [
                'id' => $chat->customer->customer_id,
                'name' => $chat->customer->name,
                'contact' => $chat->customer->contact,
                'chat_id' => $chat->id,
                'unread_count' => $unreadCount,
                'last_message' => $lastMessage ? [
                    'message' => $lastMessage->message,
                    'sender_type' => $lastMessage->sender_type,
                    'formatted_time' => $lastMessage->formatted_time,
                ] : null,
                'last_message_at' => $chat->last_message_at ? $chat->last_message_at->diffForHumans() : null,
            ];
        })->toArray();
    }

    public function searchCustomers()
    {
        if (strlen($this->searchQuery) < 2) {
            $this->loadCustomerChats();
            return;
        }

        // Search customers yang belum punya chat dengan admin ini
        $admin = Auth::guard('admin')->user();
        $existingCustomerIds = Chat::where('admin_id', $admin->id)
            ->pluck('customer_id')
            ->toArray();

        $searchResults = Customer::where(function ($query) {
            $query->where('name', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('contact', 'like', '%' . $this->searchQuery . '%');
        })
            ->whereNotIn('customer_id', $existingCustomerIds)
            ->limit(10)
            ->get();

        $this->customers = $searchResults->map(function ($customer) {
            return [
                'id' => $customer->customer_id,
                'name' => $customer->name,
                'contact' => $customer->contact,
                'chat_id' => null,
                'unread_count' => 0,
                'last_message' => null,
                'last_message_at' => null,
                'is_new' => true,
            ];
        })->toArray();
    }

    public function selectCustomer($customerId, $chatId = null)
    {
        $this->selectedCustomerId = $customerId;
        $this->showCustomerList = false;

        $admin = Auth::guard('admin')->user();

        if ($chatId) {
            // Load existing chat
            $this->currentChat = Chat::find($chatId);
        } else {
            // Create new chat
            $this->currentChat = Chat::create([
                'customer_id' => $customerId,
                'admin_id' => $admin->id,
                'is_active' => true,
                'started_at' => now(),
                'last_message_at' => now(),
            ]);
        }

        $this->loadMessages();
        $this->markMessagesAsRead();
    }

    public function loadMessages()
    {
        if (!$this->currentChat) return;

        $this->messages = $this->currentChat->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $message->sender_name,
                    'created_at' => $message->created_at,
                    'formatted_time' => $message->formatted_time,
                    'formatted_date' => $message->formatted_date,
                    'is_from_admin' => $message->isFromAdmin(),
                ];
            })
            ->toArray();
    }

    public function sendMessage()
    {
        if (empty(trim($this->newMessage)) || !$this->currentChat) {
            return;
        }

        $admin = Auth::guard('admin')->user();

        $message = ChatMessage::create([
            'chat_id' => $this->currentChat->id,
            'sender_type' => 'admin',
            'sender_id' => $admin->id,
            'message' => trim($this->newMessage),
            'message_type' => 'text',
            'is_read_by_admin' => true,
            'is_read_by_customer' => false,
        ]);

        // Broadcast event
        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Exception $e) {
            // Jika broadcasting gagal, lanjutkan tanpa error
        }

        $this->newMessage = '';
        $this->loadMessages();

        // Scroll ke bawah
        $this->dispatch('scrollToBottom');
    }

    public function markMessagesAsRead()
    {
        if (!$this->currentChat) return;

        ChatMessage::where('chat_id', $this->currentChat->id)
            ->where('sender_type', 'customer')
            ->where('is_read_by_admin', false)
            ->update([
                'is_read_by_admin' => true,
                'read_by_admin_at' => now()
            ]);
    }

    public function backToCustomerList()
    {
        $this->showCustomerList = true;
        $this->selectedCustomerId = null;
        $this->currentChat = null;
        $this->messages = [];
        $this->newMessage = '';
        $this->searchQuery = '';
        $this->loadCustomerChats();
    }

    public function updatedSearchQuery()
    {
        $this->searchCustomers();
    }

    /**
     * Menghapus chat dengan customer tertentu
     */
    public function deleteChat($chatId)
    {
        try {
            $admin = Auth::guard('admin')->user();
            $chat = Chat::where('id', $chatId)
                ->where('admin_id', $admin->id)
                ->first();

            if (!$chat) {
                session()->flash('error', 'Chat tidak ditemukan atau Anda tidak memiliki akses.');
                return;
            }

            // Hapus semua pesan dalam chat ini
            $chat->messages()->delete();

            // Hapus chat
            $chat->delete();

            // Jika chat yang dihapus adalah chat yang sedang aktif, kembali ke daftar customer
            if ($this->currentChat && $this->currentChat->id == $chatId) {
                $this->backToCustomerList();
            }

            // Refresh daftar customer
            $this->loadCustomerChats();

            session()->flash('success', 'Chat berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus chat.');
        }
    }

    /**
     * Konfirmasi sebelum menghapus chat
     */
    public function confirmDeleteChat($chatId)
    {
        $this->dispatch('confirm-delete-chat', ['chatId' => $chatId]);
    }

    // Polling untuk real-time updates
    public function refreshChat()
    {
        if ($this->currentChat) {
            $this->loadMessages();
            $this->markMessagesAsRead();
        }

        if ($this->showCustomerList) {
            $this->loadCustomerChats();
        }
    }

    public function render()
    {
        return view('livewire.admin.chat-manager');
    }
}
