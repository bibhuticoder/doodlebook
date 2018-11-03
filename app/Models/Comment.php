<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = [
        'doodle_id', 'comment', 'user_id'
    ];

    public function replies(){
        return $this->hasmany('App\Models\CommentReply', 'comment_id');
    }

    public function doodle(){
        return $this->belongsTo('App\Models\Doodle', 'doodle_id');
    }

}
