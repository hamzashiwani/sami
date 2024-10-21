<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|max:100',
            'designation'  => 'required|max:100',
            'employee_id'      => 'required|unique:users|max:128',
            'passport_number'       => 'required|max:128',
            'division'       => 'required|max:128',
            'email'      => 'required|email:strict,filter|unique:users|max:128'
        ];
    }
}
