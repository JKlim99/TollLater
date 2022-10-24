<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TollLater - Login</title>

    @vite('resources/css/app.css')
</head>

<body class="antialiased">
    <div class="hero min-h-screen bg-base-200">
        <div class="hero-content flex-col lg:flex-row">
            <div class="text-center lg:text-left">
                <h1 class="text-5xl font-bold">TollLater</h1>
                <p class="py-6">Haven't register with TollLater? Get started with TollLater now!</p>
				<a class="btn btn-outline btn-primary" href="/register">Register an account</a>
            </div>
            <div class="card flex-shrink-0 w-full max-w-sm shadow-2xl bg-base-100">
                <form method="POST" action="/login">
                    @csrf
                    <div class="card-body">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Email</span>
                            </label>
                            <input type="text" name="email" placeholder="email" class="input input-bordered" onkeyup='errorNoted();' value="{{old('email', null)}}"/>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Password</span>
                            </label>
                            <input type="password" name="password" placeholder="password" class="input input-bordered" onkeyup='errorNoted();'/>
                            @if(session('message'))
                                <label class="label" id="error">
                                    <span class="label-text-alt text-error">{{session('message')}}</span>
                                </label>
                            @endif
                            <label class="label">
                                <a href="#" class="label-text-alt link link-hover">Forgot password?</a>
                            </label>
                        </div>
                        <div class="form-control mt-6">
                            <button class="btn btn-primary">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    var errorNoted = function () {
        document.getElementById('error').style.display = 'none';
    }
</script>
</html>