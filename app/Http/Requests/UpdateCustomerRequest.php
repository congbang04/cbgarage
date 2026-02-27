<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'name' => 'nullable|string|min:5|max:255',
            'phone' => 'nullable|digits_between:10,15',
            'address' => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'name.min' => 'Tên phải có ít nhất 5 ký tự.',
            'name.max' => 'Dài quá số ký tự quy định.',

            'phone.digits_between' => 'Số điện thoại sai định dạng.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',

            'address.string' => 'Địa chỉ không hợp lệ.',
            'address.max' => 'Dài quá số ký tự quy định.',
        ];
    }
}
