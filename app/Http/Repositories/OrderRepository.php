<?php

namespace App\Http\Repositories;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository {
    /**
     * Get orders by medication ID and date range with pagination.
     *
     * @param int $medicationId
     * @param string|null $startDate
     * @param string|null $endDate
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getOrdersByMedicationAndDateRangePaginated(int $medicationId, ?string $startDate, ?string $endDate, int $perPage = 10): LengthAwarePaginator {
        $query = Order::whereHas('orderItems', function($q) use ($medicationId) {
            $q->where('medication_id', $medicationId);
        })->with('customer', 'orderItems.medication');
        
        if($startDate) {
            $query->where('purchase_date', '>=', $startDate);
        }
        
        if($endDate) {
            $query->where('purchase_date', '<=', $endDate);
        }
        
        return $query->orderBy('purchase_date', 'desc')->paginate($perPage);
    }
    
    /**
     * Get all orders by medication ID and date range (without pagination for exports).
     *
     * @param int $medicationId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getAllOrdersByMedicationAndDateRange(int $medicationId, ?string $startDate, ?string $endDate): array {
        $query = Order::whereHas('orderItems', function($q) use ($medicationId) {
            $q->where('medication_id', $medicationId);
        })->with('customer', 'orderItems.medication');
        
        if($startDate) {
            $query->where('purchase_date', '>=', $startDate);
        }
        
        if($endDate) {
            $query->where('purchase_date', '<=', $endDate);
        }
        
        return $query->orderBy('purchase_date', 'desc')->get()->toArray();
    }
    
    /**
     * Find order by ID.
     *
     * @param int $id
     * @return Order|null
     */
    public function findById(int $id): ?Order {
        return Order::with('customer', 'orderItems.medication')->find($id);
    }
}