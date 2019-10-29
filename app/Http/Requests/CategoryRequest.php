<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_code' => [
                'required',
                Rule::unique('category')->ignore($this->category)
            ],
            'category_name' => [
                'required',
                Rule::unique('category')->ignore($this->category)
            ]
        ];
    }

    public function messages()
    {
        return [
            'category_code.required' => 'Category code is required.',
            'category_name.required' => 'Category name is required.',
            'category_code.unique' => 'Category code already exist.',
            'category_name.unique' => 'Category name already exist.'
        ];
    }

}
