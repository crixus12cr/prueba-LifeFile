<?php

namespace App\Http\Repositories;

use App\Models\Order;

class OrderRepository
{
    /**
     * Get orders filtered by medication and optional date range.
     *
     * This method retrieves all orders that contain a specific medication.
     * It can also filter the results by a purchase date range if provided.
     *
     * @param int $medicationId The ID of the medication to filter orders by.
     * @param string|null $startDate The start date (inclusive) for filtering orders (format: Y-m-d or datetime).
     * @param string|null $endDate The end date (inclusive) for filtering orders (format: Y-m-d or datetime).
     * @return array Returns an array of orders with their associated customer, ordered by purchase date in descending order.
     */
    public function getOrdersByMedicationAndDateRange(int $medicationId, ?string $startDate, ?string $endDate): array {
        $query = Order::whereHas('orderItems', function($q) use ($medicationId) {
            $q->where('medication_id', $medicationId);
        });
        
        if($startDate) {
            $query->where('purchase_date', '>=', $startDate);
        }
        
        if($endDate) {
            $query->where('purchase_date', '<=', $endDate);
        }
        
        return $query->with('customer')->orderBy('purchase_date', 'desc')->get()->toArray();
    }

    /**
     * Find an order by its ID, including its customer and order items with medications.
     *
     * @param int $id The unique identifier of the order.
     * @return Order|null Returns the Order instance with related customer and medications if found, or null otherwise.
     */
    public function findById(int $id): ?Order {
        return Order::with('customer', 'orderItems.medication')->find($id);
    }
}
