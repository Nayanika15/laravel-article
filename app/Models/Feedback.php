<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use App\Mail\FeedbackMail;
use App\Mail\FeedbackMailAdmin;
use Illuminate\Support\Facades\Mail;
class Feedback extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message', 'name', 'mobile', 'email'
    ];
    protected $table = 'feedbacks';

    /**
     * To store feedback
     * @param string feedback content
     */
    public static function saveMessage($data)
    {
    	$feedback = new Feedback();
    	$feedback->name = $data['name'];
        $feedback->email = $data['email'];
        $feedback->mobile = $data['mobile'];
        $feedback->message = $data['message'];
        if(Auth::guard('api')->user())
        {
            $feedback->user_id = Auth::guard('api')->user()->id;
        }
        
        
        $saved = $feedback->save();

        if($saved)
        {   
            Mail::to($feedback->email)->send(new FeedbackMail($feedback));

            $admin = User::where('is_admin', '1')->first();
            Mail::to($admin->email)->send(new FeedbackMailAdmin($feedback));
        	$message = 'Feedback was submitted successfully.';
        }
        else
        {
        	$message = 'There is some error while adding the data.';
        }

        return $message;

    }
}
