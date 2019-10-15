<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Twilio\Rest\Client;
use Authy\AuthyApi;

class MobileVerificationController extends Controller
{	
	protected $authy;
 	protected $sid;
 	protected $authToken;
 	protected $twilioFrom;

	public function __construct()
	{
	  // Initialize the Authy API and the Twilio Client
	  $this->authy = new AuthyApi(config('app.twilio')['AUTHY_API_KEY']);
	  // Twilio credentials
	  $this->sid = config('app.twilio')['TWILIO_ACCOUNT_SID'];
	  $this->authToken = config('app.twilio')['TWILIO_AUTH_TOKEN'];
	  $this->twilioFrom = config('app.twilio')['TWILIO_PHONE'];
	}
	/**
	 * Function to send verification code
	 */
    public function SendCode($mobile)
    {
    	//sending verification code by twilio
	    if(request()->ajax())
	    {	
	    	try
	    	{
        		$response = $this->authy->phoneVerificationStart($mobile, '91', 'sms');    	
		    	if($response->ok())
				{
					$msg = 'Verification code sent.';
				}
				else
				{
					$msg = 'Please enter a valid mobile number.';
				}

				return $msg; 
		    } 
		    catch (Exception $e)
		    {
		        report($e);
		        return false;
		    }
		}
	
    }
    /**
	 * Function to verify code sent to user
	 */
    public function VerifyCode($data)
    {	
    	$response = $this->authy->phoneVerificationCheck($request->mobile, '91', $request->code);
    	if($response->ok())
    	{
    		return 1;
    	}
    	else
    	{
    		return 0;
    	}
    }
            
}
