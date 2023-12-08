<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerify extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user=$user;
    }

   

    public function build()
    {
        return $this->markdown('emails.emailVerify')
             ->subject('Verify Your Email to Activate Account')->with([
                    'action_url'=>config('app.front_url').'/verify-email/'.base64_encode($this->user->email)
        ]);
    }
}
