<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use App\Jobs\GenerateBill;

class Billing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:bill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to generate bill for every active assigned card';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cards = DB::table('card')
                        ->select('card.id', DB::raw('max(card.user_id) as user_id'))
                        ->leftJoin('bill', 'card.id', '=', 'bill.card_id')
                        ->whereNotNull('card.user_id')
                        ->groupBy('card.id')
                        ->having(DB::raw('month(max(bill.created_at))'), '<', date('m'))
                        ->get();
        
        foreach($cards as $card)
        {
            GenerateBill::dispatch($card->user_id, $card->id);
        }

        return Command::SUCCESS;
    }
}
