<?php


namespace App\Http\Controllers;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;  // this brings the hash facade 


class AuthController extends Controller

{


public function register(RegisterRequest $request)

{
    $user = User::create([
     'name'=>$request->name,
    'email'=>$request->email
     'password'=> Hash::make($request->password),
 
        ]);

        $user->sendEmailVerificationNotification();

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([

        'user' => new UserResource($user),
        'token' => $token,
        'message' => 'Please verify your email address.',

    ],201);
}

}






