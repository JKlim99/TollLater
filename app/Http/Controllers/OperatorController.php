<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TollStationModel;
use App\Models\ClosedStationPriceModel;

use DB;

class OperatorController extends Controller
{
    /* Toll Staions Management */
    public function stationList(Request $request)
    {
        $type = $request->input('type', null);
        $keyword = $request->input('keyword', null);

        if(!$keyword)
        {
            $stations = TollStationModel::orderBy('created_at', 'desc')->paginate(10);
        }
        else
        {
            if($type == 'type')
            {
                $keyword = str_replace(' ', '_', $keyword);
            }
            $stations = TollStationModel::where($type, 'LIKE', '%'.$keyword.'%')->orderBy('created_at', 'desc')->paginate(10);
        }

        return view('operator.stations')->with(['stations'=>$stations, 'type'=>$type, 'keyword'=>$keyword]);
    }

    public function createStationPage()
    {
        $stations = TollStationModel::where('type', 'closed_exit')->get();
        return view('operator.stationCreate')->with(['stations'=>$stations]);
    }

    public function createStation(Request $request)
    {
        $name = $request->input('name', null);
        $highway = $request->input('highway', null);
        $type = $request->input('type', null);
        $price = $request->input('price', null);

        $prices = $request->input('prices', []);
        $station_ids = $request->input('station_ids', []);

        $station_found = TollStationModel::where('name', $name)->first();
        if($station_found)
        {
            return redirect()->back()->withInput()->with('error', 'Station Name existed.');
        }

        $station = TollStationModel::create([
            'name'=>$name,
            'highway'=>$highway,
            'type'=>$type,
            'price'=>$price
        ]);

        if($type == 'closed_entry')
        {
            $i = 0;
            foreach($station_ids as $station_id)
            {
                ClosedStationPriceModel::create([
                    'toll_station_id'=>$station_id,
                    'entry_id' => $station->id,
                    'price' => $prices[$i]
                ]);
                $i++;
            }
        }

        
        return redirect('/operator/stations')->with(['alert_status'=>'success', 'alert_text'=>'Station has created successfully.']);
    }

    public function deleteStation($id)
    {
        $station = TollStationModel::find($id);
        if(!$station)
        {
            return redirect('/operator/stations')->with(['alert_status'=>'error', 'alert_text'=>'Station not found.']);
        }

        $station->delete();

        return redirect('/operator/stations')->with(['alert_status'=>'success', 'alert_text'=>'Station has deleted successfully.']);
    }

    public function stationDetails($id)
    {
        $station = TollStationModel::find($id);
        if(!$station)
        {
            return redirect('/operator/stations')->with(['alert_status'=>'error', 'alert_text'=>'Station not found.']);
        }

        $stations = TollStationModel::where('type', 'closed_exit')->get();

        $closed_stations = [];
        if($station->type == 'closed_entry')
        {
            $closed_stations = ClosedStationPriceModel::where('entry_id', $id)->get();
        }

        return view('operator.stationDetails')->with(['station'=>$station, 'stations'=>$stations, 'closed_stations'=>$closed_stations]);
    }

    public function stationUpdate(Request $request, $id)
    {
        $name = $request->input('name', null);
        $highway = $request->input('highway', null);
        $type = $request->input('type', null);
        $price = $request->input('price', null);

        $prices = $request->input('prices', []);
        $station_ids = $request->input('station_ids', []);
        $ids = $request->input('ids', []);

        $station_found = TollStationModel::where('name', $name)->where('id', '!=', $id)->first();
        if($station_found)
        {
            return redirect()->back()->withInput()->with('error', 'Station Name existed.');
        }

        TollStationModel::where('id', $id)->update([
            'name'=>$name,
            'highway'=>$highway,
            'type'=>$type,
            'price'=>$price
        ]);

        $closed_stations = ClosedStationPriceModel::where('entry_id', $id)->get();

        foreach($closed_stations as $closed_station)
        {
            if(!in_array($closed_station->id, $ids))
            {
                ClosedStationPriceModel::where('id', $closed_station->id)->delete();
            }
        }

        if($type == 'closed_entry')
        {
            $i = 0;
            foreach($station_ids as $station_id)
            {
                if($ids[$i] != 0)
                {
                    ClosedStationPriceModel::where('id', $ids[$i])->update([
                        'toll_station_id'=>$station_id,
                        'entry_id' => $id,
                        'price' => $prices[$i]
                    ]);
                }
                else
                {
                    ClosedStationPriceModel::create([
                        'toll_station_id'=>$station_id,
                        'entry_id' => $id,
                        'price' => $prices[$i]
                    ]);
                }
                
                $i++;
            }
        }
        else
        {
            ClosedStationPriceModel::where('entry_id', $id)->delete();
        }

        
        return redirect('/operator/station/'.$id)->with(['alert_status'=>'success', 'alert_text'=>'Station has updated successfully.']);
    }

    public function report(Request $request)
    {
        $from_date = $request->input('from_date', date("Y-m-d",strtotime("-1 month")));
        $to_date = $request->input('to_date', date("Y-m-d"));
        $highway = $request->input('highway', null);

        $highways = DB::table('toll_station')
                        ->select('highway')
                        ->groupBy('highway')
                        ->get();

        $stations = DB::table('toll_station')
                        ->leftJoin('transaction', function($join) use ($from_date, $to_date)
                        {
                            $join->on('toll_station.id', '=', 'transaction.toll_station_id');
                            $join->where('transaction.created_at', '>=', $from_date);
                            $join->where('transaction.created_at', '<=', $to_date);
                        })
                        ->select(DB::raw('max(toll_station.name) as name'), DB::raw('max(toll_station.highway) as highway'), DB::raw('sum(transaction.amount) as amount'))
                        ->where('toll_station.type', '!=', 'closed_entry');

        if($highway)
        {
            $stations = $stations->where('toll_station.highway', 'LIKE', '%'.$highway.'%');
        }

        $stations = $stations->groupBy('toll_station.id')->get();

        return view('operator.reports')->with(['highways'=>$highways, 'from_date'=>$from_date, 'to_date'=>$to_date, 'selected_highway'=>$highway, 'stations'=>$stations]);
    }
}
