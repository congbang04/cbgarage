<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|confirmed',

            'customer_name' => 'required|min:5|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được đăng ký',

            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 5 ký tự',
            'password.regex' => 'Mật khẩu phải có chữ hoa, chữ thường và số',

            'customer_name.required' => 'Tên không được để trống.',
            'customer_name.min' => 'Tên phải có ít nhất 5 ký tự.',
            'customer_name.max' => 'Dài quá số ký tự quy định.',
        ];
    }
}
