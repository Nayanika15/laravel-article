<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;

use Illuminate\Contracts\Auth\UserProvider;

use Illuminate\Http\Request;
use App\Http\Requests\FeedbackRequest;
use App\Http\Controllers\MobileVerificationController;
use App\Models\Feedback;
use Authy\AuthyApi;
use Twilio\Rest\Client;

class FeedbackController extends Controller
{
    /**
	 * Function to fetch details of the requested article
	 * @param string slug
	 */
	public function add(FeedbackRequest $request)
	{
		$data = $request->validated();//to validate the data
        if($data)
        {	
        	//verify mobile number
        	$verify = new AuthyApi(config('app.twilio')['AUTHY_API_KEY']);
            try
            {
                $verification = $verify->phoneVerificationCheck($data['mobile'], '91', $data['code']);
                if($verification->ok())
            	{
            		$result = FeedBack::saveMessage($data);
	       			if($result)
	       			{	
	       				return response()->json([
	       					'message' => 'Feedback added successfully.' ], 200);
	       			}
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
        	return response()->json([
            'message' =>  'Invalid data provided.'
        	], 200);
        }		
	}

	/**
	 * call method to send verfication code to verify mobile
	 */
	public function verifyMobile($mobile)
	{
		if($mobile)
		{
			$result = (new MobileVerificationController)->SendCode($mobile);
			return response()->json([
	            'message' =>  $result
	        	], 200);
		}
	}
}
