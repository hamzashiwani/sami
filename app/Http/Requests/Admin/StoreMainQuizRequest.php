<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMainQuizRequest extends FormRequest
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
            'title' => 'required|max:32',
            // 'tags' => 'required',
            'description'  => 'required',
            // 'image'      => 'required|file|mimes:jpeg,jpg,png|max:5000',
            // 'date'  => 'required',
            // 'time'  => 'required',
            // 'attendance'  => 'required'
        ];
    }
}