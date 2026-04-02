<?php

namespace App\Http\Services;

use Illuminate\Http\JsonResponse;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\MedicationRepository;
use App\Http\Requests\Order\ListOrdersRequest;

class OrderService {
    /**
     * OrderRepository instance.
     *
     * @var OrderRepository
     */
    public OrderRepository $orderRepository;
    
    /**
     * MedicationRepository instance.
     *
     * @var MedicationRepository
     */
    protected MedicationRepository $medicationRepository;

    /**
     * Create a new OrderService instance.
     *
     * @param OrderRepository $orderRepository
     * @param MedicationRepository $medicationRepository
     */
    public function __construct(OrderRepository $orderRepository, MedicationRepository $medicationRepository) {
        $this->orderRepository = $orderRepository;
        $this->medicationRepository = $medicationRepository;
    }
    
    /**
     * Get orders by lot number and date range with pagination (for web).
     *
     * @param ListOrdersRequest $request
     * @return JsonResponse
     */
    public function getOrdersByLotNumber(ListOrdersRequest $request): JsonResponse {
        $medication = $this->medicationRepository->findByLotNumber($request->lot_number);
        
        if(!$medication) {
            return response()->json([
                'message' => 'Medication not found',
                'errors' => ['No medication found with lot number: ' . $request->lot_number],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        $perPage = $request->input('per_page', 15);
        $orders = $this->orderRepository->getOrdersByMedicationAndDateRangePaginated(
            $medication->id,
            $request->start_date,
            $request->end_date,
            $perPage
        );
        
        if($orders->isEmpty()) {
            return response()->json([
                'message' => 'Orders not found',
                'errors' => ['No orders found for lot ' . $request->lot_number . ' in the specified date range'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        return response()->json([
            'data' => $orders->items(),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'from' => $orders->firstItem(),
                'to' => $orders->lastItem(),
            ],
            'links' => [
                'first' => $orders->url(1),
                'last' => $orders->url($orders->lastPage()),
                'prev' => $orders->previousPageUrl(),
                'next' => $orders->nextPageUrl(),
            ],
            'message' => 'Orders retrieved successfully',
        ], JsonResponse::HTTP_OK);
    }
    
    /**
     * Get all orders by lot number and date range without pagination (for exports).
     *
     * @param ListOrdersRequest $request
     * @return JsonResponse
     */
    public function getAllOrdersByLotNumberForExport(ListOrdersRequest $request): JsonResponse {
        $medication = $this->medicationRepository->findByLotNumber($request->lot_number);
        
        if(!$medication) {
            return response()->json([
                'message' => 'Medication not found',
                'errors' => ['No medication found with lot number: ' . $request->lot_number],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        $orders = $this->orderRepository->getAllOrdersByMedicationAndDateRange(
            $medication->id,
            $request->start_date,
            $request->end_date
        );
        
        if(empty($orders)) {
            return response()->json([
                'message' => 'Orders not found',
                'errors' => ['No orders found for lot ' . $request->lot_number . ' in the specified date range'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        return response()->json([
            'data' => $orders,
            'message' => 'Orders retrieved successfully',
        ], JsonResponse::HTTP_OK);
    }
    
    /**
     * Get order details by ID.
     *
     * @param int $orderId
     * @return JsonResponse
     */
    public function getOrderDetail(int $orderId): JsonResponse {
        $order = $this->orderRepository->findById($orderId);
        
        if(!$order) {
            return response()->json([
                'message' => 'Order not found',
                'errors' => ['No order found with ID: ' . $orderId],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        return response()->json([
            'data' => $order->toArray(),
            'message' => 'Order retrieved successfully',
        ], JsonResponse::HTTP_OK);
    }
}