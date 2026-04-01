<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PharmacovigilanceAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $order;
    public $badMedication;

    /**
     * Create a new message instance.
     */
    public function __construct($customer, $order, $badMedication)
    {
        $this->customer = $customer;
        $this->order = $order;
        $this->badMedication = $badMedication;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'URGENT: Pharmacovigilance Safety Alert - Lot Recall',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.alerts.pharmacovigilance',
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
