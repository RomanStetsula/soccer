<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    public $timestamps = false;

    protected $table = 'leagues';

    protected $fillable = [
        'name',
        'country',
        'tier',
        'base_talent',
        'url',
        'date_of_parsing',
    ];
}
