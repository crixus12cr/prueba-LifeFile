<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Orders Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h1 {
            color: #dc2626;
            text-align: center;
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #dc2626;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <h1>Pharmacovigilance Orders Report</h1>

    <div class="info">
        <p><strong>Lot Number:</strong> {{ $lotNumber }}</p>
        <p><strong>Generated on:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
        <p><strong>Total Orders:</strong> {{ count($orders) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Purchase Date</th>
                <th>Medications</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>#{{ $order['id'] }}</td>
                    <td>{{ $order['customer']['name'] ?? 'N/A' }}</td>
                    <td>{{ $order['customer']['email'] ?? 'N/A' }}</td>
                    <td>{{ $order['customer']['phone'] ?? 'N/A' }}</td>
                    <td>{{ $order['purchase_date'] ?? 'N/A' }}</td>
                    <td>
                        @php
                            $medicationsList = [];
                            if (isset($order['order_items']) && is_array($order['order_items'])) {
                                foreach ($order['order_items'] as $item) {
                                    if (isset($item['medication']['name'])) {
                                        $medicationsList[] =
                                            $item['medication']['name'] .
                                            ' (' .
                                            ($item['medication']['lot_number'] ?? 'N/A') .
                                            ')';
                                    }
                                }
                            }
                        @endphp
                        {{ !empty($medicationsList) ? implode(', ', $medicationsList) : 'N/A' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by the Pharmacovigilance Alert System.</p>
    </div>
</body>

</html>
