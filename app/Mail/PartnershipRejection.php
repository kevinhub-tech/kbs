<?php

namespace App\Mail;

use App\Models\vendorApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnershipRejection extends Mailable
{
    use Queueable, SerializesModels;
    protected $vendor;
    public function __construct($vendor_application_id)
    {
        $this->vendor = vendorApplication::find($vendor_application_id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Partner Ship Rejection Mail from KBS',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail-format.vendors.applicationrejectionletter',
            with: [
                'vendor_application' => $this->vendor
            ]
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
}
