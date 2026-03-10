<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
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
            'service_id' => 'required|exists:services,id',
            'lock_id' => 'required|integer|exists:booking_locks,id',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'start_time' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ];
    }
    public function messages()
    {
        return [
            'service_id.required' => 'Vui lòng chọn 1 dịch vụ.',
            'service_id.exists'   => 'Dịch vụ không tồn tại.',

            'lock_id.required' => 'Vui lòng giữ chỗ trước khi đặt lịch.',
            'lock_id.exists' => 'Phiên giữ chỗ không hợp lệ. Vui lòng thử lại.',

            'contact_name.required' => 'Vui lòng nhập tên liên hệ.',
            'contact_phone.required' => 'Vui lòng nhập số điện thoại liên hệ.',

            'start_time.required' => 'Vui lòng chọn thời gian bắt đầu.',
        ];
    }
}
