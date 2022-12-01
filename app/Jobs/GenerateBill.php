<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\TransactionModel;
use App\Models\BillModel;
use App\Models\UnpaidBillModel;
use Carbon\Carbon;

class GenerateBill implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id;
    public $card_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $card_id = null)
    {
        $this->user_id = $user_id;
        $this->card_id = $card_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $end_date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m').'-14 23:59:59');
        $start_date = $end_date->copy()->addMonths(-1)->addDay();
        $start_date->hour(0);
        $start_date->minute(0);
        $start_date->second(0);

        if($this->card_id == null)
        {
            $amount = TransactionModel::whereNull('card_id')->whereBetween('created_at', [$start_date, $end_date])->where('user_id', $this->user_id)->sum('amount');
        }
        else
        {
            $amount = TransactionModel::where('card_id', $this->card_id)->whereBetween('created_at', [$start_date, $end_date])->where('user_id', $this->user_id)->sum('amount');
        }

        $bill = BillModel::create([
            'card_id' => $this->card_id,
            'user_id' => $this->user_id,
            'amount' => $amount,
            'due_date' => Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 23:59:59')->addDays(15)
        ]);

        if($this->card_id == null)
        {
            $unpaid_bills = BillModel::whereNull('card_id')->where('user_id', $this->user_id)->where('status', 'unpaid')->get();
        }
        else
        {
            $unpaid_bills = BillModel::where('card_id', $this->card_id)->where('user_id', $this->user_id)->where('status', 'unpaid')->get();
        }

        $unpaid_amount = 0;

        foreach($unpaid_bills as $unpaid_bill)
        {
            if($unpaid_bill->amount > 0)
            {
                UnpaidBillModel::create([
                    'bill_id' => $bill->id,
                    'unpaid_bill_id' => $unpaid_bill->id
                ]);
                $unpaid_amount += $unpaid_bill->amount;
            }
        }

        if($unpaid_amount > 0)
        {
            $bill->update(['amount' => $amount + $unpaid_amount]);
        }
    }
}
