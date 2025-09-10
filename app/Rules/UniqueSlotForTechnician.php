<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\ServiceTicket;

class UniqueSlotForTechnician implements ValidationRule
{
    protected $adminId;
    protected $visitDate;
    protected $excludeTicketId;

    /**
     * Create a new rule instance.
     *
     * @param int $adminId
     * @param string $visitDate
     * @param string|null $excludeTicketId
     */
    public function __construct($adminId, $visitDate, $excludeTicketId = null)
    {
        $this->adminId = $adminId;
        $this->visitDate = $visitDate;
        $this->excludeTicketId = $excludeTicketId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if slot is already booked by this technician
        $query = ServiceTicket::where('admin_id', $this->adminId)
            ->whereDate('visit_schedule', $this->visitDate)
            ->whereTime('visit_schedule', $value)
            ->whereNotNull('visit_schedule')
            ->whereHas('orderService', function ($q) {
                $q->where('type', 'onsite'); // Only for onsite services
            });

        // Exclude current ticket if editing
        if ($this->excludeTicketId) {
            $query->where('service_ticket_id', '!=', $this->excludeTicketId);
        }

        $isSlotTaken = $query->exists();

        if ($isSlotTaken) {
            $existingTicket = $query->first();
            $customerName = $existingTicket->orderService->customer->name ?? 'Unknown';
            $fail("Slot {$value} sudah diambil oleh {$customerName}");
            return;
        }

        // Check daily limit (maximum 4 visits per technician per day)
        $dailyVisitsQuery = ServiceTicket::where('admin_id', $this->adminId)
            ->whereDate('visit_schedule', $this->visitDate)
            ->whereNotNull('visit_schedule')
            ->whereHas('orderService', function ($q) {
                $q->where('type', 'onsite'); // Only for onsite services
            });

        if ($this->excludeTicketId) {
            $dailyVisitsQuery->where('service_ticket_id', '!=', $this->excludeTicketId);
        }

        $dailyVisitsCount = $dailyVisitsQuery->count();
        $maxVisitsPerDay = 4;

        if ($dailyVisitsCount >= $maxVisitsPerDay) {
            $fail('Teknisi sudah mencapai batas maksimal kunjungan hari ini (' . $maxVisitsPerDay . ' kunjungan)');
            return;
        }
    }
}
