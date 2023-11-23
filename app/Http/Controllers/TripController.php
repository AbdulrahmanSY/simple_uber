<?php

namespace App\Http\Controllers;

use App\Events\CreateTripEvent;
use App\Events\TripAcceptedEvent;
use App\Events\TripCompleteEvent;
use App\Events\TripLocationUpdatedEvent;
use App\Events\TripStartedEvent;
use App\Http\Requests\DriverLocationRequest;
use App\Http\Requests\TripRequest;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    function store(TripRequest $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {

         $trip=$request->user()->trips()->create($request->only(
            'origin',
            'destination',
            'destination_name'
        ));
        CreateTripEvent::dispatch($trip,$request->user());


        return response( $trip);


    }
    function show(Request $request,Trip $trip){

        if($trip->user->id == $request->user()->id){
            return $trip;
        }
        if($trip->driver && $request->user()->driver->id){
            if ($trip->driver->id == $request->user()->driver->id)
                 return $trip;
        }

        return  response()->json(['message' => 'erro', 'status' => 404]);
    }

    function accept(DriverLocationRequest $request,Trip $trip){

        $trip->update([
            'driver_id' => $request->user()->driver->id,
            'driver_location'=>$request->driver_location
        ]);

        $trip->load('driver.user');
        TripAcceptedEvent::dispatch($trip ,$trip -> user);

        return $trip;


    }
    function start(Request $request,Trip $trip){

        $trip->update([ 'is_started' => true,]);
        TripStartedEvent::dispatch($trip,$request->user());
        return $trip->load('driver.user');

    }
    function end(Request $request,Trip $trip){

        $trip->update([ 'is_completed' => true,]);
        TripCompleteEvent::dispatch($trip,$request->user());
        return $trip->load('driver.user');

    }
    function location(DriverLocationRequest $request,Trip $trip){
        $trip->update([
            'driver_location' => $request->driver_location,
        ]);
        TripLocationUpdatedEvent::dispatch($trip,$trip->user);
        return $trip->load('driver.user');
    }
}
