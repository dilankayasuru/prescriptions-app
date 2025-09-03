<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation Status Update</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 28px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            margin: 10px 0;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .content {
            color: #555;
            margin-bottom: 30px;
        }

        .quotation-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }

        .quotation-id {
            font-weight: bold;
            color: #495057;
            margin-bottom: 8px;
        }

        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }

        .customer-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }

        .customer-info h3 {
            margin: 0 0 10px 0;
            color: #1976d2;
        }

        .prescription-info {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }

        .prescription-info h3 {
            margin: 0 0 10px 0;
            color: #856404;
        }

        .medicine-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .medicine-table th,
        .medicine-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .medicine-table th {
            background-color: #e9ecef;
            font-weight: 600;
            color: #495057;
        }

        .medicine-table tr:hover {
            background-color: #f8f9fa;
        }

        .price-cell {
            text-align: right;
            font-weight: 500;
        }

        .cta-button {
            display: inline-block;
            color: blue;
            text-decoration: none;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #e9ecef;
            margin-top: 30px;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Quotation Status Update</h1>
            <div class="status-badge status-{{ $quotation->status }}">
                {{ ucfirst($quotation->status) }}
            </div>
        </div>

        <div class="greeting">
            Hello {{ $quotation->status === 'completed' ? $quotation->prescription->user->name : 'Admin' }},
        </div>

        <div class="content">
            <p>A quotation status has been updated by the
                {{ $quotation->status === 'completed' ? 'Admin' : $quotation->prescription->user->name }}. Please find
                the details below:</p>
        </div>

        <div class="customer-info">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $quotation->prescription->user->name }}</p>
            <p><strong>Email:</strong> {{ $quotation->prescription->user->email }}</p>
            <p><strong>Status Update:</strong>
                <span class="status-badge status-{{ $quotation->status }}">
                    {{ ucfirst($quotation->status) }}
                </span>
            </p>
            <p><strong>Updated on:</strong> {{ $quotation->updated_at->format('F j, Y \a\t g:i A') }}</p>
        </div>

        <div class="prescription-info">
            <h3>Prescription Details</h3>
            <p><strong>Prescription ID:</strong> #{{ $quotation->prescription->id }}</p>
            <p><strong>Submitted on:</strong> {{ $quotation->prescription->created_at->format('F j, Y \a\t g:i A') }}
            </p>
        </div>

        <div class="quotation-details">
            <p class="quotation-id">Quotation #{{ $quotation->id }}</p>
            <div class="total-amount">
                ${{ number_format($quotation->total_price, 2) }}
            </div>

            @if ($quotation->medicineQuotations->count() > 0)
                <h3 style="margin: 0 0 15px 0; color: #495057;">Medicine Breakdown</h3>
                <table class="medicine-table">
                    <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Dosage</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotation->medicineQuotations as $medicine)
                            <tr>
                                <td><strong>{{ $medicine->name }}</strong></td>
                                <td>{{ $medicine->dosage }}</td>
                                <td>{{ $medicine->quantity }}</td>
                                <td class="price-cell">${{ number_format($medicine->unit_price, 2) }}</td>
                                <td class="price-cell">
                                    <strong>${{ number_format($medicine->unit_price * $medicine->quantity, 2) }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div style="text-align: center;">
            <a href="{{ route('quotations.show', $quotation->id) }}" class="cta-button">View Quotation</a>
        </div>

        <div class="footer">
            <p>This notification was sent automatically when a quotation status was updated.</p>
            <p style="margin-top: 15px; font-size: 12px;">
                Prescription Management System - Admin
            </p>
        </div>
    </div>
</body>

</html>
