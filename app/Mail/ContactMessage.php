<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    // Define the public variable so it's accessible in the email view
    public $formData;

    /**
     * Create a new message instance.
     */
    public function __construct($formData)
    {
        $this->formData = $formData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Portfolio Inquiry: ' . ($this->formData['subject'] ?? 'No Subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // This creates a simple HTML email using a Blade view
            htmlString: "
                <h2>New Contact Form Submission</h2>
                <p><strong>Name:</strong> {$this->formData['name']}</p>
                <p><strong>Email:</strong> {$this->formData['email']}</p>
                <p><strong>Message:</strong></p>
                <p>{$this->formData['message']}</p>
            ",
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}