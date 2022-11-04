<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AdminModel;
use Hash;

class AdminLoginController extends Controller
{
    public function loginPage()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email', null);
        $password = $request->input('password', null);

        $admin = AdminModel::where('email', $email)->first();
        if(!$admin)
        {
            return redirect()->back()->with('message', 'Incorrect Email / Password given. Please try again.')->withInput();
        }

        if(!Hash::check($password.$admin->hash, $admin->secret_key)) //password verification
        {
            return redirect()->back()->with('message', 'Incorrect Email / Password given. Please try again.')->withInput();
        }

        $request->session()->put('id', $admin->id);
        $request->session()->put('type', $admin->type);
        $request->session()->put('key', $admin->hash);

        return redirect('/admin');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/admin');
    }
}
