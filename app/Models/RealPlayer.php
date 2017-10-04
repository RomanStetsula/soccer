<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealPlayer extends Model
{
    public $timestamps = false;

    protected $table = 'real_players';

    protected $fillable = [
        'firstname',
        'lastname',
        'team',
        'birth_date',
        'age',
        'position',
        'nationality',
        'market_value',
        'leagve_base_talent',
        'url'
    ];
}
