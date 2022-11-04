<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminModel extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'type',
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
}
