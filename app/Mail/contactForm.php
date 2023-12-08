<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class contactForm extends Mailable
{
    use Queueable, SerializesModels;
    protected $recipient;
    protected $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $recipient)
    {
        $this->data = $data;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contactForm')->to($this->recipient)
                    ->replyTo($this->data['email'], $this->data['name'])
                    ->subject('New Contact Form Submission')->with('data', $this->data)->replyTo('reply@example.com', 'Reply Guy');
    }
}
