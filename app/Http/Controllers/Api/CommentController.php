<?php

namespace App\Http\Controllers\Api;
use Authy\AuthyApi;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Twilio\Rest\Client;

class CommentController extends Controller
{	
	/**
	 * To store comment for a article
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
                    report($e);
                    return false;
                }
            }

            //if user is registered or verified guest user
            if(Auth::guard('api') || (!(Auth::guard('api')) && $verification->ok()))
            {
                if($data)
                {   
                    $result = Comment::saveComment($data,$id);                    
                    if($result)
                    {
                    	return response()->json(['msg'=> $result['msg']], 200);
                    }
                }
            }

            //if verification failed
            else
            {
                return response()->json(['msg'=> 'User verification failed.'], 200);
            }
        }
        else
        {
            return response()->json(['msg'=> 'Invalid data provided.'], 200);
        }
    }

    /**
     * Api show all comments
     */
    public function list()
    {	
    	$comments = Comment::select(['id', 'comment', 'name', 'article_id', 'user_id', 'created_at', 'approve_status'])->get()
    		->each(function ($item, $key) 
			{
			    $item['created_date'] = date('d-M-y', strtotime($item['created_at']));
			    $item['user_name'] = ($item->name == '') ? $item->user->name : $item->name;
    		});
       return response()->json($comments, 200);
    }

   /**
     * Api to approve comment
     */
    public function approve($id)
    {
        $result = Comment::approveComment($id);
        return response()->json(['msg'=> $result['msg']], 200);
    }    

}