<!DOCTYPE html>
<html>
    <head>
        <title>Sales Receipt</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                margin: 0;
                padding: 0;
                font-size: 12px;
            }

            @media print {
                @page {
                    size: auto;
                    margin: 15mm 5mm 5mm 5mm;
                }

                .receipt-header {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    text-align: center;
                    background: transparent;
                    padding: 10px 0;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                thead {
                    display: table-header-group;
                }

                tr {
                    page-break-inside: avoid;
                }

                th, td {
                    padding: 4px;
                }

                .no-print {
                    display: none !important;
                }
            }

            @media screen {
                body {
                    padding: 20px;
                }

                .receipt-header {
                    text-align: center;
                    margin-top: 200px;
                    margin-bottom: 20px;
                }
            }
        </style>
    </head>
    <body>
        <div class="receipt-header">
            <h4 class="mb-2">POS Outlet</h4>
            <p class="mb-1">Invoice #: {{ $sale->invoice_number }}</p>
            <p class="mb-1">Date: {{ \Carbon\Carbon::parse($sale->created_at)->format('d M, Y h:i A') }}</p>
            <p class="mb-1">Customer: {{ $sale->customer->customer_name ?? '' }}</p>
            <p class="mb-1">Phone: {{ $sale->customer->phone ?? '' }}</p>
            <p class="mb-1">Cashier: {{ $sale->user->name ?? '' }}</p>
            <hr class="mb-0">
        </div>

        <div class="content-area">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                    <tr>
                        <td>{{ $item->product->product_name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">৳{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-end">৳{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end">৳{{ number_format($sale->items->sum('subtotal'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-4">
                <h6 class="mb-2">Payment Details</h6>
                <table class="table table-sm">
                    @foreach($sale->payments as $payment)
                    <tr>
                        <td>{{ $payment->paymentMethod->name }}</td>
                        <td class="text-end">৳{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <script>
            window.onload = function() {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 1000);
            };
        </script>
    </body>
</html>