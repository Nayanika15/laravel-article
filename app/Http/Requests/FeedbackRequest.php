<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
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
           'name'       => auth()->check() ? '':'required|max:50',
            'email'     => auth()->check() ? '':'required|email|max:50',
            'mobile'    => auth()->check() ? '':'required|max:10',
            'code'      => auth()->check() ? '':'required',
            'message'   => 'required' 
        ];
    }
}
