<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBayRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'status' => 'required|string|in:available,in-use,maintenance,closed',
            'branch_id' => 'required|exists:branches,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên cầu nâng là bắt buộc',
            'name.max' => 'Tên cầu nâng không được vượt quá 50 ký tự',
            'status.required' => 'Trạng thái là bắt buộc',
            'status.in' => 'Trạng thái không hợp lệ',
            'type.required' => 'Loại cầu nâng là bắt buộc',
            'branch_id.required' => 'Chi nhánh là bắt buộc',
            'branch_id.exists' => 'Chi nhánh không tồn tại',
        ];
    }
}
