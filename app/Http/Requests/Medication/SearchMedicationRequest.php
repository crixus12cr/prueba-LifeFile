<?php

namespace App\Http\Requests\Medication;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchMedicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Prepare data before validation by trimming the lot number.
     *
     * @return void
     */
    protected function prepareForValidation(): void {
        $this->merge([
            'lot_number' => trim($this->lot_number),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'lot_number' => 'required|string|max:50',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date'   => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'lot_number.required'    => 'The lot number is required.',
            'start_date.date_format' => 'Invalid date format (Y-m-d).',
            'end_date.after_or_equal'=> 'The end date must be greater than or equal to the start date.',
        ];
    }
}
