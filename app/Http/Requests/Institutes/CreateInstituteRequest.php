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
            'name' => 'required|unique:institutes',
            'address' => 'required|regex:/[a-zA-z0-9-.,\/\s]/',
            'contact_no' => 'required|numeric',
            'image' => ['image', Rule::dimensions()->maxWidth(1024)->maxHeight(256)]

        ];
    }
}
