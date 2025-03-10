<div style="padding: 10px;">
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
        <thead>
            <tr>
                <th style="text-align: left;">Item</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Price</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->product->product_name }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">{{ number_format($item->unit_price, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                <td style="text-align: right;">{{ number_format($sale->items->sum('subtotal'), 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 10px;">
        <h4 style="margin: 10px 0 5px 0;">Payment Details</h4>
        <table style="width: 100%; border-collapse: collapse;">
            @foreach($sale->payments as $payment)
            <tr>
                <td>{{ $payment->paymentMethod->name }}</td>
                <td style="text-align: right;">{{ number_format($payment->amount, 2) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>