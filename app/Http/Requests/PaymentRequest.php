<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'order_id' => 'required|integer|exists:orders,id',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Mã đơn hàng là bắt buộc',
            'order_id.exists' => 'Đơn hàng không tồn tại',
            'reason.max' => 'Lý do không được vượt quá 255 ký tự',
            'notes.max' => 'Ghi chú không được vượt quá 500 ký tự',
        ];
    }
}
