@extends('admin.layout.public')
@section('title', 'User Cards')

@section('sidebar_active')
<?php
$active = 'user';
?>
@endsection

@section('header', 'User Cards')

@section('button')
<div class="btn-toolbar mb-2 mb-md-0">
    <a type="button" class="btn btn-sm btn-secondary" href="/admin/users">
        Back to list
    </a>
</div>
@endsection

@section('content')
<?php $tab_active = 'card'; ?>
@include('admin.layout.userTab')

<form method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            Card Assignment
        </div>
        <div class="card-body">
            Please enter the card serial number.
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="card_serial_no" placeholder="Card serial number"/>
                <button class="btn btn-primary" type="submit" onclick="return confirm('Are you sure you want to add the card to this user?')">Assign Card</button>
            </div>
        </div>
    </div>
</form>
<br/>
<form>
    <div class="input-group mb-3">
        <select class="form-select" style="max-width:200px" name="type">
            <option value="card_serial_no" @if($type=='card_serial_no' ) selected @endif>Card serial no.</option>
            <option value="status" @if($type=='status' ) selected @endif>Status</option>
        </select>
        <input type="text" class="form-control" name="keyword" value="{{$keyword}}"
            aria-label="Text input with dropdown button">
        <button class="btn btn-primary" type="submit">Search</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Card serial no.</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 0;
            ?>
            @foreach($cards as $card)
            <tr>
                <td>{{$card->id}}</td>
                <td>{{$card->card_serial_no}}</td>
                <td>{{ucfirst($card->status)}}</td>
                <td><a href="/admin/card/{{$card->id}}">Edit</a></td>
            </tr>
            <?php $count++; ?>
            @endforeach

            @if($count == 0)
            <tr>
                <td colspan="4" class="text-center">
                    No result found.
                </td>
            </tr>
            @endif
        </tbody>
    </table>
    {{ $cards->links('pagination::bootstrap-5') }}
</div>
@endsection