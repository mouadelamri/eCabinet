<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{

    public function login(Request $request)
    {
    $request->validate([
        'email'=>'required|string|email|max:255',
        'password'=>'required|string|min:8'
    ]);
    if(!Auth::attempt($request->only('email' , 'password')))
    return response()->json([
        'message' => 'email or password incorrect',
    ], 401);

    $user = User::where('email', $request->email)->FirstOrFail();
    $token = $user->createToken('auth_token')->plainTextToken;
    return response()->json([
        'message' => 'login with succesful',
        'user'=> $user ,
        'token'=>$token
    ],200);
    }
    public function logout()
    {
      //
    }
}
