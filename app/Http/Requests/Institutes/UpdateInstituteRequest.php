<?php

namespace App\Http\Requests\Institutes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstituteRequest extends FormRequest
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
            'name' => ['required', Rule::unique('institutes')->ignore($this->institute->id)],
            'address' => 'required|regex:/[a-zA-z0-9-.,\/\s]/',
            'contact_no' => 'required|size:9|regex:/\d/'

        ];
    }

    public function messages()
    {
        return [
            'contact_no.regex' => 'Contact No. format is invalid',
        ];
    }
}
