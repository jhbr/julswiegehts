<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Island extends Model
{


    protected $fillable = [
        'name', 'position_x', 'position_y'
    ];


    public function user() {
        return $this->belongsTo('App\User');
    }
}
