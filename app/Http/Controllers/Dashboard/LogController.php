<?php

namespace App\Http\Controllers\Dashboard;

use App\Log;
use App\Channel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogController extends Controller
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
    public function view($id) {
        $log = Log::find($id);

        return view('dashboard.logs.view', ['log' => $log]);
    }
}
