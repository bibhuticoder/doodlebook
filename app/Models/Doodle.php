<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doodle extends Model
{
    protected $table = "doodles";
    protected $fillable = [
        'title', 'description', 'user_id', 'image', 'visibility', 'forked_from'
    ];

    public function comments(){
        return $this->hasmany('App\Models\Comment', 'doodle_id');
    }

    public function likes(){
        return $this->hasmany('App\Models\DoodleLike', 'doodle_id');
    }

}
