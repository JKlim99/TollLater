<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TollLater - @yield('title')</title>

    @vite('resources/css/app.css')
</head>

<body class="antialiased">
    <div class="navbar bg-base-100 sticky top-0 z-50 shadow-md">
        <div class="flex-1">
            <a class="btn btn-ghost normal-case text-xl">TollLater</a>
        </div>
        <div class="flex-none">
            <a class="btn btn-square btn-ghost" href="#logout-modal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
            </a>
        </div>
        <div class="modal" id="logout-modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg">Logout Confirmation</h3>
                <p class="py-4">Are you sure you want to logout?</p>
                <div class="modal-action">
                    <a href="/logout" class="btn btn-primary">Proceed to logout</a>
                    <a href="#" class="btn">Cancel</a>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-base-200 min-h-screen">
        @yield('content')
    </div>
    <?php
    $dashboard = false;
    $pay = false;
    $bill = false;
    $profile = false;

    if($active == 'dashboard')
        $dashboard = true;
    else if($active == 'pay')
        $pay = true;
    else if($active == 'bill')
        $bill = true;
    else
        $profile = true;
    ?>
    <div class="btm-nav btm-nav-lg">
        <a class="@if($dashboard) text-primary active @endif hover:bg-gray-200" href="/dashboard">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="btm-nav-label">Dashboard</span>
        </a>
        <a class="@if($pay) text-primary active @endif hover:bg-gray-200" href="/pay">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
            </svg>

            <span class="btm-nav-label">Pay Bills</span>
        </a>
        <a class="@if($bill) text-primary active @endif hover:bg-gray-200" href="/bills">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
            </svg>
            <span class="btm-nav-label">My Bills</span>
        </a>
        <a class="@if($profile) text-primary active @endif hover:bg-gray-200" href="/profile">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            <span class="btm-nav-label">Profile</span>
        </a>
    </div>
</body>

</html>