<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\OrderService;
use App\Http\Requests\Order\ListOrdersRequest;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ExportController extends Controller {
    /**
     * OrderService instance.
     *
     * @var OrderService
     */
    public OrderService $orderService;

    /**
     * Create a new ExportController instance.
     *
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    /**
     * Export orders to Excel.
     *
     * @param ListOrdersRequest $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|JsonResponse
     */
    public function exportExcel(ListOrdersRequest $request) {
        try {
            $response = $this->orderService->getOrdersByLotNumber($request);
            
            if($response->getStatusCode() !== JsonResponse::HTTP_OK) {
                return $response;
            }
            
            $responseData = $response->getData(true);
            $orders = $responseData['data'] ?? [];
            
            if(empty($orders)) {
                return response()->json([
                    'message' => 'No orders found to export',
                    'errors' => ['There are no orders for the specified lot number'],
                ], JsonResponse::HTTP_NOT_FOUND);
            }
            
            $export = new OrdersExport($orders);
            
            return Excel::download($export, 'orders_lot_' . $request->lot_number . '.xlsx');
        } catch(\Exception $e) {
            Log::error('Excel export error: ' . $e->getMessage());
            Log::error('Excel export trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'message' => 'An error occurred while exporting to Excel',
                'errors' => [$e->getMessage()],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Export orders to PDF.
     *
     * @param ListOrdersRequest $request
     * @return \Illuminate\Http\Response|JsonResponse
     */
    public function exportPDF(ListOrdersRequest $request) {
        try {
            $response = $this->orderService->getOrdersByLotNumber($request);
            
            if($response->getStatusCode() !== JsonResponse::HTTP_OK) {
                return $response;
            }
            
            $responseData = $response->getData(true);
            $orders = $responseData['data'] ?? [];
            
            if(empty($orders)) {
                return response()->json([
                    'message' => 'No orders found to export',
                    'errors' => ['There are no orders for the specified lot number'],
                ], JsonResponse::HTTP_NOT_FOUND);
            }
            
            $lotNumber = $request->lot_number;
            
            $pdf = PDF::loadView('exports.orders-pdf', compact('orders', 'lotNumber'));
            $pdf->setPaper('a4', 'landscape');
            
            return $pdf->download('orders_lot_' . $lotNumber . '.pdf');
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while exporting to PDF',
                'errors' => [$e->getMessage()],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}