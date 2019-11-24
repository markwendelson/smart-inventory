<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'item_code' => [
                'required',
                Rule::unique('item')->ignore($this->item)
            ],
            'item_name' => [
                'required',
                Rule::unique('item')->ignore($this->item)
            ]
        ];
    }

    public function messages()
    {
        return [
            'item_code.required' => 'Item code is required.',
            'item_name.required' => 'Item name is required.',
            'item_code.unique' => 'Item code already exist.',
            'item_name.unique' => 'Item name already exist.'
        ];
    }

}
