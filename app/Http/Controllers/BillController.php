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
        $user_id = session('id');
        $key = session('key');
        $user = UserModel::where('id', $user_id)->where('hash', $key)->first();
        if(!$user){
            return redirect('/logout');
        }
        $name = $user->fullname;
        
        $cards = DB::table('user')
                        ->select(DB::raw('max(bill.id) as id'), 'card.card_serial_no', DB::raw('max(bill.due_date) as due_date'),  DB::raw('sum(bill.amount) as amount'))
                        ->leftJoin('card', 'user.id', '=', 'card.user_id')
                        ->leftJoin('bill', 'card.id', '=', 'bill.card_id')
                        ->where('user.id', $user_id)
                        ->where('card.status', 'active')
                        ->where('bill.status', 'unpaid')
                        ->orderBy('bill.created_at', 'desc')
                        ->groupBy('bill.card_id', 'card.card_serial_no')
                        ->get();

        $penalty = DB::table('user')
                        ->select(DB::raw('max(bill.id) as id'), DB::raw('max(bill.due_date) as due_date'),  DB::raw('sum(bill.amount) as amount'))
                        ->join('bill', 'user.id', '=', 'bill.user_id')
                        ->where('user.id', $user_id)
                        ->whereNull('bill.card_id')
                        ->where('bill.status', 'unpaid')
                        ->orderBy('bill.created_at', 'desc')
                        ->groupBy('bill.user_id')
                        ->first();

        return view('pay')->with(['cards'=>$cards, 'penalty'=>$penalty]);
    }

    public function pay(Request $request)
    {
        $user_id = session('id');
        $key = session('key');
        $user = UserModel::where('id', $user_id)->where('hash', $key)->first();
        if(!$user){
            return redirect('/logout');
        }

        $amount = 0.00;
        $subtotals = [];
        $bill_ids = $request->input('bills', []);
        if(count($bill_ids) == 0)
        {
            return redirect()->back()->with(['error_notice'=>'No bill selected. Please select at least 1 bill,']);
        }

        foreach($bill_ids as $bill_id)
        {
            $subtotal = 0.00;
            $bill = BillModel::where('user_id', $user_id)->where('id', $bill_id)->where('status', 'unpaid')->where('amount', '>', 0.00)->first();
            if(!$bill)
            {
                return redirect()->back()->with(['error_notice'=>'Bill not found.']);
            }
            $subtotal += $bill->amount;

            $unpaid_bills = UnpaidBillModel::where('bill_id', $bill_id)->get();
            foreach($unpaid_bills as $unpaid_bill)
            {
                $subtotal += $unpaid_bill->bill->amount;
            }

            $subtotals[] = $subtotal;
            $amount += $subtotal;
        }

        \Stripe\Stripe::setApiKey(env('stripe_secret_key'));

        $checkout_session = \Stripe\Checkout\Session::create([
            'success_url' => 'http://localhost:8000/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:8000/cancel?session_id={CHECKOUT_SESSION_ID}',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'myr',
                        'product_data' => [
                            'name' => 'TollLater Bill Payment',
                        ],
                        'unit_amount' => $amount*100,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
        ]);

        $i = 0;
        foreach($bill_ids as $bill_id)
        {
            $payment = PaymentModel::create([
                'bill_id' => $bill_id,
                'user_id' => $user_id,
                'ref_id' => $checkout_session->id,
                'amount' => $subtotals[$i],
            ]);

            $i++;
        }

        return redirect($checkout_session->url);
    }

    public function paySuccess(Request $request)
    {
        $stripe_ref_id = $_GET['session_id'] ?? '';
        $payments = PaymentModel::where('ref_id', $stripe_ref_id)->get();

        foreach($payments as $payment)
        {
            $bill = BillModel::where('id', $payment->bill_id)->update(['status'=>'paid']);
            $unpaid_bills = UnpaidBillModel::where('bill_id', $payment->bill_id)->get();
            foreach($unpaid_bills as $unpaid_bill)
            {
                $bill = BillModel::where('id', $unpaid_bill->unpaid_bill_id)->first();
                if($bill)
                {
                    $bill->status = 'paid';
                    $bill->update();
                    $date1 = date_create($bill->due_date);
                    $date2 = date_create(date('Y-m-d'));
                    $diff = date_diff($date1, $date2);
                    $difference = $diff->format("%R%a");
                    if($difference > 0){
                        TransactionModel::create([
                            'card_id' => null,
                            'user_id' => $bill->user_id,
                            'type' => 'penalty',
                            'amount' => $bill->amount * 0.01 * $difference / 30,
                            'toll_station_id' => null,
                            'station_type' => 'late_payment',
                            'car_plate_no' => null
                        ]);
                    }
                }
                
                // TBC: check on due date, if due payment penalize the user on next bill
            }
        }
        PaymentModel::where('ref_id', $stripe_ref_id)->update(['status'=>'success']);

        return view('paySuccess');
    }

    public function payCancel(Request $request)
    {
        $stripe_ref_id = $_GET['session_id'] ?? '';
        if($stripe_ref_id)
        {
            PaymentModel::where('ref_id', $stripe_ref_id)->delete();
        }

        return view('payCancel');
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