<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PlayersRelation extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the virtual player record associated with the relation.
     */
    public function getSoccerlife()
    {
        return $this->belongsTo('App\Models\SoccerlifePlayerOnTR', 'sl_player_id');
    }

    /**
     * Get the real player record associated with the relation.
     */
    public function getTransfermarkt()
    {
        return $this->belongsTo('App\Models\TransfermarktPlayer', 'tr_player_id');
    }

}
