<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Mail\CustomerEmailVerification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email sending to Mailtrap';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info('=== EMAIL CONFIGURATION TEST ===');
        $this->info('Email: ' . $email);
        $this->info('MAIL_MAILER: ' . config('mail.default'));
        $this->info('MAIL_HOST: ' . config('mail.mailers.smtp.host'));
        $this->info('MAIL_PORT: ' . config('mail.mailers.smtp.port'));
        $this->info('MAIL_USERNAME: ' . config('mail.mailers.smtp.username'));
        $this->info('MAIL_PASSWORD: ' . (config('mail.mailers.smtp.password') ? '***SET***' : 'NOT SET'));
        $this->info('MAIL_ENCRYPTION: ' . (config('mail.mailers.smtp.encryption') ?: 'null'));
        $this->info('MAIL_FROM_ADDRESS: ' . config('mail.from.address'));
        $this->info('MAIL_FROM_NAME: ' . config('mail.from.name'));
        $this->line('');

        // Test basic mail configuration
        $this->info('=== TESTING MAIL CONFIGURATION ===');

        if (!config('mail.mailers.smtp.host')) {
            $this->error('❌ MAIL_HOST is not configured!');
            return 1;
        }

        if (!config('mail.mailers.smtp.username')) {
            $this->error('❌ MAIL_USERNAME is not configured!');
            return 1;
        }

        if (!config('mail.mailers.smtp.password')) {
            $this->error('❌ MAIL_PASSWORD is not configured!');
            return 1;
        }

        $this->info('✅ Basic configuration looks good');
        $this->line('');

        try {
            // Create a test customer
            $customer = new Customer();
            $customer->customer_id = 'TEST001';
            $customer->name = 'Test User';
            $customer->email = $email;

            $this->info('=== SENDING TEST EMAIL ===');
            $this->info('Creating test customer...');
            $this->info('Preparing email verification mail...');

            // Test if we can create the mail instance
            $mail = new CustomerEmailVerification($customer);
            $this->info('✅ Mail instance created successfully');

            $this->info('Sending email to: ' . $email);
            Mail::to($email)->send($mail);

            $this->info('✅ Email sent successfully!');
            $this->info('📧 Check your Mailtrap inbox at: https://mailtrap.io/inboxes');
        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            $this->error('❌ SMTP Transport Error: ' . $e->getMessage());
            $this->error('This usually means connection issues with Mailtrap server');
            $this->line('');
            $this->info('💡 Troubleshooting tips:');
            $this->info('1. Check your Mailtrap credentials');
            $this->info('2. Verify your internet connection');
            $this->info('3. Make sure you\'re using the correct Mailtrap host and port');
        } catch (\InvalidArgumentException $e) {
            $this->error('❌ Email Format Error: ' . $e->getMessage());
            $this->error('Check your FROM email address format');
        } catch (\Exception $e) {
            $this->error('❌ Email sending failed: ' . $e->getMessage());
            $this->error('Error type: ' . get_class($e));

            if ($this->option('verbose')) {
                $this->error('Stack trace: ' . $e->getTraceAsString());
            } else {
                $this->info('Run with -v flag for detailed stack trace');
            }
        }

        return 0;
    }
}
