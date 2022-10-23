<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    @vite('resources/css/app.css')
</head>

<body class="antialiased">
    <div class="hero min-h-screen bg-base-200">
        <div class="hero-content flex-col lg:flex-row">
            <div class="text-center lg:text-left">
                <h1 class="text-5xl font-bold">TollLater</h1>
                <p class="py-6">Already have an account? Login and start using TollLater now!</p>
                <a class="btn btn-outline btn-primary" href="/">Login now</a>
            </div>
            <div class="card flex-shrink-0 w-full max-w-sm shadow-2xl bg-base-100">
                <form method="POST" action="/register">
                    @csrf
                    <div class="card-body">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">IC Number</span>
                                <span class="label-text-alt">without dash (-)</span>
                            </label>
                            <input type="text" name="ic_no" placeholder="IC Number" class="input input-bordered" onkeyup='errorNoted();'
                                onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" pattern="[0-9]{12}" required 
                                value="{{old('ic_no', null)}}"/>
                            @if(session('ic_error'))
                            <label class="label" id="error">
                                <span class="label-text-alt text-error">{{session('ic_error')}}</span>
                            </label>
                            @endif
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Full Name</span>
                            </label>
                            <input type="text" name="fullname" placeholder="Full Name" class="input input-bordered" required value="{{old('fullname', null)}}"/>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Email</span>
                            </label>
                            <input type="email" name="email" placeholder="Email" class="input input-bordered" onkeyup='errorNoted();' required value="{{old('email', null)}}"/>
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
                            onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" pattern="[0-9]+" class="input input-bordered" required value="{{old('mobile_no', null)}}"/>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Password</span>
                            </label>
                            <input type="password" name="password" id="password" placeholder="Password"
                                class="input input-bordered" required/>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Re-enter Password</span>
                            </label>
                            <input type="password" name="c_password" id="r_password" placeholder="Re-enter Password"
                                class="input input-bordered" onkeyup='check();' required/>
                            <label class="label" id="password_mismatch" style="display:none">
                                <span class="label-text-alt text-error">Password mismatch</span>
                            </label>
                        </div>
                        <div class="form-control mt-6">
                            <button class="btn btn-primary" id="button">Register</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

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

</html>