<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromArray, WithHeadings, WithMapping
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function array(): array
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Purchase Date',
            'Medications'
        ];
    }

    public function map($order): array
    {
        $medicationsList = [];
        
        if(isset($order['order_items']) && is_array($order['order_items'])) {
            foreach($order['order_items'] as $item) {
                if(isset($item['medication']['name'])) {
                    $medicationsList[] = $item['medication']['name'] . ' (' . ($item['medication']['lot_number'] ?? 'N/A') . ')';
                }
            }
        }
        
        $medications = !empty($medicationsList) ? implode(', ', $medicationsList) : 'N/A';

        return [
            $order['id'] ?? 'N/A',
            $order['customer']['name'] ?? 'N/A',
            $order['customer']['email'] ?? 'N/A',
            $order['customer']['phone'] ?? 'N/A',
            $order['purchase_date'] ?? 'N/A',
            $medications
        ];
    }
}