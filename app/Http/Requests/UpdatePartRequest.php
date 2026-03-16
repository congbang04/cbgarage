<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePartRequest extends FormRequest
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
        $partId = $this->route('part')?->id ?? $this->route('part');

        return [
            'sku' => 'nullable|string|max:255|unique:parts,sku,' . $partId,
            'name' => 'nullable|string|max:255',
            'price_sell' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'sku.unique' => 'Mã SKU đã tồn tại',
        ];
    }
}
