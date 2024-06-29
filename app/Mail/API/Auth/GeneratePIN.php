<?php

namespace App\Mail\API\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GeneratePIN extends Mailable{
    use Queueable, SerializesModels;
    public string $_username, $_mail, $_pin;
    /**
     * Create a new message instance.
     */
    public function __construct($username, $mail, $pin){
        $this->_username = $username;
        $this->_mail = $mail;
        $this->_pin = $pin;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope{
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')),
            subject: __('Restart PIN'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content{
        return new Content(
            markdown: 'public.auth.emails.generate-pin',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array{
        return [];
    }
}
