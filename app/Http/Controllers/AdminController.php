<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserModel;

class AdminController extends Controller
{
    public function userList(Request $request)
    {
        $type = $request->input('type', null);
        $keyword = $request->input('keyword', null);

        if(!$keyword)
        {
            $users = UserModel::orderBy('created_at', 'desc')->paginate(10);
        }
        else
        {
            $users = UserModel::where($type, 'LIKE', '%'.$keyword.'%')->orderBy('created_at', 'desc')->paginate(10);
        }

        return view('admin.users')->with(['users'=>$users, 'type'=>$type, 'keyword'=>$keyword]);
    }

    public function createUserPage()
    {
        return view('admin.userCreate');
    }

    public function createUser(Request $request)
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

        
        return redirect('/admin/users')->with(['alert_status'=>'success', 'alert_text'=>'User has created successfully.']);
    }

    public function deleteUser($id)
    {
        $user = UserModel::find($id);
        if(!$user)
        {
            return redirect('/admin/users')->with(['alert_status'=>'error', 'alert_text'=>'User not found.']);
        }

        $user->delete();

        return redirect('/admin/users')->with(['alert_status'=>'success', 'alert_text'=>'User has deleted successfully.']);
    }
}
