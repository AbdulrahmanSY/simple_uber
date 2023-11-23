<?php

namespace App\Http\Controllers;

use App\Http\Requests\DriverRequest;
use Illuminate\Http\Request;

class DriverConroller extends Controller
{
    function show(Request $request){
        $user = $request->user();

        $user->load('driver');

        return response()->json($user);
    }
    function update(DriverRequest $request){

        $user = $request->user();
        $user->update(['name'=> $request->name]);

        $user->driver()->updateOrCreate([
            'years'=>$request->years,
            'make'=>$request->make,
            'model'=>$request->model,
            'color'=>$request->color,
            'license_plate'=>$request->license_plate,

        ]);
        $user->load('driver');

        return response()->json($user);

    }
}
