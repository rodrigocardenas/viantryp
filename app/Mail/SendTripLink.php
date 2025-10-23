<?php

namespace App\Mail;

use App\Models\Trip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendTripLink extends Mailable
{
    use Queueable, SerializesModels;

    public $trip;
    public $customMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(Trip $trip, string $customMessage = null)
    {
        $this->trip = $trip;
        $this->customMessage = $customMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Enlace para ver tu viaje: ' . $this->trip->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.send-trip-link',
            with: [
                'trip' => $this->trip,
                'customMessage' => $this->customMessage,
                'shareUrl' => $this->trip->getShareUrl(),
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
}
