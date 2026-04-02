<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\OrderService;
use App\Http\Requests\Order\ListOrdersRequest;

class OrderController extends Controller {
    /**
     * OrderService instance.
     *
     * @var OrderService
     */
    public OrderService $orderService;

    /**
     * Create a new OrderController instance.
     *
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    /**
     * Get orders by lot number and date range.
     *
     * @param ListOrdersRequest $request
     * @return JsonResponse
     */
    public function index(ListOrdersRequest $request): JsonResponse {
        try {
            return $this->orderService->getOrdersByLotNumber($request);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while trying to get orders',
                'errors' => [$e->getMessage()],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Get order details by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse {
        try {
            return $this->orderService->getOrderDetail($id);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while trying to get order details',
                'errors' => [$e->getMessage()],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}