<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LastLoginController extends Controller
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->user = new User;
    }
    public function login(Request $request)
    {
        $validator = array("password"=>__('Unauthorized'));
        $user = User::where('oldusername', $request->username)
            ->where('oldpassword', $request->password)
            ->first();
        if ($user) {
            return view('auth.registerolduser',compact('user'));
        } else {
            return redirect()->route('lastuser')->withErrors($validator);
        }
    }

    public function ajaxLogin(Request $request)
    {
        //dd($request);
        // Check validation
        $this->validate($request, [
            'mobile' => 'required|regex:/[0-9]{10}/|digits:11',
        ]);
        // Get user record
        $user = User::where('mobile', $request->get('mobile'))->first();
        if ($user) {

            // Check Condition Mobile No. Found or Not
            if (!Hash::check($request->get('password'), $user->password)) {
                $validator = array("mobile"=>__('Unauthorized'));
                \Session::put('errors2', 'Your mobile number not match in our system..!!');
                $success = array("success"=>false);
                return response()->json([$success , $validator] , 500) ;
                //return back();
            }
            // Set Auth Details
            \Auth::login($user);
            $success = array("success"=>true);
            return response()->json([$success] , 200) ;

        } else {
            \Session::put('errors', 'Your mobile number not match in our system..!!');
            $validator = array("mobile"=>__('Unauthorized'));
            $success = array("success"=>false);
            return response()->json([$success , $validator] , 500) ;
            //return redirect()->route('home');
        }

    }

    public function update(Request $request)
    {

        $user = User::All()->find($request->userid);
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        //x dd($user);
        \Auth::login($user);
        return redirect()->route('home');
    }

}
