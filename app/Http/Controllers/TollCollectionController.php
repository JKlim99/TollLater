<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CardModel;
use App\Models\TollStationModel;
use App\Models\TransactionModel;
use App\Models\ClosedStationPriceModel;

class TollCollectionController extends Controller
{
    public $penalty_multiply = 2;

    public function toll(Request $request)
    {
        $card_serial_no = $request->input('card_serial_no');
        $toll_station_id = $request->input('toll_station_id');
        $type = $request->input('type', 'entry');

        $card_found = CardModel::where('card_serial_no', $card_serial_no)->whereNotNull('user_id')->where('status', 'active')->first();
        if(!$card_found)
        {
            return errorResponse('Card not found.', 'C01');
        }

        $station_found = TollStationModel::where('id', $toll_station_id)->first();
        if(!$station_found)
        {
            return errorResponse('Toll station not found.', 'T01');
        }

        $amount = 0.00;

        if($station_found->type == 'open') // 'open' type
        {
            $amount = $station_found->amount;
            TransactionModel::create([
                'card_id' => $card_found->id,
                'user_id' => $card_found->user_id,
                'type' => 'single',
                'amount' => $station_found->amount,
                'toll_station_id' => $toll_station_id,
                'station_type' => $station_found->type
            ]);
        }
        else // 'closed' type
        {
            $entry_found = TransactionModel::where('card_id', $card_found->id)->where('user_id', $card_found->user_id)->where('station_type', $station_found->type)->orderBy('created_at', 'desc')->first();
            if($type == 'entry')
            { // entry
                TransactionModel::create([
                    'card_id' => $card_found->id,
                    'user_id' => $card_found->user_id,
                    'type' => 'entry',
                    'amount' => $amount,
                    'toll_station_id' => $toll_station_id,
                    'station_type' => $station_found->type
                ]);
            }
            else
            { // exit
                if($entry_found->type == 'exit' && $type == 'exit')
                {
                    $amount = 0.00;
                }
                else
                {
                    $price = ClosedStationPriceModel::where('toll_station_id', $entry_found->toll_station_id)->where('exit_id', $toll_station_id)->first();
                    if($price)
                    {
                        $amount = $price->price;
                    }
                }

                TransactionModel::create([
                    'card_id' => $card_found->id,
                    'user_id' => $card_found->user_id,
                    'type' => 'exit',
                    'amount' => $amount,
                    'toll_station_id' => $toll_station_id,
                    'station_type' => $station_found->type
                ]);
            }
        }

        return successResponse(['amount'=>$amount], 'Charged RM'.number_format($amount, 2, '.', ','));
    }

    public function penalize(Request $request)
    {
        $car_plate_number = $request->input('car_plate_number');
        $toll_station_id = $request->input('toll_station_id');

        $user_found = CarPlateNumberModel::where('car_plate_numebr', $car_plate_number)->first();
        $station_found = TollStationModel::where('id', $toll_station_id)->first();
        if(!$station_found)
        {
            return errorResponse('Toll station not found.', 'T01');
        }

        $amount = 0.00;
        if($station_found->type == 'open') // 'open' type
        {
            $amount = $station_found->amount * $this->penalty_multiply;
            TransactionModel::create([
                'card_id' => null,
                'user_id' => $user_found->user_id,
                'type' => 'penalty',
                'amount' => $amount,
                'toll_station_id' => $toll_station_id,
                'station_type' => $station_found->type,
                'car_plate_no' => $car_plate_number
            ]);
        }
        else // 'closed' type
        {
            $price = ClosedStationPriceModel::where('toll_station_id', $station_found->id)->orderBy('price', 'desc')->first();

            $amount = 1.00;

            if($price)
            {
                $amount = $price->price * $this->penalty_multiply;
            }

            TransactionModel::create([
                'card_id' => null,
                'user_id' => $user_found->user_id,
                'type' => 'penalty',
                'amount' => $amount,
                'toll_station_id' => $toll_station_id,
                'station_type' => $station_found->type,
                'car_plate_no' => $car_plate_number
            ]);
        }

        return successResponse(['amount'=>$amount], 'Penalized RM'.number_format($amount, 2, '.', ','));
    }

    protected function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
			'status'=> 'Success', 
			'message' => $message, 
			'data' => $data
		], $code);
	}

	protected function errorResponse($message = null, $code)
	{
		return response()->json([
			'status'=>'Error',
			'message' => $message,
			'data' => null
		], $code);
	}
}
