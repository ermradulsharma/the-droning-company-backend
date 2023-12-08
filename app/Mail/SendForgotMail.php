<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SendForgotMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $user_mail;
    protected $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $token)
    {
        $this->user_mail=$user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.sendForgotMail')
            ->subject('You Received a Password Reset Request')
            ->with([
            'name'=>$this->user_mail->first_name,
            'email'=>$this->user_mail->email,
            'token'=>$this->token,
            'action_url'=>config('app.front_url').'/update-password/'.$this->token,
            'login_url'=>config('app.front_url').'/login',
        ]);
    }
}
