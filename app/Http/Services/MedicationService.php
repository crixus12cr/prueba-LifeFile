<?php

namespace App\Http\Services;

use Illuminate\Http\JsonResponse;
use App\Http\Repositories\MedicationRepository;
use Illuminate\Http\Request;

class MedicationService {
    /**
     * MedicationRepository instance.
     *
     * @var MedicationRepository
     */
    public MedicationRepository $medicationRepository;

    /**
     * Create a new MedicationService instance.
     *
     * @param MedicationRepository $medicationRepository
     */
    public function __construct(MedicationRepository $medicationRepository) {
        $this->medicationRepository = $medicationRepository;
    }
    
    /**
     * Search medication by lot number.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchByLotNumber(Request $request): JsonResponse {
        $medication = $this->medicationRepository->findByLotNumber($request->lot_number);
        
        if(!$medication) {
            return response()->json([
                'message' => 'Medication not found',
                'errors' => ['No medication found with lot number: ' . $request->lot_number],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        return response()->json([
            'data' => [
                'id' => $medication->id,
                'name' => $medication->name,
                'lot_number' => $medication->lot_number,
            ],
            'message' => 'Medication retrieved successfully',
        ], JsonResponse::HTTP_OK);
    }
}