<?php

namespace App\Http\Controllers;

use App\Notifications\LonginNotification;
use Illuminate\Http\Request;
use \App\Http\Requests\LoginRquest;
use \App\Http\Requests\LoginVerifyRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\mailOtpCode;
class AuthentactionController extends Controller
{
    function submit(LoginRquest $request){
    //create user model
    try{
        DB::beginTransaction();
        // dd($request->email);
        $user = User::updateOrCreate([
            'email' => $request->email,
            'name' => 'user',
        ]);
        if(!$user){
            return response([
                'message' =>'User not found',
                'status' =>404
            ]);
        }

        //send otp to user


        Mail::to($user->email)->send(new mailOtpCode($user));

        DB::commit();
        return response()->json(['message' =>'send code in text message']);

    }catch(\Exception $e){
        DB::rollBack();
        return response()->json(['message' =>$e->getMessage()]);
    }

    }
    function verify(LoginVerifyRequest $request){

        $user = User::where('email',$request->email)->first();

        if ($user->login_code == $request->code){
            $user->update([
                'login_code'=>null
            ]);

            $token = $user->createToken($request->code)->plainTextToken;

            return response()->json(['token' =>$token]);
        }

        return response()->json(['message' =>'Otp wrong ']);

    }
}
