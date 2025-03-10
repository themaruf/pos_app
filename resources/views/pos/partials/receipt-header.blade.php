<div style="text-align: center; padding: 10px; border-bottom: 1px solid #ddd;">
    <h4 style="margin: 0;">{{ config('app.name') }}</h4>
    <p style="margin: 2px 0;">Invoice #: {{ $sale->invoice_number }}</p>
    <p style="margin: 2px 0;">Date: {{ \Carbon\Carbon::parse($sale->created_at)->format('d M, Y h:i A') }}</p>
    <p style="margin: 2px 0;">Customer: {{ $sale->customer->customer_name ?? '' }}</p>
    <p style="margin: 2px 0;">Phone: {{ $sale->customer->phone ?? '' }}</p>
</div>