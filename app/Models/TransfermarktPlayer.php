<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransfermarktPlayer extends Model
{
    public $timestamps = false;

    protected $table = 'transfermarkt_players';

    protected $fillable = [
        'firstname',
        'lastname',
        'team',
        'birth_date',
        'age',
        'position',
        'nationality',
        'market_value',
        'talent',
        'url'
    ];
}
