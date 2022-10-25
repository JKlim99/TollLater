<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserModel;
use Hash;

class ProfileController extends Controller
{
    public function profilePage()
    {
        $user_id = session('id');
        $key = session('key');
        $user = UserModel::where('id', $user_id)->where('hash', $key)->first();
        if(!$user){
            return redirect('/logout');
        }

        return view('profile')->with(['user'=>$user]);
    }

    public function updateProfile(Request $request)
    {
        $user_id = session('id');
        $key = session('key');
        $user = UserModel::where('id', $user_id)->where('hash', $key)->first();
        if(!$user){
            return redirect('/logout');
        }

        $fullname = $request->input('fullname');
        $email = $request->input('email');
        $mobile_no = $request->input('mobile_no');
        $old_password = $request->input('old_password');
        $new_password = $request->input('new_password');

        if($old_password != '' && !Hash::check($old_password.$user->hash, $user->secret_key)) //password verification
        {
            return redirect()->back()->with('error', 'Incorrect Current Password given. Please try again.')->withInput();
        }

        if($user->fullname != $fullname)
        {
            $user->fullname = $fullname;
        }
        if($user->email != $email)
        {
            $email_found = UserModel::where('email', $email)->where('id', '!=', $user_id)->first();
            if($email_found)
            {
                return redirect()->back()->withInput()->with('email_error', 'Email existed');
            }
            $user->email = $email;
        }
        if($user->mobile_no != $mobile_no)
        {
            $user->mobile_no = $mobile_no;
        }
        if($new_password != '')
        {
            $user->secret_key = $new_password;
        }

        $user->update();

        return redirect('/profile')->with(['notice'=>'Your profile has been updated.']);
    }
}
