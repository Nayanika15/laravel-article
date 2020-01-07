<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DataTables;

use App\Jobs\SendMail;
use App\Mail\CommentMail;
use Illuminate\Support\Facades\Mail; 

class Comment extends Model
{	
	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comment', 'article_id', 'user_id', 'name', 'email', 'approve_status'
    ];

	/**
	 * Defining relationship with user
	 */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    /**
     * Defining relationship with article
     */
    public function article()
    {
    	return $this->belongsTo(Article::class);
    }

    /**
     * To store comment for article
     * @param int article id, string comment content
     */
    public static function saveComment($data,$id)
    {   
        $article = Article::find($id);
        $result = array();
    	if(!empty($data) && !empty($article))
    	{  
            $comment = new Comment(['comment' => $data['comment']]);
            if(auth()->check())
            {
                $comment->approve_status = '1';
                $comment->user()->associate(auth()->user()->id);                
                $result['msg']  = 'Comment was submitted successfully.';
            }
            else
            {  
                $comment->name      = $data['name'];
                $comment->email     = $data['email'];
                $comment->mobile    = $data['mobile'];
                $result['msg']      = 'Comment was submitted successfully and will be published after approval.';
            }
    		$article = Article::find($id);
			$saved = $article->comments()->save($comment);

    		if($saved)
    		{   
                //sending mail to user
                //Mail::to($article->user->email)->send(new CommentMail($saved));
                
                //adding mail to queue
                $commentDetails = new CommentMail($saved);
                SendMail::dispatch($article->user->email, $commentDetails);
    			$result['errFlag']  = 0;
                $result['msgType']  = 'success';
    		}
            else
            {
                $result['errFlag']  = 1;
                $result['msg']      = 'There is some error';
                $result['msgType']  = 'ErrorMessage';
            }
    	}
        else
        {
            $result['errFlag']  = 1;
            $result['msg']      = 'Article was not found.';
            $result['msgType']  = 'ErrorMessage';
        }

        return $result;
    }

    /**
     * get active comments
     * @param string $slug
     */
    public static function activeComments($slug)
    {
    return Article::where('slug', $slug)
        ->first()
        ->comments()
        ->where('approve_status', '1')
        ->get();
    }

    /**
     * All comments listing 
     */
    public static function allComments()
    {
       $comments = Comment::select(['id', 'comment', 'name', 'article_id', 'user_id', 'created_at', 'approve_status']);

        return Datatables::of($comments)
            ->addColumn('article_title', function($comments){
                 return $comments->article->title;
                })            
            ->editColumn('approve_status', function($comments){
                $status= $comments->approve_status;

                if($status == 0)
                {
                    return "<p class='text-danger'>Unpublished </p>";
                }
                elseif ($status == 1)
                {
                   return "<p class='text-success'> Published </p>";
                }
                elseif ($status == 1)
                {
                   return "<p class='text-warning'> Unapproved </p>";
                }
            })
            ->editColumn('user_id', function($comments){
                if($comments->user_id > 0)
                {
                    return $comments->user->name;
                }
                else
                {
                    return $comments->name;  
                }
            })
            ->editColumn('created_at', function($comments){
                return date("d-M-Y", strtotime($comments->created_at));
            })
            ->addColumn('action', function($comments){
                $approve_route=route('approve-comment',$comments->id);
                $status = $comments->approve_status;
                if($status == 0)
                {
                    return "<a href='" . $approve_route . "' class='btn btn-primary' onclick='return confirm(\"Are you sure?\")'>Approve</a>";
                }
                else
                {
                    return '';
                }
            })
            ->escapeColumns(['action'])
            ->make(true); 
    }
    /**
     * function to approve comment
     * @param int $id
     */
    public static function approveComment($id)
    {   
        $updated = Comment::find($id)->update(['approve_status'=> '1']);
        $result = array();
        
        if($updated)
        {   
            $result['msg']      = 'Comment approved successfully';
            $result['msgType']  = 'success'; 
        }
        else
        {
            $result['msg']      = 'There is some error';
            $result['msgType']  = 'ErrorMessage';
        }
        return $result;
    }
}
