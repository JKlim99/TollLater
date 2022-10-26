<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'card';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'card_serial_no',
        'status',
        'batch_no'
    ];

    public function transactions()
    {
        return $this->hasMany(TransactionModel::class, 'card_id');
    }

    public function bills()
    {
        return $this->hasMany(BillModel::class, 'card_id')->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
