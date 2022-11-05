@extends('admin.layout.public')
@section('title', 'User Management')

@section('sidebar_active')
<?php
$active = 'user';
?>
@endsection

@section('header', 'User Management')

@section('button')
<div class="btn-toolbar mb-2 mb-md-0">
    <a type="button" class="btn btn-sm btn-primary" href="/admin/create/user">
        + Create New User
    </a>
</div>
@endsection

@section('content')
<form>
    <div class="input-group mb-3">
        <select class="form-select" style="max-width:200px" name="type">
            <option value="fullname" @if($type=='fullname' ) selected @endif>Full Name</option>
            <option value="ic_no" @if($type=='ic_no' ) selected @endif>IC No.</option>
            <option value="email" @if($type=='email' ) selected @endif>E-mail</option>
            <option value="mobile_no" @if($type=='mobile_no' ) selected @endif>Phone Number</option>
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
                <th scope="col">Full Name</th>
                <th scope="col">E-mail</th>
                <th scope="col">Created On</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 0;
            ?>
            @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->fullname}}</td>
                <td>{{$user->email}}</td>
                <td>{{date('d M Y, h:iA', strtotime($user->created_at));}}</td>
                <td><a href="/admin/user/{{$user->id}}">Edit</a> | <a href="/admin/delete/user/{{$user->id}}" onclick="return confirm('Are you sure you want to delete the {{$user->fullname}} user?')">Delete</a></td>
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
    {{ $users->links('pagination::bootstrap-5') }}
</div>
@endsection