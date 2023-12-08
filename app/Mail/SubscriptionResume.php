<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;

class SubscriptionResume extends Mailable
{
    use Queueable, SerializesModels;

    protected $invoice_id;
    protected $getUser;
    protected $getSubscription;
    protected $renew_at;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $invoice_id)
    {
        $this->getUser=$user;
        $this->getInvoice=$this->getUser->findInvoice($invoice_id);
        // $this->getSubscription=$this->getUser->subscriptions->first();

        $this->renew_at=Carbon::parse(@$this->getInvoice->lines->data[0]->period->end)
                ->format("m-d-Y");
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.subscriptionResumeNew')
                 ->subject('Your Subscription Resumed')
                            ->with(['action_url'=>config('app.front_url').'/login/',
                                'user'=>$this->getUser,
                                //'invoice'=>$this->getInvoice,
                                //'subscription'=>$this->getSubscription,
                                'renew_at'=>$this->renew_at,
                            ]);
    }
}
