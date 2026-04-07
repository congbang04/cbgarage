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
            'name' => 'required|string|min:5|max:255',
            'phone' => 'nullable|digits_between:10,15',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống.',
            'name.min' => 'Tên phải có ít nhất 5 ký tự.',
            'name.max' => 'Dài quá số ký tự quy định.',

            'phone.digits_between' => 'Số điện thoại sai định dạng.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',

            'address.string' => 'Địa chỉ không hợp lệ.',
            'address.max' => 'Dài quá số ký tự quy định.',

            'image.image' => 'File phải là hình ảnh.',
            'image.mimes' => 'Chỉ hỗ trợ định dạng: jpeg, png, jpg, gif.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 5MB.',
        ];
    }
}
