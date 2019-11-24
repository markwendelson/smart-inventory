<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'supplier_code' => [
                'required',
                Rule::unique('supplier')->ignore($this->supplier)
            ],
            'supplier_name' => [
                'required',
                Rule::unique('supplier')->ignore($this->supplier)
            ]
        ];
    }

    public function messages()
    {
        return [
            'supplier_code.required' => 'Supplier code is required.',
            'supplier_name.required' => 'Supplier name is required.',
            'supplier_code.unique' => 'Supplier code already exist.',
            'supplier_name.unique' => 'Supplier name already exist.'
        ];
    }

}
