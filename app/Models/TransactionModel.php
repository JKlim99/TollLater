<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaction';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'card_id',
        'type',
        'paid',
        'charged',
        'amount',
        'toll_station_id',
        'station_type',
        'car_plate_no',
        'user_id'
    ];

    public function user()
    {
        return $this->belongTo(UserModel::class, 'user_id');
    }

    public function card()
    {
        return $this->belongTo(CardModel::class, 'card_id');
    }

    public function station()
    {
        return $this->belongTo(TollStationModel::class, 'toll_station_id');
    }
}
