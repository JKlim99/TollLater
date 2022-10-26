<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\CardModel;
use App\Models\BillModel;
use App\Models\UnpaidBillModel;
use App\Models\PaymentModel;
use App\Models\TransactionModel;

use Carbon\Carbon;
use PDF;
use DB;

class BillController extends Controller
{
    public function payPage()
    {
        return view('pay');
    }

    public function billPage(Request $request)
    {
        $user_id = session('id');
        $key = session('key');
        $user = UserModel::where('id', $user_id)->where('hash', $key)->first();
        if(!$user){
            return redirect('/logout');
        }
        $card_serial_no = $request->input('card_serial_no', null);
        $cards = CardModel::where('user_id', $user_id)->where('status', 'active')->get();
        
        if(count($cards) < 1 || $card_serial_no == 'penalty')
        {
            $bills = BillModel::where('user_id', $user_id)->whereNull('card_id')->orderBy('created_at', 'desc')->get();
            return view('bills')->with(['bills'=>$bills, 'cards'=>$cards, 'card_serial_no'=>$card_serial_no]);
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

        $bills = BillModel::where('user_id', $user_id)->where('card_id', $selected_card->id)->orderBy('created_at', 'desc')->get();

        return view('bills')->with(['bills'=>$bills, 'cards'=>$cards, 'card_serial_no'=>$card_serial_no]);
    }

    public function receiptPage(Request $request)
    {
        $user_id = session('id');
        $key = session('key');
        $user = UserModel::where('id', $user_id)->where('hash', $key)->first();
        if(!$user){
            return redirect('/logout');
        }
        $card_serial_no = $request->input('card_serial_no', null);
        $cards = CardModel::where('user_id', $user_id)->where('status', 'active')->get();
        
        if(count($cards) < 1 || $card_serial_no == 'penalty')
        {
            $payments = DB::table('payment')
                            ->select('payment.created_at', 'payment.status', 'payment.amount', 'payment.id')
                            ->join('bill', 'payment.bill_id', '=', 'bill.id')
                            ->where('payment.status', 'success')
                            ->where('bill.user_id', $user_id)
                            ->whereNull('bill.card_id')
                            ->orderBy('bill.created_at', 'desc')
                            ->get();

            return view('receipts')->with(['payments'=>$payments, 'cards'=>$cards, 'card_serial_no'=>$card_serial_no]);
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

        $payments =  DB::table('payment')
                            ->select('payment.created_at', 'payment.status', 'payment.amount', 'payment.id')
                            ->join('bill', 'payment.bill_id', '=', 'bill.id')
                            ->where('payment.status', 'success')
                            ->where('bill.user_id', $user_id)
                            ->where('bill.card_id', $selected_card->id)
                            ->orderBy('bill.created_at', 'desc')
                            ->get();

        return view('receipts')->with(['payments'=>$payments, 'cards'=>$cards, 'card_serial_no'=>$card_serial_no]);
    }

    public function pdfBill($id)
    {
        $user_id = session('id');
        $key = session('key');
        $user = UserModel::where('id', $user_id)->where('hash', $key)->first();
        if(!$user){
            return redirect('/logout');
        }

        $bill = BillModel::where('user_id', $user_id)->where('id', $id)->first();
        $unpaid_bills = UnpaidBillModel::where('bill_id', $id)->get();
        $card = CardModel::where('id', $bill->card_id)->first();

        $end_date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m', strtotime($bill->created_at)).'-14 23:59:59');
        $start_date = $end_date->copy()->addMonths(-1)->addDay();
        $start_date->hour(0);
        $start_date->minute(0);
        $start_date->second(0);

        if($bill->card_id == null)
        {
            $transactions = TransactionModel::whereNull('card_id')->whereBetween('created_at', [$start_date, $end_date])->where('user_id', $user_id)->get();
        }
        else
        {
            $transactions = TransactionModel::where('card_id', $bill->card_id)->whereBetween('created_at', [$start_date, $end_date])->where('user_id', $user_id)->get();
        }

        $pdf = PDF::loadView('pdf.bill', [
            'bill' => $bill,
            'user' => $user,
            'card' => $card,
            'unpaid_bills' => $unpaid_bills,
            'transactions' => $transactions
        ]);

        return $pdf->stream('tolllater-bill-'.$bill->id.'.pdf');
    }

    public function pdfReceipt($id)
    {
        $user_id = session('id');
        $key = session('key');
        $user = UserModel::where('id', $user_id)->where('hash', $key)->first();
        if(!$user){
            return redirect('/logout');
        }

        $payment = PaymentModel::where('user_id', $user_id)->where('id', $id)->first();

        $pdf = PDF::loadView('pdf.receipt', [
            'user' => $user,
            'payment' => $payment
        ]);

        return $pdf->stream('tolllater-receipt-'.$payment->id.'.pdf');
    }
}
