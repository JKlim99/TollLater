@extends('layout.public')
@section('title', 'Pay Bills')
@section('content')
<?php
$active = 'pay'
?>
<div class="hero bg-base-200 p-10">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <h1 class="text-5xl font-bold">Payment Cancelled</h1>
            <p class="py-6">You have cancelled your payment. You may go back to Pay Bills page to make the payment again.</p>
            <a class="btn btn-primary"  href="/pay">Pay Bills</a>
        </div>
    </div>
</div>
@endsection