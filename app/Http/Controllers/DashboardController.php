<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\BillModel;
use App\Models\CardModel;
use DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user_id = session('id');
        $key = session('key');
        $user = UserModel::where('id', $user_id)->where('hash', $key)->first();
        if(!$user){
            return redirect('/logout');
        }
        $name = $user->fullname;
        
        $cards = DB::table('user')
                        ->select('card.card_serial_no', DB::raw('max(bill.due_date) as due_date'),  DB::raw('sum(bill.amount) as amount'))
                        ->leftJoin('card', 'user.id', '=', 'card.user_id')
                        ->leftJoin('bill', 'card.id', '=', 'bill.card_id')
                        ->where('user.id', $user_id)
                        ->where('card.status', 'active')
                        ->where('bill.status', 'unpaid')
                        ->orderBy('bill.created_at', 'desc')
                        ->groupBy('bill.card_id', 'card.card_serial_no')
                        ->get();

        $penalty = DB::table('user')
                        ->select(DB::raw('max(bill.due_date) as due_date'),  DB::raw('sum(bill.amount) as amount'))
                        ->join('bill', 'user.id', '=', 'bill.user_id')
                        ->where('user.id', $user_id)
                        ->whereNull('bill.card_id')
                        ->where('bill.status', 'unpaid')
                        ->orderBy('bill.created_at', 'desc')
                        ->groupBy('bill.user_id')
                        ->first();
        

        return view('dashboard')->with(['cards'=>$cards, 'penalty'=>$penalty]);
    }

    public function addCard(Request $request)
    {
        $user_id = session('id');
        $key = session('key');
        $user = UserModel::where('id', $user_id)->where('hash', $key)->first();
        if(!$user){
            return redirect('/logout');
        }

        $card_serial_no = $request->input('card_serial_no');
        $card_found = CardModel::where('card_serial_no', $card_serial_no)->whereNull('user_id')->where('status', 'active')->first();
        if(!$card_found)
        {
            return redirect('/dashboard#add-card-modal')->withInput()->with('error', 'Card not found.');
        }

        $card_found->user_id = $user_id;
        $card_found->update();

        BillModel::create([
            'card_id' => $card_found->id,
            'user_id' => $user_id,
            'amount' => 0.00,
            'due_date' => date('Y-m-d'),
        ]);

        return redirect('/dashboard')->with(['notice'=>'Your new card has been added.']);
    }
}
