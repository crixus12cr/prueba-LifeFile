<?php

namespace App\Http\Services;

use Illuminate\Http\JsonResponse;
use App\Http\Repositories\CustomerRepository;

class CustomerService {
    /**
     * CustomerRepository instance.
     *
     * @var CustomerRepository
     */
    public CustomerRepository $customerRepository;

    /**
     * Create a new CustomerService instance.
     *
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository) {
        $this->customerRepository = $customerRepository;
    }
    
    /**
     * Get customer details by ID.
     *
     * @param int $customerId
     * @return JsonResponse
     */
    public function getCustomerDetail(int $customerId): JsonResponse {
        $customer = $this->customerRepository->findById($customerId);
        
        if(!$customer) {
            return response()->json([
                'message' => 'Customer not found',
                'errors' => ['No customer found with ID: ' . $customerId],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        return response()->json([
            'data' => $customer->toArray(),
            'message' => 'Customer retrieved successfully',
        ], JsonResponse::HTTP_OK);
    }
}