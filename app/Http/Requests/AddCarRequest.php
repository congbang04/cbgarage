<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCarRequest extends FormRequest
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
            'brand_id' => 'required|exists:brands,id',
            'car_model_id' => 'required|exists:car_models,id',
            'license_plate' => 'required|unique:cars,license_plate',
            'manufacture_year' => 'required|integer|min:1900|max:2100',
            'color' => 'required|string|max:255',
            'current_odo' => 'required|integer|min:0',
            'vin_number' => 'nullable|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'brand_id.required' => 'Hãng xe không được để trống',
            'brand_id.exists' => 'Hãng xe không tồn tại',

            'car_model_id.required' => 'Dòng xe không được để trống',
            'car_model_id.exists' => 'Dòng xe không tồn tại',

            'license_plate.required' => 'Biển số xe không được để trống',
            'license_plate.unique' => 'Biển số xe đã tồn tại',

            'manufacture_year.required' => 'Năm sản xuất không được để trống',
            'manufacture_year.integer' => 'Năm sản xuất phải là số nguyên',
            'manufacture_year.min' => 'Năm sản xuất phải lớn hơn 1900',
            'manufacture_year.max' => 'Năm sản xuất phải nhỏ hơn 2100',

            'color.required' => 'Màu xe không được để trống',
            'color.string' => 'Màu xe phải là chuỗi ký tự',
            'color.max' => 'Màu xe quá dài',

            'current_odo.required' => 'ODO không được để trống',
            'current_odo.integer' => 'ODO phải là số nguyên',
            'current_odo.min' => 'ODO phải lớn hơn hoặc bằng 0'
        ];
    }
}
