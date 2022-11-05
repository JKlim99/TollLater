<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserModel;
use App\Models\CardModel;
use App\Models\BillModel;

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

        $ic_found = UserModel::where('ic_no', $ic_no)->withTrashed()->first();
        if($ic_found)
        {
            return redirect()->back()->withInput()->with('ic_error', 'IC Number existed');
        }

        $email_found = UserModel::where('email', $email)->withTrashed()->first();
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

    public function userDetails($id)
    {
        $user = UserModel::find($id);
        if(!$user)
        {
            return redirect('/admin/users')->with(['alert_status'=>'error', 'alert_text'=>'User not found.']);
        }

        return view('admin.userDetails')->with(['user'=>$user]);
    }

    public function userUpdate(Request $request, $id)
    {
        $ic_no = $request->input('ic_no');
        $fullname = $request->input('fullname');
        $email = $request->input('email');
        $mobile_no = $request->input('mobile_no');
        $password = $request->input('password');

        $ic_found = UserModel::where('ic_no', $ic_no)->where('id', '!=', $id)->withTrashed()->first();
        if($ic_found)
        {
            return redirect()->back()->withInput()->with('ic_error', 'IC Number existed');
        }

        $email_found = UserModel::where('email', $email)->where('id', '!=', $id)->withTrashed()->first();
        if($email_found)
        {
            return redirect()->back()->withInput()->with('email_error', 'Email existed');
        }

        $user = UserModel::where('id', $id)->update([
            'ic_no' => $ic_no,
            'fullname' => $fullname,
            'email' => $email,
            'mobile_no' => $mobile_no
        ]);

        if($password != '')
        {
            UserModel::where('id', $id)->update([
                'secret_key' => $password
            ]);
        }

        return redirect('/admin/user/'.$id)->with(['alert_status'=>'success', 'alert_text'=>'User has updated successfully.']);
    }

    public function userCards(Request $request, $id)
    {
        $user = UserModel::find($id);
        if(!$user)
        {
            return redirect('/admin/users')->with(['alert_status'=>'error', 'alert_text'=>'User not found.']);
        }

        $type = $request->input('type', null);
        $keyword = $request->input('keyword', null);

        if(!$keyword)
        {
            $cards = CardModel::where('user_id', $id)->orderBy('updated_at', 'desc')->paginate(10);
        }
        else
        {
            $cards = CardModel::where('user_id', $id)->where($type, 'LIKE', '%'.$keyword.'%')->orderBy('updated_at', 'desc')->paginate(10);
        }

        return view('admin.userCards')->with(['cards'=>$cards, 'user'=>$user, 'type'=>$type, 'keyword'=>$keyword]);
    }

    public function assignCard(Request $request, $id)
    {
        $user_id = $id;
        $user = UserModel::where('id', $user_id)->first();
        if(!$user)
        {
            return redirect('/admin/users')->with(['alert_status'=>'error', 'alert_text'=>'User not found.']);
        }

        $card_serial_no = $request->input('card_serial_no');
        $card_found = CardModel::where('card_serial_no', $card_serial_no)->whereNull('user_id')->where('status', 'active')->first();
        if(!$card_found)
        {
            return redirect('/admin/ucard/'.$id)->with(['alert_status'=>'error', 'alert_text'=>'Card not found.']);
        }

        $card_found->user_id = $user_id;
        $card_found->update();

        BillModel::create([
            'card_id' => $card_found->id,
            'user_id' => $user_id,
            'amount' => 0.00,
            'due_date' => date('Y-m-d'),
        ]);

        return redirect('/admin/ucard/'.$id)->with(['alert_status'=>'success', 'alert_text'=>'Card has assigned successfully.']);
    }
}
