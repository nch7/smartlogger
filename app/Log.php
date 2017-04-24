<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Log extends Eloquent {

    protected $table = 'logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title', 'channel_name', 'ms', 'meta', 'created_at'
    ];

    public function channel() {
        return $this->belongsTo('App\Channel', 'name', 'channel_name');
    }

}
