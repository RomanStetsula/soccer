<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoccerlifePlayerOnTR;
use Carbon\Carbon;

class SlPlayersController extends Controller
{
    public function show()
    {
        $players = SoccerlifePlayerOnTR::where([
                ['nationality', 'CzechRepublic'],
                ['transfer_date', '>', Carbon::now()->timezone('Europe/Moscow')]
            ])
            ->get();

       return view('czech', compact('players'));
    }
}
