<?php

namespace App\Http\Controllers\Dashboard;

use App\Channel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
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
    public function index(Request $request) {
        $channels = Channel::with('logs')->orderBy('tms', 'desc')->paginate(25);
        return view('dashboard.index', ['channels' => $channels]);
    }
}
