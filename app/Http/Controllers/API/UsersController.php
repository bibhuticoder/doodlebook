<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{

    // list all doodles in the system
    public function index()
    {
        $doodles = Doodle::orderBy('created_at', 'DESC')->paginate(10);
        return response()->json($doodles, 200);
    }

    // list all doodles of specific user
    public function allDoodlesOfUser($user_id)
    {
        $doodles = Doodle::where('user_id', $user_id)->orderBy('created_at', 'DESC')->paginate(10);
        return response()->json($doodles, 200);
    }

    // show specific doodle
    public function show($id)
    {
        $doodle = Doodle::findOrFail($id);
        return response()->json($doodle, 200);
    }

    // store Doodle in Database
    public function store(Request $request)
    {
        $created = Doodle::create($request->all());
        ($created)
            ? response()->json($created, 201)
            : response()->json(['message' => 'failed']);
    }

    // Update specific Doodle
    public function update(Request $request, $id)
    {
        $doodle = Doodle::findOrFail($id);
        $updated = $doodle->update($request->all());
        ($updated)
            ? response()->json($updated, 201)
            : response()->json(['message' => 'failed']);
    }

    // Update image of specific Doodle
    public function updateImage(Request $request, $id)
    {
        $doodle = Doodle::findOrFail($id);
        $updated = $doodle->update($request->all());
        ($updated)
            ? response()->json($updated, 201)
            : response()->json(['message' => 'failed']);
    }

    // Delete specific Doodle
    public function destroy($id)
    {
        $doodle = Doodle::findOrFail($id);
        $deleted = $doodle->delete();
        ($deleted)
            ? response()->json(['message' => 'success'], 200)
            : response()->json(['message' => 'failed'], 400);
    }
}
