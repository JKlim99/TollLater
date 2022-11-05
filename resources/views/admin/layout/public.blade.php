<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TollLater (Admin) - @yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">
</head>

<body>

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">TollLater <span class="badge bg-danger">Admin</span></a>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="/admin/logout">Sign out</a>
            </div>
        </div>
    </header>

    @yield('sidebar_active')

    <?php
    $user = false;
    $card = false;

    if($active == 'user')
        $user = true;
    else
        $card = true;
    ?>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link @if($user) active @endif" href="/admin/users">
                                User Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($card) active @endif" href="/admin/cards">
                                Card Management
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('header')</h1>
                    @yield('button')
                </div>

                @if(session('alert_text') ?? false)
                <div class="alert @if(session('alert_status') == 'success') alert-success @else alert-danger @endif alert-dismissible fade show" role="alert">
                    <strong>@if(session('alert_status') == 'success') Success! @else Error! @endif</strong> {{session('alert_text')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script>
        var errorNoted = function () {
            document.getElementById('error').style.display = 'none';
        }
    </script>
</body>

</html>