<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SendUserPassMail extends Mailable
{
    use Queueable, SerializesModels;
        protected $user_mail;
        protected $pass;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $pass)
    {
       $this->user_mail=$user;
       $this->password=$pass;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('admin.email.userMailPass')->subject('Your Password Changed')->with([
            'name'=>$this->user_mail->first_name.' '.$this->user_mail->last_name,
            'email'=>$this->user_mail->email,
            'password'=>$this->password
        ]);
    }
}
