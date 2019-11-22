<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

use Twilio\Rest\Client;
use Authy\AuthyApi;

use Socialite;

use App\Mail\RegistrationMailAdmin;
use App\Mail\RegistrationMailSocialUser;
use Illuminate\Support\Facades\Mail;

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
     * To redirect to dashboard page
     * @return \Illuminate\Http\Response
     */
       public function update()
    {   
        return view('site.wordify.add-mobile');
    }

    /**
     *To update the mobile number if not provided 
     */
    public function updateMobile(Request $request)
    {   
        $validatedData = $request->validate([
            'mobile' => 'required|unique:users|max:10',
            'code' => 'required',
        ]);

        if(!empty($validatedData))
        {
            //to verify user with mobile number
            $verify = new AuthyApi(config('app.twilio')['AUTHY_API_KEY']);
            try
            {
                $verification = $verify->phoneVerificationCheck($validatedData['mobile'], '91', $validatedData['code']);
            }
            catch (Exception $e)
            {
                return redirect()->route('add-phone')
                    ->withErrors($e->getMessage());   
            }

             //if user is verified 
            if($verification->ok())
            {   
                $result = User::addMobile($validatedData);

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
                return redirect()->route('add-phone')
                    ->with('ErrorMessage', 'Mobile verification failed.')
                    ->withInput();
            }
        }
        else
        {
            return redirect()->route('add-phone')
                    ->with('ErrorMessage', 'Enter valid data.')
                    ->withInput();
        }
        
    }
       
    /**
     * Redirect User for social login.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from social login.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback() {
        
        try 
        {
            $user = Socialite::driver('google')->user('phonenumber');
        } 
        catch (\Exception $e)
        {
            return redirect('/login')->withError($e->getMessage());
        }
        
        // check if it is an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser)
        {
            // log them in
            auth()->login($existingUser, true);
        } 
        else
        {
            // create a new user
            $newUser                  = new User;
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $password                 = Str::random(8);
            $newUser->password        = Hash::make($password);
            $newUser->save();
            auth()->login($newUser, true);
            $admin = User::where('is_admin', '1')->first();

            $mailData = array('user' => $newUser, 'password'=> $password);

            Mail::to($admin->email)->send(new RegistrationMailAdmin($newUser));
            Mail::to($user->email)->send(new RegistrationMailSocialUser($mailData));
        }
        return redirect()->to('/dashboard');
    }

    /**
     * Display reset password page.
     */
    public function forgotPassword()
    {
        return view('site.wordify.forgot-password');
    }

    /**
     * To reset password of the user
     */
    public function updatePassword(Request $request)
    {   
        $validatedData = $request->validate([
            'mobile'               => 'required|max:10',
            'code'                 => 'required',
            'password'             => 'required|confirmed',
            'password_confirmation'=> 'required'
        ]);

        if(!empty($validatedData))
        {   
            //to verify user with mobile number
            $verify = new AuthyApi(config('app.twilio')['AUTHY_API_KEY']);
            try
            {
                $verification = $verify->phoneVerificationCheck($validatedData['mobile'], '91', $validatedData['code']);
            }
            catch (Exception $e)
            {
                return redirect()->route('forgot-password')
                    ->withErrors($e->getMessage());   
            }

             //if user is verified 
            if($verification->ok())
            {
                $result = User::resetPassword($validatedData);

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
            else
            {
                return redirect()->route('forgot-password')
                        ->with('ErrorMessage', 'Incorrect verification code entered.')
                        ->withInput();
            }
        }
        else
        {
            return redirect()->route('forgot-password')
                    ->with('ErrorMessage', 'Enter valid data.')
                    ->withInput();
        }
        
    }
}