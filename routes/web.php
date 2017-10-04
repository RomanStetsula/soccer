<?php

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
Route::resource('/league', 'LeagueController');

Route::get('/real', 'RealPlayerParserController@parse');

Route::get('/virtual', 'VirtualPlayerController@parse');

Route::get('/perspective', 'PlayerRelationController@show');

Route::get('parseTM/{id}', 'ParseTransfermarktController@parse');

Route::get('/', function (){
    return view('welcome');
});

