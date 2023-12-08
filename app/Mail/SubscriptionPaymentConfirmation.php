<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionPaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    protected $invoice_id;
    protected $getUser;
    protected $getSubscription;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $invoice_id)
    {
        $this->getUser=$user;
        $this->getInvoice=$this->getUser->findInvoice($invoice_id);
        $this->getSubscription=$this->getUser->subscriptions->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.subscriptionPaymentConfirmation')
                    ->subject('Thanks for Your Subscription')
                    ->with(['login_url'=>'',
                        'user'=>$this->getUser,
                        'invoice'=>$this->getInvoice,
                        'subscription'=>$this->getSubscription
                    ]);
    }
}
