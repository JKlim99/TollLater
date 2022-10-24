@extends('layout.public')
@section('title', 'Pay Bills')
@section('content')
<?php
$active = 'pay'
?>
<h3 class="font-medium leading-tight text-3xl p-2">Pay My Bills</h3>
<p class="m-2">Select your bill(s) to make payment.</p>
<div class="form-control">
    <label class="cursor-pointer">
        <div class="card bg-base-content text-primary-content shadow-xl m-2">
            <div class="card-body">
                <h2 class="card-title"><input type="checkbox" class="checkbox checkbox-primary bg-base-100" />Card #
                    123412341234
                </h2>
                <h4 class="text-right font-medium leading-tight text-2xl">RM45.20</h4>
                <p class="text-right">Amount due by 14 Aug 2022</p>
            </div>
        </div>
    </label>
</div>
<div class="form-control">
    <label class="cursor-pointer">
        <div class="card bg-error text-primary-content shadow-xl m-2">
            <div class="card-body">
                <h2 class="card-title"><input type="checkbox" class="checkbox checkbox-primary bg-base-100" />PENALTY
                </h2>
                <h4 class="text-right font-medium leading-tight text-2xl">RM0.00</h4>
                <p class="text-right">Amount due by 14 Aug 2022</p>
            </div>
        </div>
    </label>
</div>
<div class="card glass m-2 lg:card-side ">
    <div class="card-body">
        <p class="">Total Amount</p>
        <h4 class="font-medium leading-tight text-2xl">RM0.00</h4>
    </div>
    <div class="card-body">
        <div class="card-actions justify-end">
            <button class="btn btn-primary">Pay Now</button>
        </div>
    </div>
</div>
@endsection