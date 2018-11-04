<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Frame;
use App\Http\Controllers\Controller;

class FramesController extends Controller
{

    // store Frame in Database
    public function store(Request $request)
    {
        //handle file upload
        $created = Frame::create([
            'data' => $request->input('data'),
            'doodle_id' => $request->input('doodle_id'),
        ]);
        return ($created)
            ? response()->json($created, 201)
            : response()->json(['message' => 'failed'], 400);
    }

    // Update specific Doodle
    public function update($id, Request $request)
    {
        $frame = Frame::findOrFail($id);
        $updated = $frame->update([
            'data' => $request->input('data')
        ]);
        return ($updated)
            ? response()->json($frame, 201)
            : response()->json(['message' => 'failed']);
    }

    
    // Delete specific Doodle
    public function destroy($id)
    {
        $frame = Frame::findOrFail($id);
        $deleted = $frame->delete();
        return ($deleted)
            ? response()->json(['message' => 'success'], 200)
            : response()->json(['message' => 'failed'], 400);
    }
}
