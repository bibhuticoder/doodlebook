<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Controllers\Controller;

class CommentsController extends Controller
{

    // store Comment in Database
    public function store(Request $request)
    {
        //handle file upload
        $created = Comment::create([
            'comment' => $request->input('comment'),
            'doodle_id' => $request->input('doodle_id'),
            'user_id' => 1 //get from auth
        ]);
        return ($created)
            ? response()->json($created, 201)
            : response()->json(['message' => 'failed'], 400);
    }

    // Update specific Doodle
    public function update($id, Request $request)
    {
        $comment = Comment::findOrFail($id);
        $updated = $comment->update([
            'comment' => $request->input('comment')
        ]);
        return ($updated)
            ? response()->json($comment, 201)
            : response()->json(['message' => 'failed']);
    }

    
    // Delete specific Doodle
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $deleted = $comment->delete();
        return ($deleted)
            ? response()->json(['message' => 'success'], 200)
            : response()->json(['message' => 'failed'], 400);
    }
}
