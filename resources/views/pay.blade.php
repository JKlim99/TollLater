@extends('layout.public')
@section('title', 'Pay Bills')
@section('content')
<?php
$active = 'pay'
?>
<h3 class="font-medium leading-tight text-3xl p-2">Pay My Bills</h3>
<p class="m-2">Select your bill(s) to make payment.</p>
<form method="POST" action="/pay">
    @csrf
    @foreach($cards as $card)
        @if($card->amount > 0.00)
        <div class="form-control">
            <label class="cursor-pointer">
                <div class="card bg-base-content text-primary-content shadow-xl m-2">
                    <div class="card-body">
                        <h2 class="card-title"><input type="checkbox" name="bills[]" class="checkbox checkbox-primary bg-base-100" price="{{number_format($card->amount, 2, '.', ',');}}" onclick="calculateTotal()" value="{{$card->id}}"/>Card # {{$card->card_serial_no}}</h2>
                        <h4 class="text-right font-medium leading-tight text-2xl">RM{{number_format($card->amount, 2, '.', ',');}}</h4>
                        <p class="text-right">Amount due by {{date('d M Y', strtotime($card->due_date));}}</p>
                    </div>
                </div>
            </label>
        </div>
        @endif
    @endforeach
    @if($penalty)
        @if($penalty->amount > 0.00)
        <div class="form-control">
            <label class="cursor-pointer">
                <div class="card bg-error text-primary-content shadow-xl m-2">
                    <div class="card-body">
                        <h2 class="card-title"><input type="checkbox" name="bills[]" class="checkbox checkbox-primary bg-base-100" price="{{number_format($penalty->amount, 2, '.', ',');}}" onclick="calculateTotal()" value="{{$penalty->id}}"/>PENALTY</h2>
                        <h4 class="text-right font-medium leading-tight text-2xl">RM{{number_format($penalty->amount, 2, '.', ',');}}
                        </h4>
                        <p class="text-right">Amount due by {{date('d M Y', strtotime($penalty->due_date));}}</p>
                    </div>
                </div>
            </label>
        </div>
        @endif
    @endif
    <div class="card glass m-2 lg:card-side ">
        <div class="card-body">
            <p class="">Total Amount</p>
            <h4 class="font-medium leading-tight text-2xl" id="total">RM0.00</h4>
        </div>
        <div class="card-body">
            <div class="card-actions justify-end">
                <button class="btn btn-primary">Pay Now</button>
            </div>
        </div>
    </div>
</form>

<script>
    var calculateTotal = function () {
        let bills = document.getElementsByName('bills[]');
        var total = 0.00;
        bills.forEach((bill) => {
            if (bill.checked) {
                total += parseFloat(bill.getAttribute('price'));
            }
        });

        let totalLabel = document.getElementById('total');
        totalLabel.innerHTML = 'RM'+(Math.round(total * 100) / 100).toFixed(2);
    }
</script>
@endsection