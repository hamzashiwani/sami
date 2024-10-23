<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Event;

class StoreEventRequest extends FormRequest
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
            'description'  => 'required',
            'image'      => 'required|file|mimes:jpeg,jpg,png|max:5000',
           'date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:date',
            'date_check' => [
                'required',
                function ($attribute, $value, $fail) {
                    $exists = Event::where(function ($query) use ($value) {
                        $query->where('date', '<=', request('end_date'))
                              ->where('end_date', '>=', $value);
                    })->exists();
    
                    if ($exists) {
                        $fail('An event already exists between the selected dates.');
                    }
                },
            ],
        ];
    }
}
