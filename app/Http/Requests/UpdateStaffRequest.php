<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return auth()->check() && auth()->user()->role === 'admin';
        return true;
    }

    public function rules(): array
    {
        $staffId = $this->route('staff')?->id ?? $this->input('id');

        return [
            'email' => ['required', 'email', Rule::unique('users')->ignore($staffId)],
            'role' => ['required', Rule::in(['TECHNICIAN', 'ADVISOR', 'WAREHOUSE', 'CASHER'])],
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
