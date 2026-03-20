<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'from_branch_id' => 'required|exists:branches,id',
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'items' => 'required|array|min:1',
            'items.*.part_id' => 'required|exists:parts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'from_branch_id.required' => 'Kho nguồn là bắt buộc',
            'to_branch_id.required' => 'Kho đích là bắt buộc',
            'to_branch_id.different' => 'Kho nguồn và kho đích không được giống nhau',
            'items.required' => 'Phải có ít nhất một mục',
            'items.*.part_id.required' => 'Chọn phụ tùng',
            'items.*.quantity.required' => 'Nhập số lượng',
            'items.*.quantity.min' => 'Số lượng phải >= 1',
        ];
    }
}
