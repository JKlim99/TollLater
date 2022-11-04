@extends('admin.layout.public')
@section('title', 'User Creation')

@section('sidebar_active')
<?php
$active = 'user';
?>
@endsection

@section('header', 'Create User')

@section('content')
<form method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">IC Number</span>
        </label>
        <input type="text" name="ic_no" placeholder="IC Number" class="form-control" onkeyup='errorNoted();'
            onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" pattern="[0-9]{12}" required 
            value="{{old('ic_no', null)}}"/>
        @if(session('ic_error'))
        <label class="form-label" id="error">
            <span class="label-text-alt text-danger">{{session('ic_error')}}</span>
        </label>
        @endif
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Full Name</span>
        </label>
        <input type="text" name="fullname" placeholder="Full Name" class="form-control" required value="{{old('fullname', null)}}"/>
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Email</span>
        </label>
        <input type="email" name="email" placeholder="Email" class="form-control" onkeyup='errorNoted();' required value="{{old('email', null)}}"/>
        @if(session('email_error'))
        <label class="form-label" id="error">
            <span class="label-text-alt text-danger">{{session('email_error')}}</span>
        </label>
        @endif
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Mobile Number</span>
        </label>
        <input type="text" name="mobile_no" placeholder="Mobile Number"
        onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" pattern="[0-9]+" class="form-control" required value="{{old('mobile_no', null)}}"/>
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Password</span>
        </label>
        <input type="password" name="password" id="password" placeholder="Password"
            class="form-control" onkeyup='check();' required/>
    </div>
    <div class="mb-3">
        <label class="form-label">
            <span class="label-text">Re-enter Password</span>
        </label>
        <input type="password" name="c_password" id="r_password" placeholder="Re-enter Password"
            class="form-control" onkeyup='check();' required/>
        <label class="form-label" id="password_mismatch" style="display:none">
            <span class="label-text-alt text-danger">Password mismatch</span>
        </label>
    </div>
    <div class="mb-3">
        <a href="/admin/users">back to list</a>
        <button class="btn btn-primary float-end" id="button">Register</button>
    </div>
</form>

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

    var errorNoted = function () {
        document.getElementById('error').style.display = 'none';
    }
</script>
@endsection