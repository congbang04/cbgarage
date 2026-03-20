<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'actual_quantities' => 'required|array',
            'actual_quantities.*' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'actual_quantities.required' => 'Số lượng xuất là bắt buộc',
            'actual_quantities.*.integer' => 'Số lượng phải là số nguyên',
            'actual_quantities.*.min' => 'Số lượng không được âm',
        ];
    }
}
