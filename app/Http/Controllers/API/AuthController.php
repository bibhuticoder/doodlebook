<?php
namespace App\Http\Controllers\API;

use Validator;
use App\User;
use \Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller as BaseController;

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
            'exp' => time() + 60*60 // Expiration time
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
                'error' => 'Email does not exist.'
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
            'error' => 'Email or password is wrong.'
        ], 400);
    }

    public function register() {
        $this->validate($this->request, [
            'username'     => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed|min:6' //password_confirmation field must be present
        ]);
        
        // hash the password
        $passwordHash = Hash::make($this->request->input('password'));
        
        // return $this->request;

        $user = User::create([
            'username' => $this->request->input('username'),
            'email' => $this->request->input('email'),
            'password' => $passwordHash
        ]);
        
        return response()->json([
            'token' => $this->jwt($user)
        ], 201);
    }

    public function me() {
        return response()->json($this->request->auth);
    }

}