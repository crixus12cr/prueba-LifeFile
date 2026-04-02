<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\OrderService;
use App\Http\Requests\Order\ListOrdersRequest;

class OrderController extends Controller {
    public OrderService $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    public function index(ListOrdersRequest $request): JsonResponse {
        try {
            $data = $this->orderService->getOrdersByLotNumber($request);
            
            return response()->json([
                'data' => $data,
            ], JsonResponse::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener órdenes',
                'errors' => [$e->getMessage()],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function show(int $id): JsonResponse {
        try {
            $data = $this->orderService->getOrderDetail($id);
            
            return response()->json([
                'data' => $data,
            ], JsonResponse::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener orden',
                'errors' => [$e->getMessage()],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}