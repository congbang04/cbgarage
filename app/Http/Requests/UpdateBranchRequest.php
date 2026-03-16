<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
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
            'name' => 'nullable|string|max:255|min:3',
            'address' => 'nullable|string|max:255|min:5',
            'phone' => 'nullable|string|max:20|regex:/^[0-9\-\+\s\(\)]{5,20}$/',
            'is_central_warehouse' => 'sometimes|in:0,1',
            'coordinates' => 'nullable|string|max:255|regex:/^(-?\d+\.\d+),(-?\d+\.\d+)$/',
            'is_actived' => 'sometimes|in:0,1',
        ];
    }

    /**
     * Get the validated data from the request.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        
        // Convert string boolean values to actual booleans
        if (isset($validated['is_actived'])) {
            $validated['is_actived'] = (bool) $validated['is_actived'];
        }
        if (isset($validated['is_central_warehouse'])) {
            $validated['is_central_warehouse'] = (bool) $validated['is_central_warehouse'];
        }
        
        // Remove null values to avoid overwriting existing data
        $validated = array_filter($validated, fn($value) => $value !== null && $value !== '');
        
        return is_null($key) ? $validated : ($validated[$key] ?? $default);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.min' => 'Tên chi nhánh phải có ít nhất 3 ký tự',
            'name.max' => 'Tên chi nhánh không được vượt quá 255 ký tự',
            'address.min' => 'Địa chỉ phải có ít nhất 5 ký tự',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'coordinates.regex' => 'Tọa độ không hợp lệ. Định dạng: latitude,longitude',
        ];
    }
}
