<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

use Twilio\Rest\Client;
use Authy\AuthyApi;

use Socialite;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('site.wordify.login');
    }

    /**
     * Authenicate the data provided.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {   
        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials))
        {
            return redirect()->intended('dashboard');
        }
        else
        {
            return back()->withInput()->with('ErrorMessage', 'Invalid User Credentials.');
        }
    }
    /**
     * To logout the user
     * @return \Illuminate\Http\Response
     */
    public function logoutUser()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('site.wordify.register');
    }

     /**
     * Store or update user details to the database.
     *
     * @param \App\Http\Requests\UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(UserRequest $request) 
    {   
        $user = new User;
        $data = $request->validated();//to validate the data

        if(!empty($data))
        {   
            //to verify user with mobile number
            $verify = new AuthyApi(config('app.twilio')['AUTHY_API_KEY']);
            try
            {
                $verification = $verify->phoneVerificationCheck($data['mobile'], '91', $data['code']);
            }
            catch (Exception $e)
            {
                report($e);
                return false;   
            }

             //if user is verified 
            if($verification->ok())
            {
                $result = User::registerUser($data);

                if($result['errFlag'] == 0)
                {   
                    return redirect()->route($result['route'])
                        ->with('success', $result['msg']);
                }
                else
                {
                    return redirect()->route($result['route'])
                        ->with('ErrorMessage', $result['msg'])
                        ->withInput();
                }
            }
            //if verification failed
            else
            {
                return redirect()->route('do-registration')
                    ->with('ErrorMessage', 'Mobile verification failed.')
                    ->withInput();
            }
        }
        //if data is invalid
        else
        {
            return redirect()->route('register')
                ->with('ErrorMessage', 'Enter valid details.')
                ->withInput();
        }
        
    }
    /**
     * To redirect to dashboard page
     * @return \Illuminate\Http\Response
     */
       public function dashboard()
    {   
        return view('site.wordify.dashboard');
    }
    /**
     * Redirect User for social login.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect($service) {
        return Socialite::driver ( $service )->redirect ();
    }

    /**
     * Obtain the user information from social login.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback($service) {
        $user = Socialite::with ( $service )->stateless()->user ();
        return view ( 'dashboard' )->withDetails ( $user )->withService ( $service );
    }
}
