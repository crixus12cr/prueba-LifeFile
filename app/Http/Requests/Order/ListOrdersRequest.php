<?php

namespace App\Http\Requests\Order;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ListOrdersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        if (!auth('sanctum')->check()) {
            return false;
        }
        return true;
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization() {
        throw new HttpResponseException(response()->json([
            'message' => 'Unauthenticated',
            'errors' => ['Authentication required'],
        ], JsonResponse::HTTP_UNAUTHORIZED));
    }

    /**
     * Set default start and end dates if they are not provided.
     *
     * @return void
     */
    protected function prepareForValidation(): void {
        if(!$this->filled('start_date') && !$this->filled('end_date')) {
            $this->merge([
                'start_date' => now()->subDays(30)->format('Y-m-d'),
                'end_date' => now()->format('Y-m-d'),
            ]);
        }
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
}
