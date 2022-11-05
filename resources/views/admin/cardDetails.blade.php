@extends('admin.layout.public')
@section('title', 'Card Details')

@section('sidebar_active')
<?php
$active = 'card';
?>
@endsection

@section('header', 'Card Details')

@section('button')
<div class="btn-toolbar mb-2 mb-md-0">
    <a type="button" class="btn btn-sm btn-secondary" href="/admin/cards">
        Back to list
    </a>
</div>
@endsection

@section('content')
<form method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Card Serial Number</span>
        </label>
        <input type="text" name="card_serial_no" placeholder="Card Serial Number" class="form-control" onkeyup='errorNoted();'
            pattern="[0-9]+" required value="{{old('card_serial_no', $card->card_serial_no)}}" required/>
        @if(session('error'))
        <label class="form-label" id="error">
            <span class="label-text-alt text-danger">{{session('error')}}</span>
        </label>
        @endif
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Batch Number</span>
        </label>
        <input type="text" class="form-control" required
            value="{{$card->batch_no}}" disabled/>
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Card Owner</span>
        </label>
        <input type="text" class="form-control" required
            value="{{$card->user ? $card->user->fullname : '-'}}" disabled/>
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Status</span>
        </label>
        <select class="form-control" name="status">
            <option value="active" @if($card->status=='active') selected @endif>Active</option>
            <option value="suspend" @if($card->status=='suspend') selected @endif>Suspend</option>
        </select>
    </div>
    <div class="mb-3">
        <button class="btn btn-primary float-end" id="button">Update</button>
    </div>
</form>
@endsection