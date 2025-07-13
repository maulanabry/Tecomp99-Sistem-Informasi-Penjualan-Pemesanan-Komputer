<?php

require_once 'vendor/autoload.php';

use App\Models\Customer;
use App\Mail\CustomerEmailVerification;
use Illuminate\Support\Facades\Mail;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== EMAIL CONFIGURATION TEST ===\n";
echo "MAIL_MAILER: " . config('mail.default') . "\n";
echo "MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "MAIL_PASSWORD: " . (config('mail.mailers.smtp.password') ? '***SET***' : 'NOT SET') . "\n";
echo "MAIL_ENCRYPTION: " . (config('mail.mailers.smtp.encryption') ?: 'null') . "\n";
echo "MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
echo "MAIL_FROM_NAME: " . config('mail.from.name') . "\n";
echo "\n";

// Test basic mail configuration
echo "=== TESTING MAIL CONFIGURATION ===\n";

if (!config('mail.mailers.smtp.host')) {
    echo "❌ MAIL_HOST is not configured!\n";
    exit(1);
}

if (!config('mail.mailers.smtp.username')) {
    echo "❌ MAIL_USERNAME is not configured!\n";
    exit(1);
}

if (!config('mail.mailers.smtp.password')) {
    echo "❌ MAIL_PASSWORD is not configured!\n";
    exit(1);
}

echo "✅ Basic configuration looks good\n";
echo "\n";

try {
    // Create a test customer
    $customer = new Customer();
    $customer->customer_id = 'TEST001';
    $customer->name = 'Test User';
    $customer->email = 'test@example.com';

    echo "=== SENDING TEST EMAIL ===\n";
    echo "Creating test customer...\n";
    echo "Preparing email verification mail...\n";

    // Test if we can create the mail instance
    $mail = new CustomerEmailVerification($customer);
    echo "✅ Mail instance created successfully\n";

    echo "Sending email to: test@example.com\n";
    Mail::to('test@example.com')->send($mail);

    echo "✅ Email sent successfully!\n";
    echo "📧 Check your Mailtrap inbox at: https://mailtrap.io/inboxes\n";
} catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
    echo "❌ SMTP Transport Error: " . $e->getMessage() . "\n";
    echo "This usually means connection issues with Mailtrap server\n";
    echo "\n";
    echo "💡 Troubleshooting tips:\n";
    echo "1. Check your Mailtrap credentials\n";
    echo "2. Verify your internet connection\n";
    echo "3. Make sure you're using the correct Mailtrap host and port\n";
} catch (\InvalidArgumentException $e) {
    echo "❌ Email Format Error: " . $e->getMessage() . "\n";
    echo "Check your FROM email address format\n";
} catch (\Exception $e) {
    echo "❌ Email sending failed: " . $e->getMessage() . "\n";
    echo "Error type: " . get_class($e) . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
