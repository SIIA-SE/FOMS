<?php

namespace App\Http\Requests\Visits;

use Illuminate\Foundation\Http\FormRequest;

class CreateVisitRequest extends FormRequest
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
            //Rules
            'branch' => 'required',
            'purpose' => 'required|regex:/[a-z,A-Z\d\s\/\-]/|min:2|max:255',
            'remarks' => 'nullable|regex:/[a-z,A-Z\d\s\/\-]/|min:2|max:255'
        ];
    }
}
