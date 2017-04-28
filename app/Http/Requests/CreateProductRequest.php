<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'product_name' => 'required',
            'product_desc' => 'required',
            'price' => 'required|integer',
            'condition' => 'required | alpha',
            'area_id' => 'required|integer',
            'subcategory_id' => 'required|integer',
            'brand_id' => 'required|integer'
        ];
    }
}