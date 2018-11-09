<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    protected $table = "frames";
    protected $fillable = [
        'image', 'doodle_id', 'duration'
    ];

}
