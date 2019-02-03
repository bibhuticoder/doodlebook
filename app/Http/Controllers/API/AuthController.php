<?php
namespace App\Http\Controllers\API;

use Validator;
use App\User;
use \Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller as BaseController;
use Avatar;

class AuthController extends BaseController 
{
    
    public function __construct(Request $request) {
        $this->request = $request;
    }

    protected function jwt(User $user) {
        $payload = [
            'iss' => "doodlebook-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60*60 // Expiration time
        ];
        return JWT::encode($payload, env('JWT_SECRET'));
    }

   
    public function authenticate() {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        
        // Find User by Email
        $user = User::where('email', $this->request->input('email'))->first();
        if (!$user) {
            return response()->json([
                'message' => 'Email does not exist.'
            ], 400);
        }

        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user)
            ], 200);
        }

        // Bad Request response
        return response()->json([
            'message' => 'Email or password is wrong.'
        ], 400);
    }

    public function register(Request $request) {
        $this->validate($this->request, [
            'username'     => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed|min:6' //password_confirmation field must be present
        ]);
        
        // hash the password
        $passwordHash = Hash::make($this->request->input('password'));
        
        //create avatar
        $profile_pic_name = $request->input('username').'_'.time().'.png';
        $user = User::create([
            'username' => $this->request->input('username'),
            'email' => $this->request->input('email'),
            'password' => $passwordHash,
            'profile_pic' => $profile_pic_name
        ]);
        // Avatar::create($request->input('username'))->save(public_path('/storage/profile_pics/' . $profile_pic_name, 100));
        
        return response()->json([
            'token' => $this->jwt($user)
        ], 201);
    }

    public function me() {
        return response()->json($this->request->auth);
    }

    public function update(Request $request){
        if($request->image){
            \Image::make($request->image)->save(public_path('/storage/profile_pics/' . $request->auth->profile_pic));
        }
        $updated = $request->auth->update([
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'bio' => $request->input('bio'),
        ]);
        return response()->json(['user' => $request->auth]);
    }

}