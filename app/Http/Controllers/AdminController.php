<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserModel;
use App\Models\CardModel;
use App\Models\BillModel;

class AdminController extends Controller
{
    /* User Management */
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

    public function userBills(Request $request, $id)
    {
        $user_id = $id;
        $user = UserModel::find($user_id);
        if(!$user)
        {
            return redirect('/admin/users')->with(['alert_status'=>'error', 'alert_text'=>'User not found.']);
        }

        $card_serial_no = $request->input('card_serial_no', null);
        $cards = CardModel::where('user_id', $user_id)->where('status', 'active')->get();
        
        if(count($cards) < 1 || $card_serial_no == 'penalty')
        {
            $bills = BillModel::where('user_id', $user_id)->whereNull('card_id')->orderBy('created_at', 'desc')->paginate(10);
            return view('admin.userBills')->with(['bills'=>$bills, 'cards'=>$cards, 'card_serial_no'=>$card_serial_no, 'user'=>$user]);
        }

        if(!$card_serial_no)
        {
            $card_serial_no = $cards[0]->card_serial_no;
        }
        $selected_card = CardModel::where('card_serial_no', $card_serial_no)->where('user_id', $user_id)->first();
        
        if(!$selected_card)
        {
            $card_serial_no = $cards[0]->card_serial_no;
            $selected_card = CardModel::where('card_serial_no', $card_serial_no)->where('user_id', $user_id)->first();
        }

        $bills = BillModel::where('user_id', $user_id)->where('card_id', $selected_card->id)->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.userBills')->with(['bills'=>$bills, 'cards'=>$cards, 'card_serial_no'=>$card_serial_no, 'user'=>$user]);
    }

    /* Card management */
    public function cardList(Request $request)
    {
        $type = $request->input('type', null);
        $keyword = $request->input('keyword', null);

        if(!$keyword)
        {
            $cards = CardModel::orderBy('created_at', 'desc')->paginate(10);
        }
        else
        {
            $cards = CardModel::where($type, 'LIKE', '%'.$keyword.'%')->orderBy('created_at', 'desc')->paginate(10);
        }

        return view('admin.cards')->with(['cards'=>$cards, 'type'=>$type, 'keyword'=>$keyword]);
    }

    public function cardDetails($id)
    {
        $card = CardModel::find($id);
        if(!$card)
        {
            return redirect('/admin/cards')->with(['alert_status'=>'error', 'alert_text'=>'Card not found.']);
        }

        return view('admin.cardDetails')->with(['card'=>$card]);
    }

    public function updateCard(Request $request, $id)
    {
        $card = CardModel::find($id);
        if(!$card)
        {
            return redirect('/admin/cards')->with(['alert_status'=>'error', 'alert_text'=>'Card not found.']);
        }

        $card_serial_no = $request->input('card_serial_no', null);
        $status = $request->input('status', null);
        
        if($card_serial_no)
        {
            $card_found = CardModel::where('card_serial_no', $card_serial_no)->where('id', '!=', $id)->first();
            if($card_found)
            {
                return redirect('/admin/card/'.$id)->with(['error'=>'Card Serial Number existed.']);
            }
            $card->card_serial_no = $card_serial_no;
        }

        $card->status = $status;
        $card->update();
        
        return redirect('/admin/card/'.$id)->with(['alert_status'=>'success', 'alert_text'=>'Card has updated successfully.']);
    }

    public function cardGenerationPage()
    {
        return view('admin.cardGeneration');
    }

    public function generateCard(Request $request)
    {
        $batch_no = $request->input('batch_no', null);
        $number = $request->input('number', 1);
        if(!$batch_no)
        {
            return redirect('/admin/generate/card')->with(['error'=>'Batch number is necessary.']);
        }

        $last_card = CardModel::orderBy('created_at', 'desc')->first();
        if(!$last_card)
        {
            $card_serial_no = 10000000;
        }
        else
        {
            $card_serial_no = $last_card->card_serial_no;
        }

        for($i=0; $i<$number; $i++)
        {
            $card_serial_no++;
            CardModel::create([
                'card_serial_no'=>$card_serial_no,
                'batch_no'=>$batch_no,
                'status'=>'active'
            ]);
        }

        return redirect('/admin/cards')->with(['alert_status'=>'success', 'alert_text'=>'Cards have generated successfully']);
    }
}
