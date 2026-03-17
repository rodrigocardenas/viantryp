<?php

namespace App\Mail;

use App\Models\Trip;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TripCollaborationInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $trip;
    public $role;
    public $inviteUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Trip $trip, string $role, string $inviteUrl)
    {
        $this->trip = $trip;
        $this->role = $role;
        $this->inviteUrl = $inviteUrl;
    }

    /**
     * Get the message envelope.
     */
    public function Envelope(): Envelope
    {
        $roleLabel = $this->role === 'editor' ? 'editar' : 'ver';
        return new Envelope(
            subject: "Invitación para {$roleLabel} el viaje: {$this->trip->title}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function Content(): Content
    {
        return new Content(
            view: 'emails.trip-invite',
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
