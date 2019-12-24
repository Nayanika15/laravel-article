<?php

namespace App\Http\Controllers\Api;
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Authy\AuthyApi;
use Validator;
use App\Http\Requests\UserRequest;
use Socialite;



class UserController extends Controller
{
    public $successStatus = 200;
    /**
     * Api for login
     */
    public function login()
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user();
            $result['token'] =  $user->createToken('MyApp')-> accessToken;
            $result['isAdmin'] = $user->is_admin;
            return response()->json([
            'message' => 'Success',
            'result' => $result
        	], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
    /** 
     * Register user api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(UserRequest $request) 
    {
        $data = $request->validated();//to validate the data
		if ($data)
		{   
            $verify = new AuthyApi(config('app.twilio')['AUTHY_API_KEY']);
            try
            {
                $verification = $verify->phoneVerificationCheck($data['mobile'], '91', $data['code']);
                if($verification->ok())
                {
                    $result = User::registerUser($data);
                    if($result['errFlag'] == 0)
                    {   
                        $user = $result['user'];
                        $success['token'] =  $user->createToken('MyApp')->accessToken; 
                        $success['name'] =  $user->name;
                        $success['message'] = 'User registered successfully.';
                    }
                    else
                    {
                        $success['message'] = 'There is some error please try again later.';
                    }

                    return response()->json([$success, 200]);
                }

                else
                {
                    return response()->json([
                        'message' =>  'Only verfied users can register.'
                        ], 200);
                }
            }

            catch (Exception $e)
            {
                return response()->json([
                    'message' =>  $e->getMessage()
                    ], 200);   
            }

        }
		else
        {
            return response()->json(['error'=>$data->errors()], 401); 
        }
	}
	/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus); 
    }
    /**
     * Api reset password of the user
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
                return response()->json(['msg'=> $e->getMessage(), 'route' => 'reset-password'], 200);
            }

             //if user is verified 
            if($verification->ok())
            {
                $result = User::resetPassword($validatedData);
                if($result)
                { 
                    return response()->json(['msg'=> $result['msg'], 'route' => $result['route']], 200);
                }
            }
            else
            {
                return response()->json(['msg'=> 'Incorrect verification code entered.', 'route' => 'reset-password'], 200);
            }
        }
        else
        {
            return response()->json(['msg'=> 'Enter valid data.', 'route' => 'reset-password'], 200);
        }
        
    }
    public function SocialSignup($provider)
    {
        // Socialite will pick response data automatic 
        $user = Socialite::driver($provider)->with(['redirect_uri' => "http://article.com/callback/google"])->stateless()->user();
        dd($user);
        //dd(Socialite::driver('google')->stateless()->user('phonenumber'));
        return response()->json($user);
    }
}
