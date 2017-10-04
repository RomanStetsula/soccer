<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PlayersRelation extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the virtual player record associated with the relation.
     */
    public function getVirtual()
    {
        return $this->belongsTo('App\Models\VirtualPlayer', 'virtual_player_id');
    }

    /**
     * Get the real player record associated with the relation.
     */
    public function getReal()
    {
        return $this->belongsTo('App\Models\RealPlayer', 'real_player_id');
    }

}
