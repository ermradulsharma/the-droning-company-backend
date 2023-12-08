<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeCompany extends Mailable
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

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.welcomeCompany')
             ->subject('Welcome Pilot - Thanks for Your Signup')
             ->with([
                   'forgot_password_url'=>config('app.front_url').'/forgot-password',
                    'login_url'=>config('app.front_url').'/login',
                   'email'=>$this->user->email,
                   'first_name'=>$this->user->first_name,
               ]);
    }
}
