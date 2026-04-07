<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', Rule::in(['TECHNICIAN', 'ADVISOR', 'WAREHOUSE', 'CASHIER', 'BRANCH_ADMIN'])],
            'branch_id' => ['required', 'exists:branches,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'role.required' => 'Vai trò bắt buộc',
            'role.in' => 'Vai trò không hợp lệ',
            'branch_id.required' => 'Chi nhánh bắt buộc',
            'branch_id.exists' => 'Chi nhánh không tồn tại',
        ];
    }
}
