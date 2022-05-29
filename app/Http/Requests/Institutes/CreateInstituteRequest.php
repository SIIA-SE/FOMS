<?php

namespace App\Http\Requests\Institutes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateInstituteRequest extends FormRequest
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
            'name' => 'required|min:10|max:255',
            'address' => 'required|regex:/[a-z,A-Z\d\s\/\-]/',
            'contact_no' => 'required|unique:institutes|size:9|regex:/\d/',
            'image' => ['image', Rule::dimensions()->maxWidth(1024)->maxHeight(256)]
        ];
    }

    public function messages()
    {
        return [
            'contact_no.regex' => 'Contact No. format is invalid',
        ];
    }
}
