<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'requested_at'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
