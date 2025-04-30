<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Itinerary;

class ItinerarySharedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    
    public $itinerary;

    // Constructor to pass the itinerary data to the email
    public function __construct(Itinerary $itinerary)
    {
        $this->itinerary = $itinerary;
    }

    // Build the message
    public function build()
    {
        return $this->view('emails.itinerary_shared') // The view file for the email
                    ->subject('Itinerary Shared with You') // Email subject
                    ->with([
                        'itinerary' => $this->itinerary, // Pass the itinerary to the view
                        'url' => route('itineraries.show', $this->itinerary->id) // Link to the itinerary
                    ]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Itinerary Shared Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.itinerary_shared',
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
