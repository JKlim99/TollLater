<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bill';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'card_id',
        'user_id',
        'amount',
        'due_date',
        'status'
    ];

    public function payments()
    {
        return $this->hasMany(PaymentModel::class, 'bill_id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function card(){
        return $this->belongsTo(CardModel::class, 'card_id');
    }
}
