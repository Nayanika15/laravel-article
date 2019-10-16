<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
#use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Mail\RegistrationMailAdmin;
use App\Mail\RegistrationMailUser;
use Illuminate\Support\Facades\Mail;

class User extends \Illuminate\Foundation\Auth\User
{
  use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'mobile', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * defining relationship with comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class); 
    }
    /**
     * Defining relationship with payments table
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * to register new user
     */
    public function registerUser($data)
    {   
        $result = array();


        if (!empty($data))
        {   
            $user = new User;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->mobile = $data['mobile'];
            $user->password = Hash::make($data['password']);
            $saved = $user->save();
            
            if($saved)
            {   
                $admin = User::where('is_admin', '1')->first();
                Mail::to($admin->email)->send(new RegistrationMailAdmin($user));
                Mail::to($user->email)->send(new RegistrationMailUser($user));
                $result['errFlag']= 0;
                $result['msg']= 'Registration completed successfully.Please login to go to your dashboard.';
                $result['route']= 'login';
                
            }
            else
            {   
                $result['errFlag'] = 1;
                $result['msg'] = 'There is some error.';
                $result['route'] = 'do-registration';
            }
            
        }
        
        return $result;
    }
    /**
     * to add mobile number to users details if not provided
     */
    public function addMobile($data)
    {   
        $result = array();

        if (!empty($data))
        {   
            $user = User::find(auth()->user()->id);
            $user->mobile = $data['mobile'];
            $saved = $user->save();
            
            if($saved)
            {   
                $result['errFlag']= 0;
                $result['msg']= 'Registration completed successfully.Please login to go to your dashboard.';
                $result['route']= 'login';
                
            }
            else
            {   
                $result['errFlag'] = 1;
                $result['msg'] = 'There is some error.';
                $result['route'] = 'add-phone';
            }
            
        }
        
        return $result;
    }
}