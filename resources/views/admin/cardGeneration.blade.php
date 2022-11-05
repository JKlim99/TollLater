@extends('admin.layout.public')
@section('title', 'Card Generation')

@section('sidebar_active')
<?php
$active = 'card';
?>
@endsection

@section('header', 'Card Generation')

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
            <span class="label-text">Batch Number</span>
        </label>
        <input type="text" class="form-control" name="batch_no" required/>
        @if(session('error'))
        <label class="form-label" id="error">
            <span class="label-text-alt text-danger">{{session('error')}}</span>
        </label>
        @endif
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Number of card(s)</span>
        </label>
        <input type="number" min='1' class="form-control" pattern="[0-9]+" name="number" required/>
    </div>
    <div class="mb-3">
        <button class="btn btn-primary float-end" id="button">Generate card(s)</button>
    </div>
</form>
@endsection