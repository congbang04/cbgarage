<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTechnicianJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Verify user is a technician
        // return auth()->user()->role === 'technician' && auth()->user()->technician !== null;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                'in:' . implode(',', [
                    Order::STATUS_IN_PROGRESS,
                    Order::STATUS_WAITING_PART,
                    Order::STATUS_COMPLETED
                ])
            ],
            'note' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Trạng thái là bắt buộc',
            'status.in' => 'Trạng thái không hợp lệ',
            'note.max' => 'Ghi chú không được vượt quá 1000 ký tự',
        ];
    }
}
