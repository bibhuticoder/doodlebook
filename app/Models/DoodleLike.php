<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoodleLike extends Model
{
    //
    protected $fillable = [
        'doodle_id', 'user_id'
    ];

    public function doodle(){
        return $this->belongsTo('App\Models\Doodle', 'doodle_id');
    }
}
