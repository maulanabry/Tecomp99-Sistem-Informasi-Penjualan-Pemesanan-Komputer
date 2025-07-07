<?php

namespace App\Console\Commands;

use App\Services\TeknisiNotificationService;
use Illuminate\Console\Command;

class CheckTodayVisits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teknisi:check-today-visits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for today\'s visit schedules and send notifications';

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
        $this->info('Checking for today\'s visit schedules...');

        $this->teknisiNotificationService->checkTodayVisitSchedules();

        $this->info('Today\'s visit check completed.');

        return 0;
    }
}
