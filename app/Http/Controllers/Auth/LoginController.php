<?php

namespace App\Http\Controllers\Auth;


use Auth;
use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    function index() {
        return view('auth.login');
    }

    function login(Request $request) {
        
        $result = Auth::attempt([
            'email' => $request->get('email'), 
            'password' => $request->get('password')
        ]);
        
        if ($result) {
            return redirect(route('dashboard'));
        }

        dd("invalid password");
    }

    function logout() {
        Auth::logout();
        return redirect(route('home'));
    }
}
