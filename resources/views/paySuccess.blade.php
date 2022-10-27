@extends('layout.public')
@section('title', 'Pay Bills')
@section('content')
<?php
$active = 'pay'
?>
<div class="hero bg-base-200 p-10">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <h1 class="text-5xl font-bold">Payment Success</h1>
            <p class="py-6">You have completed your payment. You may go to My Bills page to get your payment receipt.</p>
            <a class="btn btn-primary"  href="/receipts">My Bills</a>
        </div>
    </div>
</div>
@endsection