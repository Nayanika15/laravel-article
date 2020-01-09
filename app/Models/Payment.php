<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;

class payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'article_id', 'user_id', 'amount', 'token_id', 'status', 'message'
    ];

    /**
     * Defining relationship with users table
     * 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Defining relationship with articles table
     * 
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * To store payment for article
     * @param int article id, string comment content
     */
    public function savePayment($data, $article_id, $status)
    {
        $article = Article::find($article_id);
        $result = array();
        
    	if(!empty($data) && !empty($article))
    	{  
            $payment         = new Payment();
            $payment->user()->associate(auth()->user()->id);
            $payment->amount = 100;

            //0 for error in trasaction, 1 for successful payment
            if($status == 0)
            {	

                $payment->status    = $status;
                $payment->token_id  = '';
                $payment->message   = $data->getMessage();

            }
            else
            {  
            	$payment->status    = $status;
                $payment->token_id  = $data['id'];
                $payment->message   = 'success';
            }

    		try 
    		{
    			$saved = $article->payments()->save($payment);

    		} 
    		catch (Exception $e) {
    			return redirect()->route('all-articles')
    				->withErrors($e->getMessage());
    		}
    	}
	}
}
