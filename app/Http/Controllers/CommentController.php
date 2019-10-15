<?php

namespace App\Http\Controllers;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;

use Twilio\Rest\Client;
use Authy\AuthyApi;

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
            if(!auth()->check())
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
            if( auth()->check() || (!(auth()->check()) && $verification->ok()))
            {
                if($data)
                {   
                    $comment = new Comment();
                    $result = $comment->saveComment($data,$id);                    
                    if($result)
                    {
                        return redirect()->back()->with($result['msgType'], $result['msg']);
                    }
                }
            }
            //if verification failed
            else
            {
                return redirect()->back()
                    ->with('ErrorMessage', 'Only verified user can submit comment.')
                    ->withInput();
            }
        }
        else
        {
            return redirect()->back()
                ->with('ErrorMessage', 'Invalid data provided.')
                ->withInput();
        }
    }

    /**
     * To show all comments
     */
    public function list()
    {
       if(request()->ajax())
        {  
            $comments = new Comment;
            $data = $comments->allComments();
            return $data;
        }
        return view('site.wordify.comments.list');
    }

   /**
     * get active comments
     */
    public function approve($id)
    {
        $comments = new Comment;
        $result = $comments->approveComment($id);
        return redirect()->route('all-comments')->with($result['msgType'], $result['msg']);
    }    

}