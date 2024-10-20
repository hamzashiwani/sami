<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRequest extends FormRequest
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
            // 'description'  => 'required',
            'topic'  => 'required|in:Global,Internal',
            // 'image'      => 'required|file|mimes:jpeg,jpg,png|max:5000',
            // 'date'  => 'required',
            // 'time'  => 'required',
            // 'attendance'  => 'required'
        ];
    }
}
