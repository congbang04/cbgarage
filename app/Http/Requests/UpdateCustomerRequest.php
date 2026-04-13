<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $customerId = \App\Models\Customer::where('user_id', auth()->id())->value('id');

        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'phone' => ['required', 'digits_between:10,15', Rule::unique('customers', 'phone')->ignore($customerId)],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên khách hàng bắt buộc',
            'name.min' => 'Tên phải có ít nhất 3 ký tự',
            'name.max' => 'Tên không được vượt quá 255 ký tự',

            'phone.required' => 'Số điện thoại bắt buộc',
            'phone.digits_between' => 'Số điện thoại phải từ 10-15 chữ số',
            'phone.unique' => 'Số điện thoại này đã tồn tại',

            'email.email' => 'Email không hợp lệ',
            'email.max' => 'Email không được vượt quá 255 ký tự',

            'address.string' => 'Địa chỉ không hợp lệ',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự',

            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Chỉ hỗ trợ định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 5MB',
        ];
    }
}
