<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sku' => 'required|string|max:255|unique:parts,sku',
            'name' => 'required|string|max:255',
            'price_sell' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'sku.unique' => 'Mã SKU đã tồn tại',
            'sku.required' => 'Mã SKU không được để trống',
            'name.required' => 'Tên phụ tùng không được để trống',
            'price_sell.required' => 'Giá bán không được để trống',
            'unit.required' => 'Đơn vị không được để trống',
        ];
    }
}
