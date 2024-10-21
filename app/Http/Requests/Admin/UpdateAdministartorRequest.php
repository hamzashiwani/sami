<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdministartorRequest extends FormRequest
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
            'first_name' => 'required|max:32',
            'last_name'  => 'required|max:32',
            'phone'      => 'required|max:24',
            // 'type'      => 'required|in:0,1,2',
            // 'email'      => 'required|email:strict,filter|unique:admins|max:128',
            // 'password_confirmation' => 'required_if:password,!==,""|same:password',
        ];
    }
}
