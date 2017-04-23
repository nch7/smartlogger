<?php

namespace App\Http\Controllers\Dashboard;

use App\Channel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index (Request $request) {
        return view('dashboard.settings', [
            'user' => $request->user()
        ]);
    }

    public function refresh(Request $request) {
        $user = $request->user();
        $user->api_token = str_random(64);
        $user->save();

        return redirect(route('settings'));
    }

    public function update(Request $request) {
        $this->validate($request, [
            'ms_usd' => 'required|numeric'
        ]);

        $user = $request->user();
        $user->ms_usd = $request->get('ms_usd');
        $user->save();

        return redirect(route('settings'));
    }
}
