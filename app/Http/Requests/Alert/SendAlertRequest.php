<?php

namespace App\Http\Requests\Alert;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SendAlertRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'required|exists:orders,id',
            'medication_name' => 'required|string',
            'lot_number' => 'required|string',
        ];
    }
}
