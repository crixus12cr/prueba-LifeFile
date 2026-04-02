<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\CustomerService;

class CustomerController extends Controller {
    /**
     * CustomerService instance.
     *
     * @var CustomerService
     */
    public CustomerService $customerService;

    /**
     * Create a new CustomerController instance.
     *
     * @param CustomerService $customerService
     */
    public function __construct(CustomerService $customerService) {
        $this->customerService = $customerService;
    }

    /**
     * Get customer details by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse {
        try {
            return $this->customerService->getCustomerDetail($id);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while trying to get customer details',
                'errors' => [$e->getMessage()],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}