<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Doodle;
use App\Models\DoodleLike;
use App\Models\AnimationDetail;
use App\Http\Controllers\Controller;
use App\Http\Resources\Doodle\DoodleResource;
use App\Http\Resources\Doodle\DoodleWithFramesResource;
use App\Http\Resources\Doodle\DoodleCollectionResource;
use App\Http\Requests\DoodleRequest;
use Illuminate\Support\Facades\DB;
use Pomirleanu\GifCreate\GifCreate;

class DoodlesController extends Controller
{

    // list all doodles in the system
    public function index()
    {
        $doodles = Doodle::where('visibility', 0)->orderBy('created_at', 'DESC')->paginate(10);
        return response()->json(new DoodleCollectionResource($doodles), 200);
    }

    // list all doodles of specific user
    public function allDoodlesOfUser($user_id)
    {
        $doodles = Doodle::where('user_id', $user_id)->orderBy('created_at', 'DESC')->paginate(10);
        return response()->json(new DoodleCollectionResource($doodles), 200);
    }

    // show specific doodle
    public function show(Request $request, $id)
    {
        $doodle = Doodle::findOrFail($id);
        // $doodle['liked'] = DoodleLike::where('doodle_id', $this->id)->where('user_id', $request->auth->id)->exists();
        return response()->json(new DoodleResource($doodle), 200);
    }

    // show specific doodle with frames
    public function showWithFrames($id)
    {
        $doodle = Doodle::with('frames', 'animationDetail')->findOrFail($id);

        // sort frames according to sequence
        $sequence  = explode(",", $doodle->animationDetail->sequence);
        $frames    = array();
        for($i = 0; $i < count($sequence); $i++){
            $frame = $doodle->frames-> firstWhere('id', intval($sequence[$i]));
            if($frame !== NULL) array_push($frames, $frame);
        }
        $doodle['frames_sorted'] = $frames;
        return response()->json(new DoodleWithFramesResource($doodle), 200);
    }

    // store Doodle in Database
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'          => 'required',
            'visibility'     => 'required',
        ]);

        return DB::transaction(function() use ($request) {

            $doodle = Doodle::create([
                'title'       => $request->input('title'),
                'description' => $request->input('description'),
                'visibility'  => $request->input('visibility'),
                'user_id'     => $request->auth->id
            ]);

            if($doodle){

                $doodle->animationDetail()->create([
                    'frame_width'  => $request->input('frame_width'),
                    'frame_height' => $request->input('frame_height'),
                    'doodle_id'    => $doodle->id
                ]);

                // generate gif
                $doodle->image = $doodle->id . '_doodle_' . '.gif';
                $doodle->save();

            }
            return response()->json($doodle->id, 201);
        });
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title'          => 'required',
            'visibility'     => 'required',
        ]);

        return DB::transaction(function() use ($request) {

            $doodle = Doodle::findOrFail($request->id);

            $doodle->update([
                'title'       => $request->input('title'),
                'description' => $request->input('description'),
                'visibility'  => $request->input('visibility')
            ]);

            $doodle->animationDetail()->update([
                'sequence'     => $request->sequence,
                'frame_width'  => $request->frame_width,
                'frame_height' => $request->frame_height
            ]);

            // generate gif
            if($doodle->frames->count() > 0){
                // arrange according to sequence
                $sequence  = explode(",", $doodle->animationDetail->sequence);
                $frames    = array();
                $durations = array();
                for($i = 0; $i < count($sequence); $i++){
                    $frame = $doodle->frames->firstWhere('id', intval($sequence[$i]));
                    if($frame){
                        array_push($frames, (public_path('\storage\frames\\' . $frame->image)));
                        array_push($durations, $frame->duration);
                    }   
                }
                // create gif
                $gif = new GifCreate();
                $gif->create($frames, $durations);
                $gif->save(public_path('\storage\doodles\\' . $doodle->image));
            }

            return response()->json(['message' => 'success'], 200);
        });
    }

    // Delete specific Doodle
    public function destroy($id)
    {
        $doodle = Doodle::findOrFail($id);
        $deleted = $doodle->delete();
        return ($deleted)
            ? response()->json(['message' => 'success'], 200)
            : response()->json(['message' => 'failed'], 400);
    }

    public function like(Request $request, $doodle_id)
    {
        $user_id = $request->auth->id; //from Auth

        // check if already liked
        $like = DoodleLike::where('doodle_id', $doodle_id)->where('user_id', $user_id)->count();
        if($like) return response()->json(['message' => 'already liked'], 200);

        $created = DoodleLike::create([
            'doodle_id' => $doodle_id,
            'user_id' => $user_id
        ]);
        return ($created)
            ? response()->json(['message' => 'success'], 200)
            : response()->json(['message' => 'failed'], 400);
    }

    public function dislike(Request $request, $doodle_id)
    {
        $user_id = $request->auth->id; //from Auth
        $like = DoodleLike::where([
            'doodle_id' => $doodle_id,
            'user_id' => $user_id
        ])->firstOrFail();
        $deleted = $like->delete();
        return ($deleted)
            ? response()->json(['message' => 'success'], 200)
            : response()->json(['message' => 'failed'], 400);
    }

}
