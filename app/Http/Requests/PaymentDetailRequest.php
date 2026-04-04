<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentDetailRequest extends FormRequest
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
            'payment_method' => 'sometimes|in:cash,bank_transfer,card',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Mã đơn hàng là bắt buộc',
            'order_id.exists' => 'Đơn hàng không tồn tại',
            'payment_method.in' => 'Hình thức thanh toán không hợp lệ',
        ];
    }
}
