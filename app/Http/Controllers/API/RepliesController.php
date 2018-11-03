<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\CommentReply;
use App\Http\Controllers\Controller;

class RepliesController extends Controller
{

    // store CommentReply in Database
    public function store(Request $request)
    {
        //handle file upload
        $created = CommentReply::create([
            'reply' => $request->input('reply'),
            'comment_id' => $request->input('comment_id'),
            'user_id' => 1 //get from auth
        ]);
        return ($created)
            ? response()->json($created, 201)
            : response()->json(['message' => 'failed'], 400);
    }

    // Update specific Doodle
    public function update($id, Request $request)
    {
        $commentReply = CommentReply::findOrFail($id);
        $updated = $commentReply->update([
            'reply' => $request->input('reply')
        ]);
        return ($updated)
            ? response()->json($commentReply, 201)
            : response()->json(['message' => 'failed']);
    }

    
    // Delete specific Doodle
    public function destroy($id)
    {
        $commentReply = CommentReply::findOrFail($id);
        $deleted = $commentReply->delete();
        return ($deleted)
            ? response()->json(['message' => 'success'], 200)
            : response()->json(['message' => 'failed'], 400);
    }
}
