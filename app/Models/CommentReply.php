<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    //
    protected $fillable = [
        'comment_id', 'reply', 'user_id'
    ];

    public function comment(){
        return $this->belongsTo('App\Models\Comment', 'comment_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

}
