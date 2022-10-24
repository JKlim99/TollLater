@extends('layout.public')
@section('title', 'Dashboard')
@section('content')
<?php
$active = 'dashboard'
?>
<div class="p-2">
    <div class="card bg-primary text-primary-content shadow-lg">
        <div class="card-body">
            <h2 class="card-title">Welcome back,</h2>
            <p>Lim Jinq Kuen</p>
        </div>
    </div>
</div>
<h3 class="font-medium leading-tight text-3xl m-2">My Cards</h3>
<div class="card bg-base-content text-primary-content shadow-xl m-2">
    <div class="card-body">
        <h2 class="card-title">Card # 123412341234</h2>
        <h4 class="text-right font-medium leading-tight text-2xl">RM45.20</h4>
        <p class="text-right">Amount due by 14 Aug 2022</p>
    </div>
</div>
<div class="card bg-error text-primary-content shadow-xl m-2">
    <div class="card-body">
        <h2 class="card-title">PENALTY</h2>
        <h4 class="text-right font-medium leading-tight text-2xl">RM0.00</h4>
        <p class="text-right">Amount due by 14 Aug 2022</p>
    </div>
</div>
<a href="#add-card-modal">
    <div
        class="card bg-neutral text-primary-content shadow-xl m-2 hover:-translate-y-1 hover:bg-neutral-focus duration-300 transition ease-in-out delay-150">
        <div class="card-body">
            <h2 class="card-title text-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Card</h2>
        </div>
    </div>
</a>
<div class="modal" id="add-card-modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Add New Card</h3>
        <p class="py-4">Please enter your card serial number.</p>
        <input type="number" placeholder="Card serial number" class="input input-bordered w-full max-w-xs" />
        <div class="modal-action">
            <a href="/addcard" class="btn btn-primary">Add Card</a>
            <a href="#" class="btn">Cancel</a>
        </div>
    </div>
</div>
@endsection