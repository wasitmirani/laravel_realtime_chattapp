<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




Route::post('/login','AuthController@login');
Route::get('/users/list/{user_id}','ChatController@user_list');
Route::get('auth/user/{user_id}','ChatController@auth_user');
Route::get('/chat/list/{sender_id}/{receiver_id}','ChatController@user_chat_list');












Route::post('send/message','ChatController@send_message');









Route::post('/login','AuthController@login');
