<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->event=$event;
    }

   

    public function build()
    {
        return $this->markdown('emails.eventsubmitted')->subject('Verify Your Email to Activate Account')->with(['action_url'=>config('app.front_url').'/event/'.$this->event->slug, 'event' => $this->event]);
    }
}
