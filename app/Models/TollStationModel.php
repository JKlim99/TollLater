<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TollStationModel extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'toll_station';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'highway',
        'type',
        'price'
    ];

    public function transactions()
    {
        return $this->hasMany(TransactionModel::class, 'toll_station_id');
    }

    public function prices()
    {
        return $this->hasMany(ClosedStationPriceModel::class, 'toll_station_id');
    }
}
