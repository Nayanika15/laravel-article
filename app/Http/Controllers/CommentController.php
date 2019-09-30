<?php

namespace App\Http\Controllers;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;

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
    		$comment = new Comment;
    		$result = $comment->saveComment($data,$id);
            if($result)
            {
                return redirect()->back()->with($result['msgType'], $result['msg']);
            }
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