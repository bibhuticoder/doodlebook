<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Doodle;
use App\Models\DoodleLike;
use App\Http\Controllers\Controller;
use App\Http\Resources\Doodle\DoodleResource;
use App\Http\Resources\Doodle\DoodleCollectionResource;
use App\Http\Requests\DoodleRequest;

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
        $doodle = Doodle::with('comments', 'comments.replies')->findOrFail($id);
        return response()->json(new DoodleResource($doodle), 200);
    }

    // store Doodle in Database
    public function store(DoodleRequest $request)
    {
        //handle file upload
        if($request->image){
            $fileNameToStore = $request->input('title').'_'.time().'.png';
            \Image::make($request->image)->save('storage/doodles/'.$fileNameToStore);
        }else{
            $fileNameToStore = 'noimage.jpg';
        }
        $created = Doodle::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'visibility' => $request->input('visibility'),
            'image' => $fileNameToStore,
            'user_id' => 1 //get from auth
        ]);
        return ($created)
            ? response()->json($created, 201)
            : response()->json(['message' => 'failed'], 400);
    }

    // Update specific Doodle
    public function update($id, Request $request)
    {
        $doodle = Doodle::findOrFail($id);
        if($request->image){
            $fileNameToStore = $doodle->image;
            \Image::make($request->image)->save('storage/doodles/'.$fileNameToStore);
            return response()->json($updated, 201);
        }
        $updated = $doodle->update([
            'title' => $request->input('title'),
            'description' => $request->input('description')
        ]);
        return ($updated)
            ? response()->json($doodle, 201)
            : response()->json(['message' => 'failed']);
    }

    // Update image of specific Doodle
    public function updateImage($id, Request $request)
    {
        $doodle = Doodle::findOrFail($id);
        if($request->image){
            $fileNameToStore = $doodle->image;
            \Image::make($request->image)->save('storage/doodles/'.$fileNameToStore);
            return response()->json(['message' => 'success'], 201);
        }
        else return response()->json(['message' => 'image not found']);
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
