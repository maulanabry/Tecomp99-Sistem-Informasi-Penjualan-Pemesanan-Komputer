<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Customer;
use App\Models\OrderService;
use App\Models\OrderServiceMedia;
use App\Models\CustomerAddress;
use App\Models\ServiceTicket;
use App\Models\Admin;
use App\Services\NotificationService;
use App\Enums\NotificationType;
use Carbon\Carbon;

class OrderServiceForm extends Component
{
    use WithFileUploads;

    // Form properties
    public $keluhan = '';
    public $jenis_perangkat = '';
    public $tanggal_kunjungan = '';
    public $slot_waktu = '';
    public $uploadedFiles = [];
    public $previews = [];

    // Customer info
    public $customer;
    public $hasAddress = false;

    // Slot availability - Updated time slots as per feedback
    public $availableSlots = [
        '08:00 - 09:30',
        '10:30 - 12:00',
        '13:00 - 14:30',
        '15:30 - 17:00',
        '18:00 - 19:30'
    ];
    public $slotsStatus = [];
    public $disabledDates = [];

    protected $rules = [
        'keluhan' => 'required|min:10|max:500',
        'jenis_perangkat' => 'required|min:3|max:100',
        'tanggal_kunjungan' => 'required|date|after:today',
        'slot_waktu' => 'required',
        'uploadedFiles.*' => 'file|max:2048|mimes:jpg,jpeg,png,gif',
    ];

    protected $messages = [
        'keluhan.required' => 'Keluhan harus diisi.',
        'keluhan.min' => 'Keluhan minimal 10 karakter.',
        'keluhan.max' => 'Keluhan maksimal 500 karakter.',
        'jenis_perangkat.required' => 'Jenis perangkat harus diisi.',
        'jenis_perangkat.min' => 'Jenis perangkat minimal 3 karakter.',
        'jenis_perangkat.max' => 'Jenis perangkat maksimal 100 karakter.',
        'tanggal_kunjungan.required' => 'Tanggal kunjungan harus dipilih.',
        'tanggal_kunjungan.date' => 'Format tanggal tidak valid.',
        'tanggal_kunjungan.after' => 'Tanggal kunjungan harus setelah hari ini.',
        'slot_waktu.required' => 'Slot waktu harus dipilih.',
        'uploadedFiles.*.file' => 'File yang diupload tidak valid.',
        'uploadedFiles.*.max' => 'Ukuran file maksimal 2MB.',
        'uploadedFiles.*.mimes' => 'File harus berformat foto: jpg, jpeg, png, gif.',
    ];

    public function mount()
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();
        $this->customer = $customer;
        $this->hasAddress = $this->customer->addresses()->exists();

        // Set minimum date to tomorrow
        $this->tanggal_kunjungan = Carbon::tomorrow()->format('Y-m-d');

        // Initialize slots status as all available
        $this->slotsStatus = [];
        foreach ($this->availableSlots as $slot) {
            $this->slotsStatus[$slot] = [
                'available' => true,
                'count' => 0
            ];
        }

        $this->checkSlotAvailability();
    }


    public function updatedTanggalKunjungan()
    {
        if ($this->tanggal_kunjungan) {
            // Reset selected slot first
            $this->slot_waktu = '';

            // Force re-check slot availability
            $this->checkSlotAvailability();

            // Force component re-render
            $this->dispatch('slot-availability-updated');
        }
    }

    public function checkSlotAvailability()
    {
        if (!$this->tanggal_kunjungan) {
            return;
        }

        $selectedDate = $this->tanggal_kunjungan;

        // Get all existing onsite orders
        $existingOrders = OrderService::where('type', 'onsite')
            ->whereNotNull('note')
            ->get();

        // Extract booked slots for the selected date
        $bookedSlots = [];
        foreach ($existingOrders as $order) {
            $note = json_decode($order->note, true);
            if (isset($note['tanggal_kunjungan']) && isset($note['slot_waktu'])) {
                // Check if the visit date matches our selected date
                if ($note['tanggal_kunjungan'] === $selectedDate) {
                    $bookedSlots[] = $note['slot_waktu'];
                }
            }
        }

        // Check availability for each slot (max 1 booking per slot)
        $this->slotsStatus = [];
        foreach ($this->availableSlots as $slot) {
            $slotBookingCount = array_count_values($bookedSlots)[$slot] ?? 0;
            $this->slotsStatus[$slot] = [
                'available' => $slotBookingCount < 1, // Max 1 booking per slot
                'count' => $slotBookingCount
            ];
        }

        // Update disabled dates for datepicker
        $this->updateDisabledDates();
    }

    private function updateDisabledDates()
    {
        // Get dates that have all slots booked for the next 30 days
        $startDate = Carbon::tomorrow();
        $endDate = Carbon::tomorrow()->addDays(30);

        $this->disabledDates = [];

        // Get all existing onsite orders
        $existingOrders = OrderService::where('type', 'onsite')
            ->whereNotNull('note')
            ->get();

        // Check each date in the range
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateString = $date->format('Y-m-d');

            // Extract booked slots for this specific date
            $bookedSlots = [];
            foreach ($existingOrders as $order) {
                $note = json_decode($order->note, true);
                if (isset($note['tanggal_kunjungan']) && isset($note['slot_waktu'])) {
                    if ($note['tanggal_kunjungan'] === $dateString) {
                        $bookedSlots[] = $note['slot_waktu'];
                    }
                }
            }

            // Count unique booked slots
            $uniqueBookedSlots = array_unique($bookedSlots);

            // If all 5 slots are booked, disable this date
            if (count($uniqueBookedSlots) >= count($this->availableSlots)) {
                $this->disabledDates[] = $dateString;
            }
        }
    }

    public function updatedUploadedFiles()
    {
        $this->previews = [];

        foreach ($this->uploadedFiles as $index => $file) {
            if ($file) {
                $this->previews[$index] = [
                    'name' => $file->getClientOriginalName(),
                    'size' => $this->formatFileSize($file->getSize()),
                    'type' => $file->getClientOriginalExtension(),
                    'url' => $file->temporaryUrl()
                ];
            }
        }
    }

    public function removeFile($index)
    {
        unset($this->uploadedFiles[$index]);
        unset($this->previews[$index]);
        $this->uploadedFiles = array_values($this->uploadedFiles);
        $this->previews = array_values($this->previews);
    }

    public function submitOrder()
    {
        $this->validate();

        if (!$this->hasAddress) {
            $this->addError('address', 'Silakan lengkapi alamat Anda terlebih dahulu.');
            return;
        }

        // Double-check slot availability right before creating order to prevent race conditions
        $selectedDate = $this->tanggal_kunjungan;
        $selectedSlot = $this->slot_waktu;

        // Get all existing onsite orders
        $existingOrders = OrderService::where('type', 'onsite')
            ->whereNotNull('note')
            ->get();

        // Check if the selected slot is already taken
        $slotTaken = false;
        foreach ($existingOrders as $order) {
            $note = json_decode($order->note, true);
            if (isset($note['tanggal_kunjungan']) && isset($note['slot_waktu'])) {
                if ($note['tanggal_kunjungan'] === $selectedDate && $note['slot_waktu'] === $selectedSlot) {
                    $slotTaken = true;
                    break;
                }
            }
        }

        if ($slotTaken) {
            $this->addError('slot_waktu', 'Slot waktu yang dipilih sudah penuh. Silakan pilih slot lain.');
            // Refresh slot availability
            $this->checkSlotAvailability();
            return;
        }

        // Generate order ID
        $orderId = $this->generateOrderId();

        // Create order service
        $orderService = OrderService::create([
            'order_service_id' => $orderId,
            'customer_id' => $this->customer->customer_id,
            'status_order' => 'Menunggu',
            'status_payment' => 'belum_dibayar',
            'complaints' => $this->keluhan,
            'type' => 'onsite',
            'device' => $this->jenis_perangkat,
            'note' => json_encode([
                'tanggal_kunjungan' => $this->tanggal_kunjungan,
                'slot_waktu' => $this->slot_waktu,
            ]),
            'hasTicket' => false,
            'hasDevice' => false,
            'sub_total' => 0,
            'grand_total' => 0,
            'discount_amount' => 0,
            'paid_amount' => 0,
            'remaining_balance' => 0,
        ]);

        // Handle file uploads
        if (!empty($this->uploadedFiles)) {
            $this->handleFileUploads($orderService);
        }

        // Create service ticket for onsite orders
        $this->createServiceTicket($orderService);

        // Send notifications to all admins
        $this->sendAdminNotifications($orderService);

        // Reset form
        $this->reset(['keluhan', 'jenis_perangkat', 'tanggal_kunjungan', 'slot_waktu', 'uploadedFiles', 'previews']);

        session()->flash('success', 'Pesanan servis berhasil dibuat! ID Pesanan: ' . $orderId);

        return redirect()->route('customer.orders.services.show', $orderService->order_service_id);
    }

    private function generateOrderId()
    {
        $date = now()->format('dmy');
        $lastOrder = OrderService::withTrashed()
            ->where('order_service_id', 'like', "SRV{$date}%")
            ->orderBy('order_service_id', 'desc')
            ->first();

        if (!$lastOrder) {
            return "SRV{$date}001";
        }

        $lastNumber = (int) substr($lastOrder->order_service_id, -3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return "SRV{$date}{$newNumber}";
    }

    private function handleFileUploads($orderService)
    {
        $uploadPath = "order_service/{$orderService->order_service_id}";

        foreach ($this->uploadedFiles as $index => $file) {
            if ($file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = "media-{$orderService->order_service_id}-" . ($index + 1) . ".{$extension}";

                // Get file content for compression and storage
                $fileContent = file_get_contents($file->getRealPath());

                // Compress file if it's an image (basic compression)
                if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $fileContent = $this->compressImage($file->getRealPath(), $extension);
                }

                // Store file in private storage using Storage::put()
                $filePath = $uploadPath . '/' . $fileName;
                Storage::disk('local')->put($filePath, $fileContent);

                // Save to database with new field names
                OrderServiceMedia::create([
                    'order_service_id' => $orderService->order_service_id,
                    'media_path' => $filePath,
                    'media_name' => $originalName,
                    'file_type' => $extension,
                    'file_size' => $file->getSize(),
                    'is_main' => $index === 0
                ]);
            }
        }
    }

    /**
     * Basic image compression
     */
    private function compressImage($filePath, $extension)
    {
        // Basic compression using GD library if available
        if (!extension_loaded('gd')) {
            return file_get_contents($filePath);
        }

        try {
            switch (strtolower($extension)) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($filePath);
                    break;
                case 'png':
                    $image = imagecreatefrompng($filePath);
                    break;
                case 'gif':
                    $image = imagecreatefromgif($filePath);
                    break;
                default:
                    return file_get_contents($filePath);
            }

            if (!$image) {
                return file_get_contents($filePath);
            }

            // Start output buffering
            ob_start();

            // Compress and output
            switch (strtolower($extension)) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($image, null, 80); // 80% quality
                    break;
                case 'png':
                    imagepng($image, null, 8); // Compression level 8
                    break;
                case 'gif':
                    imagegif($image, null);
                    break;
            }

            $compressedContent = ob_get_contents();
            ob_end_clean();

            imagedestroy($image);

            return $compressedContent;
        } catch (\Exception $e) {
            // If compression fails, return original file
            return file_get_contents($filePath);
        }
    }

    /**
     * Create service ticket for onsite orders
     */
    private function createServiceTicket($orderService)
    {
        // Generate service ticket ID
        $ticketId = $this->generateServiceTicketId();

        // Parse visit schedule from selected date and time slot
        $visitSchedule = null;
        if ($this->tanggal_kunjungan && $this->slot_waktu) {
            // Extract start time from slot (e.g., "08:00 - 09:30" -> "08:00")
            $startTime = explode(' - ', $this->slot_waktu)[0];
            $visitSchedule = Carbon::parse($this->tanggal_kunjungan . ' ' . $startTime);
        }

        // Create service ticket
        ServiceTicket::create([
            'service_ticket_id' => $ticketId,
            'order_service_id' => $orderService->order_service_id,
            'admin_id' => null, // Will be assigned later by admin
            'status' => 'Menunggu',
            'schedule_date' => $visitSchedule ? $visitSchedule->toDateString() : Carbon::tomorrow()->toDateString(),
            'visit_schedule' => $visitSchedule,
            'estimation_days' => null, // Will be set by technician
            'estimate_date' => null, // Will be calculated later
        ]);

        // Update order service to indicate it has a ticket
        $orderService->update(['hasTicket' => true]);
    }

    /**
     * Generate service ticket ID
     */
    private function generateServiceTicketId()
    {
        $date = now()->format('dmy');
        $lastTicket = ServiceTicket::withTrashed()
            ->where('service_ticket_id', 'like', "TKT{$date}%")
            ->orderBy('service_ticket_id', 'desc')
            ->first();

        if (!$lastTicket) {
            return "TKT{$date}001";
        }

        $lastNumber = (int) substr($lastTicket->service_ticket_id, -3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return "TKT{$date}{$newNumber}";
    }

    /**
     * Send notifications to all admins about new service order
     */
    private function sendAdminNotifications(OrderService $orderService)
    {
        try {
            // Get all active admins
            $admins = Admin::whereNull('deleted_at')->get();

            if ($admins->isEmpty()) {
                return; // No admins to notify
            }

            // Initialize notification service
            $notificationService = new NotificationService();

            // Parse visit schedule for notification message
            $note = json_decode($orderService->note, true);
            $visitDate = isset($note['tanggal_kunjungan']) ? Carbon::parse($note['tanggal_kunjungan'])->format('d F Y') : 'Tidak ditentukan';
            $visitTime = $note['slot_waktu'] ?? 'Tidak ditentukan';

            // Create notification message
            $message = "Pesanan servis onsite baru dari {$this->customer->name} (ID: {$orderService->order_service_id}). " .
                "Perangkat: {$orderService->device}. " .
                "Jadwal kunjungan: {$visitDate} pada {$visitTime}.";

            // Send notification to each admin
            foreach ($admins as $admin) {
                $notificationService->create(
                    $admin,
                    NotificationType::SERVICE_ORDER_CREATED,
                    $orderService,
                    $message,
                    [
                        'customer_name' => $this->customer->name,
                        'customer_id' => $this->customer->customer_id,
                        'order_id' => $orderService->order_service_id,
                        'device' => $orderService->device,
                        'visit_date' => $visitDate,
                        'visit_time' => $visitTime,
                        'complaints' => $orderService->complaints
                    ]
                );
            }
        } catch (\Exception $e) {
            // Log error but don't fail the order creation
            \Log::error('Failed to send admin notifications for service order: ' . $e->getMessage(), [
                'order_id' => $orderService->order_service_id,
                'customer_id' => $this->customer->customer_id
            ]);
        }
    }

    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function render()
    {
        return view('livewire.customer.order-service-form');
    }
}
