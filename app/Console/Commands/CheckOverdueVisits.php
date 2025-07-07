<?php

namespace App\Console\Commands;

use App\Services\TeknisiNotificationService;
use Illuminate\Console\Command;

class CheckOverdueVisits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teknisi:check-overdue-visits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue visit schedules and send notifications';

    protected $teknisiNotificationService;

    public function __construct(TeknisiNotificationService $teknisiNotificationService)
    {
        parent::__construct();
        $this->teknisiNotificationService = $teknisiNotificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue visit schedules...');

        $this->teknisiNotificationService->checkOverdueVisitSchedules();

        $this->info('Overdue visit check completed.');

        return 0;
    }
}
