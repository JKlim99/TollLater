@extends('layout.public')
@section('title', 'Profile')
@section('content')
<?php
$active = 'profile'
?>
<h3 class="font-medium leading-tight text-3xl p-2">My Profile</h3>
<p class="m-2">ID: {{$user->ic_no}}</p>
<div class="m-2">
    <form method="POST" action="/profile">
        @csrf
        <div class="form-control">
            <label class="label">
                <span class="label-text">Full Name</span>
            </label>
            <input type="text" name="fullname" placeholder="Full Name" class="input input-bordered" required value="{{ old('fullname', $user->fullname)}}"/>
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Email</span>
            </label>
            <input type="email" name="email" placeholder="Email" class="input input-bordered" onkeyup='errorNoted();' required value="{{old('email', $user->email)}}"/>
            @if(session('email_error'))
            <label class="label" id="error">
                <span class="label-text-alt text-error">{{session('email_error')}}</span>
            </label>
            @endif
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Mobile Number</span>
                <span class="label-text-alt">without dash (-)</span>
            </label>
            <input type="text" name="mobile_no" placeholder="Mobile Number"
            onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" pattern="[0-9]+" class="input input-bordered" required value="{{old('mobile_no', $user->mobile_no)}}"/>
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Current Password</span>
            </label>
            <input type="password" name="old_password" id="old_password" placeholder="Current Password"
                class="input input-bordered" onkeyup='checkCurrentPassword();'/>
            @if(session('error'))
            <label class="label" id="error">
                <span class="label-text-alt text-error">{{session('error')}}</span>
            </label>
            @endif
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">New Password</span>
            </label>
            <input type="password" name="new_password" id="password" placeholder="New Password"
                class="input input-bordered" onkeyup='check();'/>
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Re-enter New Password</span>
            </label>
            <input type="password" name="c_password" id="r_password" placeholder="Re-enter New Password"
                class="input input-bordered" onkeyup='check();'/>
            <label class="label" id="password_mismatch" style="display:none">
                <span class="label-text-alt text-error">Password mismatch</span>
            </label>
        </div>
        <div class="form-control mt-6">
            <button class="btn btn-primary" id="button">Update profile</button>
        </div>
    </form>
</div>
<script>
    var check = function () {
        if (document.getElementById('password').value ==
            document.getElementById('r_password').value) {
            document.getElementById('password_mismatch').style.display = 'none';
            document.getElementById('button').disabled = '';
        } else {
            document.getElementById('password_mismatch').style.display = 'block';
            document.getElementById('button').disabled = 'disabled';
        }
    }

    var checkCurrentPassword = function () {
        if ((document.getElementById('password').value != '' &&
            document.getElementById('r_password').value != '') || 
            document.getElementById('old_password').value == '') {
            document.getElementById('password_mismatch').style.display = 'none';
            document.getElementById('button').disabled = '';
        } else {
            document.getElementById('password_mismatch').style.display = 'block';
            document.getElementById('button').disabled = 'disabled';
        }
    }

    var errorNoted = function () {
        document.getElementById('error').style.display = 'none';
    }
</script>
@endsection