<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profilePage()
    {
        return view('profile');
    }

    public function updateProfile(Request $request)
    {
        $fullname = $request->input('fullname');
        $email = $request->input('email');
        $mobile_no = $request->input('mobile_no');
        $old_password = $request->input('old_password');
        $new_password = $request->input('new_password');
    }
}
