<?php

namespace App;

use Auth;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Channel extends Eloquent {

    protected $table = 'channels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'tms', 'user_id'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function logs() {
        return $this->hasMany('App\Log', 'channel_name', 'name');
    }

    public function someLogs() {
        return $this->hasMany('App\Log', 'channel_name', 'name')->orderBy('created_at', 'desc')->take(5);     
    }
    
    public function calculateUSD($tms = false) {
        if($tms === false) {
            $tms = $this->tms; 
        }

        return number_format($tms / Auth::user()->ms_usd, 2);
    }

}
