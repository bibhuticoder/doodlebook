<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    protected $table = "frames";
    protected $fillable = [
        'image', 'doodle_id', 'duration'
    ];

    public function doodle(){
        return $this->belongsTo('App\Models\Doodle', 'doodle_id');
    }

}
