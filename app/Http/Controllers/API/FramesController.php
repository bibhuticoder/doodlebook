<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Frame;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FramesController extends Controller
{

    // store Frame in Database
    public function store(Request $request)
    {
        //handle file upload
        if($request->image){
            $fileNameToStore = 'frame_' . $request->input('doodle_id').'_'.time().'.png';
            \Image::make($request->image)->save(public_path('\storage\frames\\'.$fileNameToStore));
        }else{
            $fileNameToStore = 'noimage.jpg';
        }
        $created = Frame::create([
            'image' => $fileNameToStore,
            'doodle_id' => $request->input('doodle_id'),
        ]);
        return ($created)
            ? response()->json($created->id, 201)
            : response()->json(['message' => 'failed'], 400);
    }

    // Update specific Doodle
    public function update($id, Request $request)
    {
        $frame = Frame::findOrFail($id);
        
        //handle file upload
        if($request->image){
            \Image::make($request->image)->save(public_path('\storage\frames\\'. $frame->image));
        }

        $updated = $frame->update([
            'duration' => $request->input('duration'),
            'image' => $frame->image
        ]);

        return ($updated)
            ? response()->json(['message' => 'success'], 200)
            : response()->json(['message' => 'failed']);
    }

    
    // Delete specific Doodle
    public function destroy($id)
    {
        $frame = Frame::findOrFail($id);
        $deleted = $frame->delete();
        Storage::delete('public\frames\\'.$frame->image);

        // update animation sequence
        $sequences = explode(",", $frame->doodle->animationDetail->sequence);
        $sequences = array_diff($sequences, array($frame->id));
        $frame->doodle->animationDetail->sequence = join(",",$sequences);
        $frame->doodle->animationDetail->save();

        return ($deleted)
            ? response()->json(['message' => 'success'], 200)
            : response()->json(['message' => 'failed'], 400);
    }
}
