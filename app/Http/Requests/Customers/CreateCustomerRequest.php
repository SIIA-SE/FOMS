<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
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
            'firstname' => 'required|regex:/[a-zA-Z]/|min:2',
            'lastname' => 'required|regex:/[a-zA-Z]/|min:1',
            'gender' => 'required',
            'nic_no' => 'required|unique:customers|regex:/[vV0-9]/|min:10|max:12',
            'address' => 'required|regex:/[a-z,A-Z\d\s\/\-]/',
            'contact_no' => 'required|size:9|regex:/\d/',
            'email' => 'email|nullable',
            'province' => 'required',
            'district' => 'required',
            'dsdivision' => 'required',
            'gndivision' => 'required'
        ];
    }
}
