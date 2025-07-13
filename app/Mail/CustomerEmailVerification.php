<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class CustomerEmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
        $this->verificationUrl = $this->verificationUrl($customer);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verifikasi Email Anda - Tecomp99',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-verification',
            with: [
                'customer' => $this->customer,
                'verificationUrl' => $this->verificationUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Get the verification URL for the given customer.
     */
    protected function verificationUrl(Customer $customer): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $customer->getKey(),
                'hash' => sha1($customer->getEmailForVerification()),
            ]
        );
    }
}
