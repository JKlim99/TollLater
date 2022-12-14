@extends('layout.public')
@section('title', 'Dashboard')
@section('content')
<?php
$active = 'dashboard'
?>
<div class="p-2">
    <div class="card bg-primary text-primary-content shadow-lg">
        <div class="card-body">
            <p>Welcome back,</p>
            <h2 class="card-title">Lim Jinq Kuen</h2>
        </div>
    </div>
</div>
<h3 class="font-medium leading-tight text-3xl m-2">My Cards</h3>
@foreach($cards as $card)
<div class="card bg-base-content text-primary-content shadow-xl m-2">
    <div class="card-body">
        <h2 class="card-title">@if($card->name){{$card->name}}@else Card @endif 
            <a href="#edit-card-modal-{{$card->id}}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                    <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                  </svg>
            </a>      
             #{{$card->card_serial_no}}</h2>
        <h4 class="text-right font-medium leading-tight text-2xl">RM{{number_format($card->amount, 2, '.', ',');}}</h4>
        <p class="text-right">@if($card->amount == 0.00) No amount due @else Amount due by
            {{date('d M Y', strtotime($card->due_date));}} @endif</p>
    </div>
</div>
<div class="modal" id="edit-card-modal-{{$card->id}}">
    <div class="modal-box">
        <form method="POST" action="/editcard/{{$card->id}}">
            @csrf
            <h3 class="font-bold text-lg">Name Your Card</h3>
            <p class="py-4">Please enter your preferred card name.</p>
            <input type="text" placeholder="Preferred Card Name" name="name"
                class="input input-bordered w-full max-w-xs" value="{{$card->name}}"/>
            <div class="modal-action">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="#" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endforeach
@if($penalty)
<div class="card bg-error text-primary-content shadow-xl m-2">
    <div class="card-body">
        <h2 class="card-title">PENALTY</h2>
        <h4 class="text-right font-medium leading-tight text-2xl">RM{{number_format($penalty->amount, 2, '.', ',');}}
        </h4>
        <p class="text-right">@if($penalty->amount == 0.00) No amount due @else Amount due by
            {{date('d M Y', strtotime($penalty->due_date));}} @endif</p>
    </div>
</div>
@else
<div class="card bg-error text-primary-content shadow-xl m-2">
    <div class="card-body">
        <h2 class="card-title">PENALTY</h2>
        <h4 class="text-right font-medium leading-tight text-2xl">RM0.00</h4>
        <p class="text-right">No amount due</p>
    </div>
</div>
@endif
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
        <form method="POST" action="/addcard">
            @csrf
            <h3 class="font-bold text-lg">Add New Card</h3>
            <p class="py-4">Please enter your card serial number.</p>
            <input type="text" placeholder="Card serial number" name="card_serial_no"
                class="input input-bordered w-full max-w-xs" onkeyup='errorNoted();'/>
                @if(session('error'))
                <label class="label" id="error">
                    <span class="label-text-alt text-error">{{session('error')}}</span>
                </label>
                @endif
            <div class="modal-action">
                <button type="submit" class="btn btn-primary">Add Card</button>
                <a href="#" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection