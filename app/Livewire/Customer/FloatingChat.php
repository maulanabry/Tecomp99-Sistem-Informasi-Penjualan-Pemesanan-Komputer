<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Admin;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class FloatingChat extends Component
{
    public $showModal = false;
    public $selectedAdminId = null;
    public $currentChat = null;
    public $messages = [];
    public $newMessage = '';
    public $admins = [];
    public $showAdminSelection = true;
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadAdmins();
        $this->loadUnreadCount();
    }

    public function loadAdmins()
    {
        $this->admins = Admin::select('id', 'name', 'last_seen_at', 'role')
            ->where('role', 'admin') // Hanya tampilkan admin dengan role 'admin'
            ->orderBy('name')
            ->get()
            ->map(function ($admin) {
                return [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'is_online' => $admin->isOnline(),
                ];
            });
    }

    public function loadUnreadCount()
    {
        if (!Auth::guard('customer')->check()) {
            $this->unreadCount = 0;
            return;
        }

        $customer = Auth::guard('customer')->user();
        $this->unreadCount = ChatMessage::whereHas('chat', function ($query) use ($customer) {
            $query->where('customer_id', $customer->customer_id);
        })
            ->where('sender_type', 'admin')
            ->where('is_read_by_customer', false)
            ->count();
    }

    public function toggleModal()
    {
        $this->showModal = !$this->showModal;

        if ($this->showModal) {
            // Load existing chat if available
            $customer = Auth::guard('customer')->user();
            $activeChat = $customer->chats()
                ->where('is_active', true)
                ->with(['admin', 'messages'])
                ->orderBy('last_message_at', 'desc')
                ->first();

            if ($activeChat) {
                $this->selectAdmin($activeChat->admin_id);
            }
        }
    }

    public function selectAdmin($adminId)
    {
        $this->selectedAdminId = $adminId;
        $this->showAdminSelection = false;

        // Cari atau buat chat dengan admin
        $customer = Auth::guard('customer')->user();
        $this->currentChat = Chat::where('customer_id', $customer->customer_id)
            ->where('admin_id', $adminId)
            ->where('is_active', true)
            ->first();

        if (!$this->currentChat) {
            // Buat chat baru
            $this->currentChat = Chat::create([
                'customer_id' => $customer->customer_id,
                'admin_id' => $adminId,
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
                    'is_from_customer' => $message->isFromCustomer(),
                ];
            })
            ->toArray();
    }

    public function sendMessage()
    {
        if (empty(trim($this->newMessage)) || !$this->currentChat) {
            return;
        }

        $customer = Auth::guard('customer')->user();

        $message = ChatMessage::create([
            'chat_id' => $this->currentChat->id,
            'sender_type' => 'customer',
            'sender_id' => $customer->customer_id,
            'message' => trim($this->newMessage),
            'message_type' => 'text',
            'is_read_by_admin' => false,
            'is_read_by_customer' => true,
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
            ->where('sender_type', 'admin')
            ->where('is_read_by_customer', false)
            ->update([
                'is_read_by_customer' => true,
                'read_at' => now()
            ]);

        $this->loadUnreadCount();
    }

    public function backToAdminSelection()
    {
        $this->showAdminSelection = true;
        $this->selectedAdminId = null;
        $this->currentChat = null;
        $this->messages = [];
        $this->newMessage = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showAdminSelection = true;
        $this->selectedAdminId = null;
        $this->currentChat = null;
        $this->messages = [];
        $this->newMessage = '';
    }

    // Polling untuk real-time updates
    public function refreshChat()
    {
        if ($this->currentChat) {
            $this->loadMessages();
            $this->markMessagesAsRead();
        }
        $this->loadUnreadCount();
    }

    public function render()
    {
        return view('livewire.customer.floating-chat');
    }
}
