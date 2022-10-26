@extends('layout.public')
@section('title', 'Payment Receipts')
@section('content')
<?php
$active = 'bill'
?>
<h3 class="font-medium leading-tight text-3xl p-2">My Bills</h3>
<p class="m-2">You can check your past payment here.</p>
<div class="flex">
    <div class="flex-1 form-control m-2">
        <a class="btn" href="/bills">Billing Statement</a>
    </div>
    <div class="flex-1 form-control m-2">
        <a class="btn btn-primary" href="/receipts">Payment Receipt</a>
    </div>
</div>
<div class="m-2">
    <form class="form-control">
        <select class="select select-bordered" name="card_serial_no" onchange="this.form.submit()">
            @foreach($cards as $card)
            <option value="{{$card->card_serial_no}}" @if($card_serial_no == $card->card_serial_no) selected @endif>Card # {{$card->card_serial_no}}</option>
            @endforeach
            <option value="penalty" @if($card_serial_no == 'penalty') selected @endif>Penalty</option>
        </select>
    </form>
</div>
<div class="m-2 shadow-md border-solid border-2 rounded-md border-base-300">
    <table class="table w-full">
        <!-- head -->
        <thead>
            <tr>
                <th>Payment Date</th>
                <th>Paid Amount</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{date('d M Y', strtotime($payment->created_at));}}</td>
                <td>RM{{number_format($payment->amount, 2, '.', ',');}}</td>
                <td>
                    <a target="_blank" href="/pdf/receipt/{{$payment->id}}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                          </svg>                          
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection