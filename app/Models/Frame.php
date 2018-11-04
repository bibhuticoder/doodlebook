<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    protected $table = "frames";
    protected $fillable = [
        'data', 'doodle_id'
    ];

}
