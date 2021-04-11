<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $req) {
        $fields = $req->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $usr = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $usr->createToken('myapptoken')->plainTextToken;

        $res = [
            'user' => $usr,
            'token' => $token
        ];

        return response($res, 200);
    }

    public function login(Request $req) {
        $fields = $req->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $usr = User::where('email', $fields['email'])->first();

        if(!$usr || !Hash::check($fields['password'], $usr->password)){
            return response([
                'message' => 'Invalid email or password'
            ], 401);
        }

        $token = $usr->createToken('myapptoken')->plainTextToken;

        $res = [
            'user' => $usr,
            'token' => $token
        ];

        return response($res, 200);
    }

    public function logout(Request $req){
            auth()->user()->tokens()->delete();

            return [
                'message' => 'Logged out!'
            ];
    }

}
