@extends('admin.layout.public')
@section('title', 'Card Management')

@section('sidebar_active')
<?php
$active = 'card';
?>
@endsection

@section('header', 'Card Management')

@section('button')
<div class="btn-toolbar mb-2 mb-md-0">
    <a type="button" class="btn btn-sm btn-primary" href="/admin/generate/card">
        + Generate New Card
    </a>
</div>
@endsection

@section('content')
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
                <th scope="col">Card serial no.</th>
                <th scope="col">Card Owner</th>
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
                <td>{{$card->card_serial_no}}</td>
                <td>{{$card->user ? $card->user->fullname : '-'}}</td>
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