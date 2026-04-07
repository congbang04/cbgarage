<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
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
            'license_plate'     => 'required|string|max:20',
            'brand_id'          => 'required|exists:brands,id',
            'car_model_id'      => 'required|exists:car_models,id',
            'vin_number'        => 'nullable|string|max:50',
            'manufacture_year'  => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'color'             => 'required|string|max:50',
            'current_odo'       => 'required|numeric|min:0',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ];
    }
    public function messages()
    {
        return [
            'license_plate.required' => 'Biển số xe không được để trống',
            'license_plate.string' => 'Biển số xe phải là chuỗi ký tự',
            'license_plate.max' => 'Biển số xe quá dài',

            'brand_id.required' => 'Hãng xe không được để trống',
            'brand_id.exists' => 'Hãng xe không tồn tại',

            'car_model_id.required' => 'Dòng xe không được để trống',
            'car_model_id.exists' => 'Dòng xe không tồn tại',

            'vin_number.string' => 'VIN phải là chuỗi ký tự',
            'vin_number.max' => 'VIN quá dài',

            'manufacture_year.required' => 'Năm sản xuất không được để trống',
            'manufacture_year.digits' => 'Năm sản xuất phải có 4 chữ số',
            'manufacture_year.integer' => 'Năm sản xuất phải là số nguyên',
            'manufacture_year.min' => 'Năm sản xuất phải lớn hơn 1900',
            'manufacture_year.max' => 'Năm sản xuất phải nhỏ hơn hoặc bằng năm hiện tại',

            'color.required' => 'Màu xe không được để trống',
            'color.string' => 'Màu xe phải là chuỗi ký tự',
            'color.max' => 'Màu xe quá dài',

            'current_odo.required' => 'ODO không được để trống',
            'current_odo.numeric' => 'ODO phải là số',
            'current_odo.min' => 'ODO phải lớn hơn hoặc bằng 0',

            'image.image' => 'File phải là hình ảnh.',
            'image.mimes' => 'Chỉ hỗ trợ định dạng: jpeg, png, jpg, gif.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 5MB.',
        ];
    }
}
