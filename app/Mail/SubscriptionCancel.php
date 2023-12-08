<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;

class SubscriptionCancel extends Mailable
{
    use Queueable, SerializesModels;


    protected $getUser;
    protected $expire_at;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $invoice_id)
    {
        $this->getUser=$user;
        $this->getInvoice=$this->getUser->findInvoice($invoice_id);
        
        $this->expire_at=Carbon::parse(@$this->getInvoice->lines->data[0]->period->end)
                ->format("m-d-Y");
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        if($this->getUser->roles[0]->id == 4){
            $url= '/company-subscription/resume';
        }else{
            $url= '/pilot-subscription/resume';
        }
        return $this->markdown('emails.subscriptionCancel')
            ->subject('Your Subscription Cancelled')
            ->with([
                    'action_url'=>config('app.front_url').'/login',
                    'resume_subscription'=>config('app.front_url').$url,
                    'expire_at'=>$this->expire_at,
               ]);
    }
}
