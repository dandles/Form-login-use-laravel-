<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Students;

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
    protected $redirectTo = '/home';

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
   
     public function getLogin()
    {
        return view('auth/login');
    }
     public function login(Request $request)
    {
       //  //echo 'uname '.$req->input('username');
         $email = '';$msg ='';
       
        if (filter_var($request->input ('username'), FILTER_VALIDATE_EMAIL)){
            $data =Students::where('email', $request->input('username'))->where('password', '=', md5($request->input('password')))->distinct()->get()->toArray();
        }else {
            $data =Students::where('username', $request->input('username'))->where('password', '=', md5($request->input('password')))->distinct()->get()->toArray();
        }
        if (!empty($data)){
            $request->session()->put("email",$data[0]['email']);
            $request->session()->put("fullname",$data[0]['fullname']);
            return view('welcome')->with('data',$request->session()->put('username'));
        }
        $msg = 'Data not found in our database!!!';
        $request->session()->put("msg",$msg);
        return redirect()->route('login')->with('msg',$msg);
    }
}
