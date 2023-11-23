<?php

use App\Events\CreateTripEvent;
use App\Http\Controllers\AuthentactionController;
use App\Http\Controllers\DriverConroller;
use App\Http\Controllers\TripController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Pusher\Pusher;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login',[AuthentactionController::class,'submit']);
Route::post('/login-verify',[AuthentactionController::class,'verify']);

Route::group(['middleware' => 'auth:sanctum'],function(){
    Route::Get('/driver',[DriverConroller::class,'show']);
    Route::Post('/driver',[DriverConroller::class,'update']);

    Route::Post('/trips',[TripController::class,'store']);
    Route::get('/trips/{trip}',[TripController::class,'show']);

    Route::Post('/trips/{trip}/accept',[TripController::class,'accept']);
    Route::Post('/trips/{trip}/start',[TripController::class,'start']);
    Route::Post('/trips/{trip}/end',[TripController::class,'end']);
    Route::Post('/trips/{trip}/location',[TripController::class,'location']);



    Route::Get('/user',function(Request $request){
        return $request->user();
    });


    Route::post('/test-pusher', function () {
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ]
        );

        $pusher->trigger('drivers', 'CreateTripEvent', ['message' => 'Testing Pusher']);

        return 'Pusher test event sent successfully.';
    });
});

