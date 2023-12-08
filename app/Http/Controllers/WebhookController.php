<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionPaymentConfirmation;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends CashierController
{
    /**
     * Handle payment succeeds.
     *
     * @param  array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleInvoicePaymentSucceeded(array $payload)
    {
        $invoice = $payload['data']['object'];
        $user = $this->getUserByStripeId($invoice['customer']);

        if ($user) {
            $getUser=User::where('stripe_id', $invoice['customer'])->first();
            Mail::to($invoice['customer_email'])
            ->send(new SubscriptionPaymentConfirmation($getUser, $invoice['id']));
        }

        return new Response('Webhook Handled', 200);
    }
}
