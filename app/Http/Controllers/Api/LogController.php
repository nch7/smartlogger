<?php

namespace App\Http\Controllers\Api;

use App\Channel;
use App\Log;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    public function new(Request $request) {

        $this->validate($request, [
            'ms' => 'required|numeric',
            'channel' => 'required',
            'title' => 'required',
            'token' => 'required|exists:users,api_token'
        ]);

        $user = User::findByToken($request->get('token'));

        // let's see if user has a channel with this name already
        $channel = $user->channels()->where('name', $request->get('channel'))->first();
        
        if(!$channel) {
            // if not, let's create a new channel in this user's account and give it the given name
            $channel = Channel::create([
                "name" => $request->get('channel'),
                "user_id" => $user->_id
            ]);
        }

        // We got the channel in one way or another, now let's add a log to it
        $log = Log::create([
            'ms' => intval($request->get('ms')),
            'title' => $request->get('title'),
            'meta' => $request->get('meta', []),
            'channel_name' => $channel->name
        ]);

        $log->save();

        // we should of course keep track of channel's TMS on the go..
        $channel->tms+=$log->ms;
        $channel->save();

        return $log;
    }
}
