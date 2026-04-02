<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceptionRequest extends FormRequest
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
            // CUSTOMER
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',

            // CAR
            'license_plate' => 'required|string|max:20',
            'brand_id' => 'nullable|exists:brands,id',
            'car_model_id' => 'nullable|exists:car_models,id',
            'vin_number' => 'nullable|string|max:50',
            'manufacture_year' => 'required|digits:4',
            'color' => 'required|string|max:50',

            // RECEPTION INFO
            'current_odo' => 'required|integer|min:0',
            'fuel_range' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string',
            'note' => 'nullable|string',

            'booking_id' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            // customer
            'name.required' => 'Vui lòng nhập họ tên khách hàng',
            'phone.required' => 'Vui lòng nhập số điện thoại',

            // car
            'license_plate.required' => 'Vui lòng nhập biển số xe',
            'manufacture_year.required' => 'Vui lòng nhập năm sản xuất',
            'manufacture_year.digits' => 'Năm sản xuất phải gồm 4 chữ số',
            'color.required' => 'Vui lòng nhập màu xe',

            // relation
            'brand_id.exists' => 'Hãng xe không hợp lệ',
            'car_model_id.exists' => 'Dòng xe không hợp lệ',

            // odo
            'current_odo.required' => 'Vui lòng nhập số ODO',
            'current_odo.integer' => 'Số ODO phải là số',
            'current_odo.min' => 'Số ODO không hợp lệ',

            // fuel
            'fuel_range.integer' => 'Mức nhiên liệu không hợp lệ',
            'fuel_range.min' => 'Mức nhiên liệu phải từ 0 - 100',
            'fuel_range.max' => 'Mức nhiên liệu phải từ 0 - 100',
        ];
    }
}
