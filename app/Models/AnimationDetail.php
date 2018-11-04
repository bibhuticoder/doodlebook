<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimationDetail extends Model
{
    protected $table = "animation_details";
    protected $fillable = [
        'infinite', 'interval', 'sequence', 'doodle_id'
    ];

}
