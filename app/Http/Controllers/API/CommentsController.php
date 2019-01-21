<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\Comment\CommentCollectionResource;

class CommentsController extends Controller
{

    // list all comments of the doodle
    public function index($id)
    {
        $comments = Comment::with('user')->where('doodle_id', $id)->orderBy('created_at', 'DESC')->paginate(10);
        return response()->json(new CommentCollectionResource($comments), 200);
    }

    // store Comment in Database
    public function store(Request $request)
    {
        //handle file upload
        $created = Comment::create([
            'comment' => $request->input('comment'),
            'doodle_id' => $request->input('doodle_id'),
            'user_id' => $request->auth->id
        ]);
        return ($created)
            ? response()->json($created->load('user'), 201)
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
