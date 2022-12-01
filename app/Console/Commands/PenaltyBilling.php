<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use App\Jobs\GenerateBill;

class PenaltyBilling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:penalty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to generate penalty bill for every user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = DB::table('bill')
                        ->select(DB::raw('max(bill.user_id) as user_id'))
                        ->whereNull('bill.card_id')
                        ->groupBy('bill.user_id')
                        ->having(DB::raw('month(max(bill.created_at))'), '<', date('m'))
                        ->get();
        
        foreach($users as $user)
        {
            GenerateBill::dispatch($user->user_id);
        }

        return Command::SUCCESS;
    }
}
