<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SendUserMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $user_mail;
    protected $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {

        $this->user_mail = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('admin.email.userMail')->subject('Account successfully created')->with([
            'name' => $this->user_mail->first_name . ' ' . $this->user_mail->last_name,
            'email' => $this->user_mail->email,
            'password' => $this->password
        ]);
    }
}
