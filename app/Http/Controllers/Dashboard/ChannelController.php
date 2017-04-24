<?php

namespace App\Http\Controllers\Dashboard;

use DB;
use App\Log;
use App\Channel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChannelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function view($id, Request $request) {

        $channel = $request->user()->channels()->findOrFail($id);
        
        $query =  $channel->logs()->orderBy('created_at', 'desc');

        if($request->has('title')) {
            $query->where('title', $request->get('title'));
        }

        $tms = $query->sum('ms'); 
        $logs = $query->paginate(100);

        $tmp = Log::raw(function($collection) use ($channel) {
            return $collection->aggregate(
                [
                    [
                        '$match' => [
                            'channel_name' => $channel->name
                        ]
                    ],
                    [
                        '$project'=> [
                            'day'=> [ '$dateToString' => [ 'format' => "%Y-%m-%d", 'date' => '$created_at' ] ],
                            'title' => '$title',
                            'ms' => '$ms'
                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => [ '$concat' => ['$day', '|', '$title']],
                            'total' => [
                                '$sum'  => '$ms'
                            ],
                            'count' => [
                                '$sum' => 1
                            ]
                        ]
                    ]
                ]
            );
        });

        $stats = $tmp->toArray();

        return view('dashboard.channel', ['channel' => $channel, 'logs' => $logs, 'tms'=>$tms, 'stats' => $stats]);
    }
}
