<?php

use App\Jobs\ParsePlayerOnTransfer;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/soccer', 'SoccerlifeController@parse');

Route::get('/parseOnTransfer', function () {
    ParsePlayerOnTransfer::dispatch();
});

Route::resource('/league', 'LeagueController');


Route::get('/test', 'TestController@test');

Route::get('/perspective', 'PlayerRelationController@show');

Route::get('/delete_rel/{id}', 'PlayerRelationController@checked');

//Route::get('/real', 'RealPlayerParserController@parse');
//
//Route::get('/virtual', 'VirtualPlayerController@parse');
//
//
//Route::get('parseTM/{id}', 'ParseTransfermarktController@parse');

