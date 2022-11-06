@extends('operator.layout.public')
@section('title', 'Manage Toll Stations')

@section('sidebar_active')
<?php
$active = 'station';
?>
@endsection

@section('header', 'Manage Toll Stations')

@section('button')
<div class="btn-toolbar mb-2 mb-md-0">
    <a type="button" class="btn btn-sm btn-primary" href="/operator/create/station">
        + Create New Station
    </a>
</div>
@endsection

@section('content')
<form>
    <div class="input-group mb-3">
        <select class="form-select" style="max-width:200px" name="type">
            <option value="highway" @if($type=='highway' ) selected @endif>Highway</option>
            <option value="name" @if($type=='name' ) selected @endif>Station Name</option>
            <option value="type" @if($type=='type' ) selected @endif>Toll Type</option>
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
                <th scope="col">Station Name</th>
                <th scope="col">Highway</th>
                <th scope="col">Toll Type</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 0;
            ?>
            @foreach($stations as $station)
            <tr>
                <td>{{$station->id}}</td>
                <td>{{$station->name}}</td>
                <td>{{$station->highway}}</td>
                <td>{{ucfirst(str_replace('_', ' ', $station->type))}}</td>
                <td><a href="/operator/station/{{$station->id}}">Edit</a> | <a href="/operator/delete/station/{{$station->id}}" onclick="return confirm('Are you sure you want to delete the {{$station->name}} station?')">Delete</a></td>
            </tr>
            <?php $count++; ?>
            @endforeach

            @if($count == 0)
            <tr>
                <td colspan="5" class="text-center">
                    No result found.
                </td>
            </tr>
            @endif
        </tbody>
    </table>
    {{ $stations->links('pagination::bootstrap-5') }}
</div>
@endsection