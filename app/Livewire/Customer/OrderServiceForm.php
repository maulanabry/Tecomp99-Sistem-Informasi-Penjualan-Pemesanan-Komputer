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
use Carbon\Carbon;

class OrderServiceForm extends Component
{
    use WithFileUploads;

    // Form properties
    public $mode = 'onsite'; // onsite or ticket
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
        'tanggal_kunjungan' => 'required_if:mode,onsite|date|after:today',
        'slot_waktu' => 'required_if:mode,onsite',
        'uploadedFiles.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,mp4,avi,mov',
    ];

    protected $messages = [
        'keluhan.required' => 'Keluhan harus diisi.',
        'keluhan.min' => 'Keluhan minimal 10 karakter.',
        'keluhan.max' => 'Keluhan maksimal 500 karakter.',
        'jenis_perangkat.required' => 'Jenis perangkat harus diisi.',
        'jenis_perangkat.min' => 'Jenis perangkat minimal 3 karakter.',
        'jenis_perangkat.max' => 'Jenis perangkat maksimal 100 karakter.',
        'tanggal_kunjungan.required_if' => 'Tanggal kunjungan harus dipilih untuk servis onsite.',
        'tanggal_kunjungan.date' => 'Format tanggal tidak valid.',
        'tanggal_kunjungan.after' => 'Tanggal kunjungan harus setelah hari ini.',
        'slot_waktu.required_if' => 'Slot waktu harus dipilih untuk servis onsite.',
        'uploadedFiles.*.file' => 'File yang diupload tidak valid.',
        'uploadedFiles.*.max' => 'Ukuran file maksimal 10MB.',
        'uploadedFiles.*.mimes' => 'File harus berformat: jpg, jpeg, png, gif, mp4, avi, mov.',
    ];

    public function mount()
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();
        $this->customer = $customer;
        $this->hasAddress = $this->customer->addresses()->exists();

        // Set minimum date to tomorrow
        $this->tanggal_kunjungan = Carbon::tomorrow()->format('Y-m-d');
        $this->checkSlotAvailability();
    }

    public function updatedMode()
    {
        $this->reset(['tanggal_kunjungan', 'slot_waktu']);
        if ($this->mode === 'onsite') {
            $this->tanggal_kunjungan = Carbon::tomorrow()->format('Y-m-d');
            $this->checkSlotAvailability();
        }
    }

    public function updatedTanggalKunjungan()
    {
        if ($this->tanggal_kunjungan) {
            $this->checkSlotAvailability();
            $this->slot_waktu = ''; // Reset selected slot
        }
    }

    public function checkSlotAvailability()
    {
        if (!$this->tanggal_kunjungan) {
            return;
        }

        $date = Carbon::parse($this->tanggal_kunjungan);

        // Check total orders for the date (max 4 per day regardless of slot)
        $totalOrdersCount = OrderService::whereDate('created_at', $date)
            ->where('type', 'onsite')
            ->count();

        // If date has 4 or more orders, disable all slots
        $dateIsFull = $totalOrdersCount >= 4;

        $this->slotsStatus = [];
        foreach ($this->availableSlots as $slot) {
            $this->slotsStatus[$slot] = [
                'available' => !$dateIsFull,
                'count' => $totalOrdersCount
            ];
        }

        // Update disabled dates for datepicker
        $this->updateDisabledDates();
    }

    private function updateDisabledDates()
    {
        // Get dates that have 4 or more orders for the next 30 days
        $startDate = Carbon::tomorrow();
        $endDate = Carbon::tomorrow()->addDays(30);

        $this->disabledDates = [];

        $fullDates = OrderService::selectRaw('DATE(created_at) as order_date, COUNT(*) as total_orders')
            ->where('type', 'onsite')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('order_date')
            ->having('total_orders', '>=', 4)
            ->pluck('order_date')
            ->toArray();

        $this->disabledDates = $fullDates;
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

        if ($this->mode === 'onsite' && !$this->slotsStatus[$this->slot_waktu]['available']) {
            $this->addError('slot_waktu', 'Slot waktu yang dipilih sudah penuh.');
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
            'type' => $this->mode,
            'device' => $this->jenis_perangkat,
            'note' => json_encode([
                'tanggal_kunjungan' => $this->mode === 'onsite' ? $this->tanggal_kunjungan : null,
                'slot_waktu' => $this->mode === 'onsite' ? $this->slot_waktu : null,
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
        if ($this->mode === 'onsite') {
            $this->createServiceTicket($orderService);
        }

        // Reset form
        $this->reset(['keluhan', 'jenis_perangkat', 'tanggal_kunjungan', 'slot_waktu', 'uploadedFiles', 'previews']);

        session()->flash('success', 'Pesanan servis berhasil dibuat! ID Pesanan: ' . $orderId);

        return redirect()->route('customer.orders.services');
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
                if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
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
