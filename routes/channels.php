<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Customer;
use App\Models\Admin;

// Channel untuk user authentication (existing)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel untuk customer authentication
Broadcast::channel('App.Models.Customer.{id}', function ($customer, $id) {
    return $customer instanceof Customer && $customer->customer_id === $id;
});

// Channel untuk admin authentication
Broadcast::channel('App.Models.Admin.{id}', function ($admin, $id) {
    return $admin instanceof Admin && (int) $admin->id === (int) $id;
});

// Private channel untuk chat antara customer dan admin
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    // Cek apakah user adalah customer atau admin yang terlibat dalam chat ini
    $chat = \App\Models\Chat::find($chatId);

    if (!$chat) {
        return false;
    }

    // Jika user adalah customer, cek apakah customer_id cocok
    if ($user instanceof Customer) {
        return $user->customer_id === $chat->customer_id;
    }

    // Jika user adalah admin, cek apakah admin_id cocok
    if ($user instanceof Admin) {
        return (int) $user->id === (int) $chat->admin_id;
    }

    return false;
});

// Channel untuk notifikasi customer
Broadcast::channel('customer.{customerId}', function ($customer, $customerId) {
    return $customer instanceof Customer && $customer->customer_id === $customerId;
});

// Channel untuk notifikasi admin
Broadcast::channel('admin.{adminId}', function ($admin, $adminId) {
    return $admin instanceof Admin && (int) $admin->id === (int) $adminId;
});

// Channel untuk status online customer
Broadcast::channel('customer-online', function ($customer) {
    if ($customer instanceof Customer) {
        return [
            'id' => $customer->customer_id,
            'name' => $customer->name,
        ];
    }
    return false;
});

// Channel untuk status online admin
Broadcast::channel('admin-online', function ($admin) {
    if ($admin instanceof Admin) {
        return [
            'id' => $admin->id,
            'name' => $admin->name,
        ];
    }
    return false;
});
