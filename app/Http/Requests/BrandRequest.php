<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
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
            'brand_code' => [
                'required',
                Rule::unique('brand')->ignore($this->brand)
            ],
            'brand_name' => [
                'required',
                Rule::unique('brand')->ignore($this->brand)
            ]
        ];
    }

    public function messages()
    {
        return [
            'brand_code.required' => 'Brand code is required.',
            'brand_name.required' => 'Brand name is required.',
            'brand_code.unique' => 'Brand code already exist.',
            'brand_name.unique' => 'Brand name already exist.'
        ];
    }

}
