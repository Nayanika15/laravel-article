<?php

namespace App\Http\Requests;

use Authy\AuthyApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => (Auth::guard('api')->user() || auth()->check()) ? '':'required|max:50',
            'email'     => (Auth::guard('api')->user() || auth()->check()) ? '':'required|email|max:50',
            'mobile'    => (Auth::guard('api')->user() || auth()->check()) ? '':'required|max:10',
            'code'      => (Auth::guard('api')->user() || auth()->check()) ? '':'required',
            'comment'   => 'required' 
        ];
    }
}