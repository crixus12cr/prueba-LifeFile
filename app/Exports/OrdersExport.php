<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class OrdersExport implements FromArray, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
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

        $purchaseDate = 'N/A';
        if(isset($order['purchase_date']) && !empty($order['purchase_date'])) {
            $purchaseDate = Carbon::parse($order['purchase_date'])->format('d/m/Y');
        }

        return [
            $order['id'] ?? 'N/A',
            $order['customer']['name'] ?? 'N/A',
            $order['customer']['email'] ?? 'N/A',
            $order['customer']['phone'] ?? 'N/A',
            $purchaseDate,
            $medications
        ];
    }

    /**
     * Apply styles to the Excel sheet.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        // Apply bold font to header row
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC2626'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Apply borders to all cells
        $sheet->getStyle('A1:F' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
        ]);

        // Auto-size rows and set height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Apply alignment for all cells
        $sheet->getStyle('A2:F' . ($sheet->getHighestRow()))->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Center align specific columns
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E:E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set background color for rows (zebra striping)
        $rowCount = $sheet->getHighestRow();
        for ($i = 2; $i <= $rowCount; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle("A{$i}:F{$i}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8FAFC'],
                    ],
                ]);
            }
        }

        return [];
    }

    /**
     * Set column widths.
     *
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 12,  // Order ID
            'B' => 25,  // Customer Name
            'C' => 30,  // Customer Email
            'D' => 18,  // Customer Phone
            'E' => 15,  // Purchase Date
            'F' => 60,  // Medications
        ];
    }

    /**
     * Register events for the Excel export.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Handle wrap text for all cells
                $event->sheet->getStyle('A1:F' . ($event->sheet->getHighestRow()))
                    ->getAlignment()
                    ->setWrapText(true);
                
                // auto-size rows based on content
                foreach(range(2, $event->sheet->getHighestRow()) as $row) {
                    $event->sheet->getRowDimension($row)->setRowHeight(-1);
                }
            },
        ];
    }
}