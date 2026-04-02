<!DOCTYPE html>
<html>
<head>
    <title>Pharmacovigilance Alert</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            padding: 40px 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            padding: 30px 40px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 8px;
            font-weight: 700;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px;
        }
        
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        
        .greeting strong {
            color: #dc2626;
        }
        
        .message {
            background-color: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 16px 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        
        .message p {
            color: #991b1b;
            font-weight: 500;
        }
        
        .alert-details {
            background-color: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            border: 1px solid #e2e8f0;
        }
        
        .alert-details h3 {
            color: #1e293b;
            font-size: 18px;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #dc2626;
            display: inline-block;
        }
        
        .details-list {
            list-style: none;
            margin-top: 16px;
        }
        
        .details-list li {
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: flex-start;
        }
        
        .details-list li:last-child {
            border-bottom: none;
        }
        
        .details-label {
            font-weight: 700;
            color: #475569;
            width: 130px;
            flex-shrink: 0;
        }
        
        .details-value {
            color: #1e293b;
            font-weight: 500;
        }
        
        .lot-number {
            background-color: #fee2e2;
            padding: 4px 10px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #991b1b;
        }
        
        .action-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            border: 1px solid #fbbf24;
        }
        
        .action-box h3 {
            color: #92400e;
            font-size: 18px;
            margin-bottom: 12px;
        }
        
        .action-box p {
            color: #78350f;
            line-height: 1.6;
        }
        
        .contact {
            background-color: #f0fdf4;
            border-radius: 10px;
            padding: 16px 20px;
            margin: 25px 0;
            border: 1px solid #86efac;
        }
        
        .contact p {
            color: #166534;
            margin-bottom: 8px;
        }
        
        .contact .phone {
            font-weight: 700;
            font-size: 18px;
        }
        
        .footer {
            background-color: #f8fafc;
            padding: 20px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer hr {
            display: none;
        }
        
        .footer small {
            color: #94a3b8;
            font-size: 12px;
        }
        
        @media (max-width: 600px) {
            .content {
                padding: 24px;
            }
            
            .details-list li {
                flex-direction: column;
            }
            
            .details-label {
                width: 100%;
                margin-bottom: 4px;
            }
            
            .header h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pharmacovigilance Alert</h1>
            <p>Important Medication Safety Notification</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                <strong>Dear {{ $customerName }},</strong>
            </div>
            
            <div class="message">
                <p>This is an important notification regarding a medication you recently purchased. Please review the information below carefully.</p>
            </div>
            
            <div class="alert-details">
                <h3>Alert Details</h3>
                <ul class="details-list">
                    <li>
                        <span class="details-label">Order ID:</span>
                        <span class="details-value">#{{ $orderId }}</span>
                    </li>
                    <li>
                        <span class="details-label">Purchase Date:</span>
                        <span class="details-value">{{ $purchaseDate }}</span>
                    </li>
                    <li>
                        <span class="details-label">Medication:</span>
                        <span class="details-value">{{ $medicationName }}</span>
                    </li>
                    <li>
                        <span class="details-label">Lot Number:</span>
                        <span class="details-value"><span class="lot-number">{{ $lotNumber }}</span></span>
                    </li>
                </ul>
            </div>
            
            <div class="action-box">
                <h3>Recommended Action</h3>
                <p><strong>{{ $recommendedAction }}</strong></p>
                <p style="margin-top: 12px;">Please discontinue use of this medication immediately and consult your healthcare provider.</p>
            </div>
            
            <div class="contact">
                <p><strong>Need assistance?</strong></p>
                <p>Contact our Pharmacovigilance Department:</p>
                <p class="phone">pharmacovigilance@pharmacy.com</p>
                <p class="phone">+57 (1) 800-123-4567</p>
            </div>
        </div>
        
        <div class="footer">
            <small>This is an automated message from the Pharmacovigilance Alert System. Please do not reply to this email.</small>
            <br>
            <small>&copy; {{ date('Y') }} Pharmacovigilance Department. All rights reserved.</small>
        </div>
    </div>
</body>
</html>