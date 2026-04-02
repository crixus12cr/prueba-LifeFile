<?php

namespace App\Http\Services;

use App\Http\Repositories\OrderRepository;
use App\Http\Requests\Order\ListOrdersRequest;

class OrderService
{
    public OrderRepository $orderRepository;
    protected MedicationService $medicationService;

    public function __construct(OrderRepository $orderRepository, MedicationService $medicationService) {
        $this->orderRepository = $orderRepository;
        $this->medicationService = $medicationService;
    }
    
    public function getOrdersByLotNumber(ListOrdersRequest $request): array {
        $medication = $this->medicationService->searchByLotNumber($request);
        
        $orders = $this->orderRepository->getOrdersByMedicationAndDateRange(
            $medication['id'],
            $request->start_date,
            $request->end_date
        );
        
        if(empty($orders)) {
            throw new \Exception('No orders found for lot ' . $request->lot_number . ' in the specified date range');
        }
        
        return $orders;
    }
    
    public function getOrderDetail(int $orderId): array {
        $order = $this->orderRepository->findById($orderId);
        
        if(!$order) {
            throw new \Exception('Order not found');
        }
        
        return $order->toArray();
    }
}
