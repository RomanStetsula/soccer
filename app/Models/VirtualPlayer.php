<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualPlayer extends Model
{
    public $timestamps = false;

    protected $table = 'virtual_players';

    protected $fillable = [
        'firstname',
        'lastname',
        'team',
        'birth_date',
        'age',
        'position',
        'nationality',
        'value',
        'skill',
        'talent',
        'url'
    ];
}
