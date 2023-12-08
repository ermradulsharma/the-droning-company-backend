<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\Country;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendUserMail;
use App\Mail\SendUserPassMail;
use Laravel\Cashier\Subscription;

class SubscriptionController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::whereHas('subscriptions')
                ->with(['subscriptions'])
                ->latest('id')
                ->get()
                ->map(function ($sub) {
                    $sub->setRelation('subscriptions', $sub->subscriptions->first());
                    return $sub;
                });

        // $sub=Subscription::with('users')->get();

        // dd($users->toArray());
        return view('admin.subscription.index', compact('users'));
    }

   

    
    
   

    public function show($id)
    {
        $user=User::where('id', $id)->first();
        // dd($user);
        $subscription=$user->subscriptions->first();

        $invoices = $user->invoices();


        //dd($invoices->toArray());

        // dd($)
        // $this->getUser=$user;
        // $this->getInvoice=$this->getUser->findInvoice($invoice_id);
        // $this->getSubscription=$this->getUser->subscriptions->first();

        return view('admin.subscription.show', compact('user', 'subscription', 'invoices'));
    }

    public function cancelSubscription(User $user, $subscription){
        //return $user->subscription($subscription);
        $user->subscription($subscription)->cancel();
        return redirect()->back()->with('success', 'Subscription Canceled Successfully');
    }
}
