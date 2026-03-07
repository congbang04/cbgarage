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
            'start_time' => 'required|date_format:Y-m-d H:i',
            'customer_name' => 'required|string|max:50|min:4',
            'customer_phone' => 'required|string|max:14|min:10',
            'license_plate' => 'nullable|string|max:9|regex:/^[A-Z0-9]{5,10}$/',
            'issue_description' => 'nullable|string|max:1000',
        ];
    }
    public function messages()
    {
        return [
            'service_id.required' => 'Vui lòng chọn dịch vụ.',
            'service_id.exists'   => 'Dịch vụ không hợp lệ.',

            'start_time.required' => 'Vui lòng chọn thời gian bắt đầu.',
            'start_time.date_format' => 'Thời gian không đúng định dạng.',

            'customer_name.required' => 'Vui lòng nhập tên khách hàng.',
            'customer_name.max'      => 'Tên quá dài.',
            'customer_name.min'      => 'Tên tối thiểu 4 ký tự.',

            'customer_phone.required' => 'Vui lòng nhập số điện thoại.',
            'customer_phone.max'      => 'Số điện thoại tối đa 14 số.',
            'customer_phone.min'      => 'Số điện thoại tối thiểu 10 số.',

            'license_plate.regex' => 'Biển số xe chỉ được gồm chữ in hoa và số (VD: 59D312345).',
            'license_plate.max'   => 'Biển số xe tối đa 9 ký tự.',

            'issue_description.max' => 'Mô tả lỗi tối đa 1000 ký tự.',
        ];
    }
}
