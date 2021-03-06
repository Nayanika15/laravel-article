<?php

namespace App\Http\Controllers;
use App\Http\Requests\CommentRequest;
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
                    $result = Comment::saveComment($data,$id);                    
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list()
    {
       if(request()->ajax())
        {  
            return Comment::allComments();
        }

        return view('site.wordify.comments.list');
    }

   /**
     * get active comments
     * @param int $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function approve($id)
    {
        $result = Comment::approveComment($id);
        return redirect()->route('all-comments')->with($result['msgType'], $result['msg']);
    }    

}