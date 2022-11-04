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
            $users = UserModel::orderBy('created_at', 'desc')->paginate(1);
        }
        else
        {
            $users = UserModel::where($type, 'LIKE', '%'.$keyword.'%')->orderBy('created_at', 'desc')->paginate(10);
        }

        return view('admin.users')->with(['users'=>$users, 'type'=>$type, 'keyword'=>$keyword]);
    }
}
