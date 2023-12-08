<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class VerifyUserMail extends Mailable
{
    use Queueable, SerializesModels;
        protected $user_mail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
       $this->user_mail=$user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('api.email.userMailVerify')->subject('Complete your signup process | Drone')->with([
            'name'=>$this->user_mail->first_name.' '.$this->user_mail->last_name,
            'email'=>$this->user_mail->email
        ]);
    }
}
