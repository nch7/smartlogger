<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Laravel\Passport\HasApiTokens;

class User extends Eloquent  implements Authenticatable{
    use AuthenticableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'ms_usd'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function channels() {
        return $this->hasMany('App\Channel');
    }

    static public function findByToken($token) {
        return User::where('api_token', $token)->firstOrFail();
    }
}
