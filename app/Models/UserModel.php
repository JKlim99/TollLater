<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Hash;

class UserModel extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'ic_no',
        'email',
        'mobile_no',
        'secret_key',
        'hash'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'secret_key',
        'hash'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $hash = $model->generateRandomString();
            $model->attributes['hash'] = $hash;
            $model->attributes['secret_key'] = Hash::make($model->secret_key.$hash);
        });

        self::updating(function($model){
            if(strlen($model->secret_key) != 60)
            {
                $hash = $model->hash;
                $model->attributes['secret_key'] = Hash::make($model->secret_key.$hash);
            }
        });
    }

    private function generateRandomString($length = 15) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function cards()
    {
        return $this->hasMany(CardModel::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(TransactionModel::class, 'user_id');
    }

    public function bills()
    {
        return $this->hasMany(BillModel::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(PaymentModel::class, 'user_id');
    }

    public function carPlates()
    {
        return $this->hasMany(CarPlateNumberModel::class, 'user_id');
    }
}
