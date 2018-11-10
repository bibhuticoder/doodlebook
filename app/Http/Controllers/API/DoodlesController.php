<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Doodle;
use App\Models\DoodleLike;
use App\Models\AnimationDetail;
use App\Http\Controllers\Controller;
use App\Http\Resources\Doodle\DoodleResource;
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
    public function show($id)
    {
        $doodle = Doodle::findOrFail($id);
        return response()->json(($doodle), 200);
    }

    // show specific doodle with frames
    public function showWithFrames($id)
    {
        $doodle = Doodle::with('frames', 'animationDetail')->findOrFail($id);
        return response()->json($doodle, 200);
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
                
                // get all frames and sequences
                $allFrames = $doodle->frames->map(function($frame){
                    return ('storage/frames/' . $frame->image);
                })->toArray();
                $allDurations = $doodle->frames->map(function($frame){
                    return $frame->duration;
                })->toArray();
                
                // arrange according to sequence
                if($doodle->animationDetail->sequence){
                    $sequence = explode(",", $doodle->animationDetail->sequence);
                    $frames = array();
                    $durations = array();
                    for($i = 0; $i < count($sequence); $i++){
                        $index = intval($sequence[$i]);
                        array_push($frames, $allFrames[$index]);
                        array_push($durations, $allDurations[$index]);
                    }
                }else{
                    $frames = $allFrames;
                    $durations = $allDurations;
                }

                // create gif
                $gif = new GifCreate();
                $gif->create($frames, $durations);
                $gif->save('storage/doodles/' . $doodle->image);
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

    public function like($doodle_id)
    {
        $user_id = 1; //from Auth

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

    public function dislike($doodle_id)
    {
        $user_id = 1; //from Auth
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
