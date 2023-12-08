@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="color:#000000;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:14px;line-height:24px;font-weight:700;">
                Subscription Detail
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <b>Name</b><br> {{$user->name}}
                    </div>
                    <div class="col-md-4">
                        <b>Email </b><br>{{$user->email}}
                    </div>
                    <div class="col-md-4">
                        <b>Subscription Id</b><br>  {{ $subscription->stripe_id ?? '' }}
                    </div> <hr>
                </div>
                <div class="row" style="padding-top: 20px ;">
                    <div class="col-md-4">
                        <b> Payment Method </b><br>  {{ $user->pm_type ?? '' }} {{ $user->pm_last_four ?? '' }}
                    </div>
                    <div class="col-md-4">
                        <b> Subscription Status</b><br>  {{ $subscription->stripe_status ?? '' }}
                    </div>
                    @if($subscription->ends_at)
                    <div class="col-md-4">
                        <b> Subscription Ends On</b><br>  {{ $subscription->ends_at ?? '' }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="color:#000000;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:14px;line-height:24px;font-weight:700;">
                Subscription Summary
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Plan Name</th>
                            <th scope="col">Duration</th>
                            <th scope="col">SubTotal</th>
                            <th scope="col">Discount</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col">Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $key=>$invoice)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$subscription->name}} </td>
                            <td>

                                {{\Carbon\Carbon::createFromTimestamp(@$invoice->lines->data[0]->period->start)->format('Y-m-d')}} - <br />
                            {{\Carbon\Carbon::createFromTimestamp(@$invoice->lines->data[0]->period->end)->format('Y-m-d')}}</td>
                            <td>{{$invoice->subtotal()}}</td>
                            <td>{{$invoice->discount()}}</td>
                            <td>{{$invoice->total()}}</td>
                            <td><a target="_blank" href="{{$invoice->invoice_pdf}}"><i class="fa fa-download" aria-hidden="true"></i></a></td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
