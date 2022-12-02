<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosedStationPriceModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'closed_station_price';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'toll_station_id',
        'exit_id',
        'price'
    ];

    public function station()
    {
        return $this->belongsTo(TollStationModel::class, 'toll_station_id');
    }

    public function exitStation()
    {
        return $this->belongsTo(TollStationModel::class, 'exit_id');
    }
}
