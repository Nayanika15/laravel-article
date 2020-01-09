<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;

use Illuminate\Contracts\Auth\UserProvider;
use App\Http\Requests\CommentRequest;
use App\Http\Controllers\MobileVerificationController;
use App\Models\Comment;
use Authy\AuthyApi;
use Twilio\Rest\Client;

class CommentController extends Controller
{	
	/**
	 * To store comment for a article
     * @param \App\Http\Requests\CommentRequest $request, int $id
     * @return \Illuminate\Contracts\Support\Renderable
	 */
    public function store(CommentRequest $request, $id)
    {
    	$data = $request->validated();//to validate the data
        if($data)
        {
            //to verify guest user with mobile number
            if(!Auth::guard('api')->user())
            {
                $verify = new AuthyApi(config('app.twilio')['AUTHY_API_KEY']);
                try
                {
                    $verification = $verify->phoneVerificationCheck($data['mobile'], '91', $data['code']);
                }
                catch (Exception $e)
                {
                    return response()->json([
	       					'message' => $e->getMessage() ], 200);
                }
            }

            //if user is registered or verified guest user
            if( Auth::guard('api')->user()|| (!(Auth::guard('api')->user()) && $verification->ok()))
            {
                if($data)
                {   
                    $result = Comment::saveComment($data,$id);                    
                    if($result)
                    {
                        return response()->json([
	       					'message' => $result['msg'] ], 200);
                    }
                }
            }

            //if verification failed
            else
            {
                return response()->json([
	       			'message' => 'User verification failed.' ], 200);
            }
        }
        else
        {
            return response()->json([
	       		'message' => 'Invalid data provided.' ], 200);
        }
    }

    /**
	 * API to list all Comments
	 */
	public function list()
	{	
		return response()->json(Comment::select(['id', 'comment', 'name', 'article_id', 'user_id', 'created_at', 'approve_status'])->get(), 200);
	}

	/**
	 * API to approve Comments
	 */
	public function approve(int $id)
	{	
		$result = Comment::approveComment($id);
		return response()->json(["msg" => $result['msg'], 200]);
	}
}
