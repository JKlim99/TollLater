<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;

class RegisterController extends Controller
{
    public function registerPage()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $ic_no = $request->input('ic_no');
        $fullname = $request->input('fullname');
        $email = $request->input('email');
        $mobile_no = $request->input('mobile_no');
        $password = $request->input('password');

        $ic_found = UserModel::where('ic_no', $ic_no)->first();
        if($ic_found)
        {
            return redirect()->back()->withInput()->with('ic_error', 'IC Number existed');
        }

        $email_found = UserModel::where('email', $email)->first();
        if($email_found)
        {
            return redirect()->back()->withInput()->with('email_error', 'Email existed');
        }

        $user = UserModel::create([
            'ic_no' => $ic_no,
            'fullname' => $fullname,
            'email' => $email,
            'mobile_no' => $mobile_no,
            'secret_key' => $password
        ]);

        
        return redirect('/');
    }
}
