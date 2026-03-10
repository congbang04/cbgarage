<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingLockRequest extends FormRequest
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
            'branch_id' => 'required|exists:branches,id',
            'service_id' => 'required|exists:services,id',
            'start_time' => 'required|date_format:Y-m-d H:i',
        ];
    }
    public function messages()
    {
        return [
            'branch_id.required' => 'Vui lòng chọn 1 chi nhánh.',
            'branch_id.exists'   => 'Chi nhánh không tồn tại.',

            'service_id.required' => 'Vui lòng chọn 1 dịch vụ.',
            'service_id.exists'   => 'Dịch vụ không tồn tại.',

            'start_time.required' => 'Vui lòng chọn thời gian bắt đầu.',
            'start_time.date_format' => 'Thời gian không đúng định dạng.',
        ];
    }
}
