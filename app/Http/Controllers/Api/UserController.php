<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Requests\UserRequest;

use Authy\AuthyApi;

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
                        'message' =>  'Only verfied users can send feedback.'
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
     * To reset password of the user
     */
    public function updatePassword(Request $request)
    {   
        $validatedData = Validator::make($request->all(), 
         [
            'mobile'               => 'required|max:10',
            'code'                 => 'required',
            'password'             => 'required|confirmed',
            'password_confirmation'=> 'required'
        ]);
        
        if(!$validatedData->fails())
        {
            //to verify user with mobile number
            $verify = new AuthyApi(config('app.twilio')['AUTHY_API_KEY']);
            try
            {
               $verification = $verify->phoneVerificationCheck($request['mobile'], '91', $request['code']);
            }
            catch (Exception $e)
            {
               return response()->json(['msg' => $e->getMessage()], 200);
            }

             //if user is verified 
            if($verification->ok())
            {
               $result = User::resetPassword($request);
               if($result['errFlag'] == 0)
               { 
                 return response()->json($result, 200);
               }
               else
               {
                 return response()->json($result, 200);
               }
            }
            else
            {
               return response()->json(['msg' => 'Incorrect verification code entered.'], 200);
            }
        }
        else
        {   
            $errMsg = array();
            foreach ($validatedData->errors()->getMessages() as $item) {
                array_push($errMsg, $item);
            }
            return response()->json(['msg' => 'Enter valid data', 'error'=> $errMsg], 400);
        }        
    }
}
