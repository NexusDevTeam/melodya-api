<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class Mailgun extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $notificationMessage;
    public $actionUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $notificationMessage, $actionUrl)
    {
        $this->user = $user;
        $this->notificationMessage = $notificationMessage;
        $this->actionUrl = $actionUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), 'EducrIA'),
            subject: 'Nova Notificação - EducrIA',

        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notification',
            with: [
                'user' => $this->user,
                'notificationMessage' => $this->notificationMessage,
                'actionUrl' => $this->actionUrl,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
