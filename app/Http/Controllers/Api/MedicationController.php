<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\MedicationService;
use App\Http\Requests\Medication\SearchMedicationRequest;

class MedicationController extends Controller {
    /**
     * MedicationService instance.
     *
     * @var MedicationService
     */
    public MedicationService $medicationService;

    /**
     * Create a new MedicationController instance.
     *
     * @param MedicationService $medicationService
     */
    public function __construct(MedicationService $medicationService) {
        $this->medicationService = $medicationService;
    }

    /**
     * Search medication by lot number.
     *
     * @param SearchMedicationRequest $request
     * @return JsonResponse
     */
    public function search(SearchMedicationRequest $request): JsonResponse {
        try {
            return $this->medicationService->searchByLotNumber($request);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while trying to search medication',
                'errors' => [$e->getMessage()],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}