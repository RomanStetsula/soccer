<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\SoccerlifePlayerOnTR;
use App\Models\TransfermarktPlayer;

class TestController extends Controller
{


    public function test()
    {

        $countries = SoccerlifePlayerOnTR::select('nationality')->get();
        $countries = $countries->unique('nationality')->sortBy('nationality')->pluck('nationality')->all();
        dd($countries);

//        $tr_countries = TransfermarktPlayer::select('nationality')->get();
//        $tr_countries = $tr_countries->unique('nationality')->sortBy('nationality');
//        $tr_countries = $tr_countries->each(function ($item, $key) {
//            preg_match('/^\w*/', $item->nationality, $matches);
//            $item->nationality = $matches[0];
//        });
//        $nationalities = $tr_countries->unique('nationality')->pluck('nationality')->all();
    }
}
