<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserModel;
use Hash;

class LoginController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email', null);
        $password = $request->input('password', null);

        $user = UserModel::where('email', $email)->first();
        if(!$user)
        {
            return redirect()->back()->with('message', 'Incorrect Email / Password given. Please try again.')->withInput();
        }

        if(!Hash::check($password.$user->hash, $user->secret_key)) //password verification
        {
            return redirect()->back()->with('message', 'Incorrect Email / Password given. Please try again.')->withInput();
        }

        $request->session()->put('id', $user->id);
        $request->session()->put('type', 'user');
        $request->session()->put('key', $user->hash);

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }
}
