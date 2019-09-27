<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('site.wordify.login');
    }

    /**
     * Authenicate the data provided.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(UserRequest $request)
    {   
        $data = $request->validated();
        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials))
        {
            return redirect()->intended('dashboard');
        }
        else
        {
            return back()->withInput()->with('ErrorMessage','Invalid User Credentials.');
        }
    }
    /**
     * To logout the user
     * @return \Illuminate\Http\Response
     */
    public function logoutUser()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('site.wordify.register');
    }

     /**
     * Store or update user details to the database.
     *
     * @param \App\Http\Requests\CategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(UserRequest $request) 
    {   
        $user = new User;
        $result = $user->registerUser($request);

        if($result['errFlag'] == 0)
        {   
            return redirect()->route($result['route'])
                        ->with('success', $result['msg']);
        }
        else
        {
            return redirect()->route($result['route'])
                    ->with('ErrorMessage', $result['msg'])
                    ->withInput();
        }
    }

       public function dashboard()
    {   
        return view('site.wordify.dashboard');
    }
}
