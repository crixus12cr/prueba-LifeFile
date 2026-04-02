<?php

namespace App\Http\Services;

use Illuminate\Http\JsonResponse;
use App\Http\Repositories\AlertRepository;
use App\Http\Repositories\CustomerRepository;
use App\Http\Repositories\OrderRepository;
use Illuminate\Support\Carbon;
use App\Jobs\SendAlertEmailJob;

class AlertService {
    /**
     * AlertRepository instance.
     *
     * @var AlertRepository
     */
    public AlertRepository $alertRepository;
    
    /**
     * CustomerRepository instance.
     *
     * @var CustomerRepository
     */
    public CustomerRepository $customerRepository;
    
    /**
     * OrderRepository instance.
     *
     * @var OrderRepository
     */
    public OrderRepository $orderRepository;

    /**
     * Create a new AlertService instance.
     *
     * @param AlertRepository $alertRepository
     * @param CustomerRepository $customerRepository
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        AlertRepository $alertRepository, 
        CustomerRepository $customerRepository,
        OrderRepository $orderRepository
    ) {
        $this->alertRepository = $alertRepository;
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
    }
    
    /**
     * Send an alert to a customer.
     *
     * @param array $data
     * @param \App\Models\User $user
     * @return JsonResponse
     */
    public function sendAlert(array $data, $user): JsonResponse {
        $customer = $this->customerRepository->findById($data['customer_id']);
        $order = $this->orderRepository->findById($data['order_id']);
        
        if(!$customer || !$order) {
            return response()->json([
                'message' => 'Customer or order not found',
                'errors' => ['Invalid customer or order ID'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        $alert = $this->alertRepository->create([
            'customer_id' => $data['customer_id'],
            'order_id' => $data['order_id'],
            'user_id' => $user->id,
            'sent_at' => Carbon::now(),
        ]);
        
        SendAlertEmailJob::dispatch(
            $customer, 
            $order, 
            $data['medication_name'], 
            $data['lot_number']
        );
        
        return response()->json([
            'data' => [
                'alert_id' => $alert->id,
                'sent_at' => $alert->sent_at,
            ],
            'message' => 'Alert registered successfully. Notification email will be sent.',
        ], JsonResponse::HTTP_OK);
    }
}