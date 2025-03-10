<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use DataTables;
use Mpdf\Mpdf;

class PosController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $products = Product::all();
        $paymentMethods = PaymentMethod::all();
        return view('pos.index', compact('customers', 'products', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'customer_id' => $request->sale['customer_id'],
                'invoice_number' => (string) Str::uuid(),
                'user_id' => auth()->id(),
                'created_at' => now(),
                'created_by' => auth()->user()->id,
            ]);

            foreach ($request->sale['items'] as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'created_at' => now(),
                ]);
            }

            foreach ($request->sale['payments'] as $payment) {
                Payment::create([
                    'sale_id' => $sale->id,
                    'payment_method_id' => $payment['type'],
                    'amount' => $payment['amount'],
                    'created_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'message' => 'Sale completed successfully'
            ]);

        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error completing sale'
            ], 500);
        }
    }

    public function receipt(Sale $sale)
    {
        $sale->load(['customer', 'items.product', 'payments.paymentMethod', 'user']);
        
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [80, 150],
            'margin_header' => 5,
            'margin_footer' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 45,
            'margin_bottom' => 25,
        ]);

        $header = view('pos.partials.receipt-header', compact('sale'))->render();
        $mpdf->SetHTMLHeader($header);

        $footer = view('pos.partials.receipt-footer')->render();
        $mpdf->SetHTMLFooter($footer);

        $content = view('pos.partials.receipt-content', compact('sale'))->render();
        $mpdf->WriteHTML($content);

        return $mpdf->Output('receipt.pdf', 'I');
    }

    
    public function salesList()
    {
        if (request()->ajax()) {
            $sales = Sale::with(['customer', 'user', 'items.product']);
            
            return DataTables::of($sales)
                ->addColumn('total_amount', function ($sale) {
                    return '৳' . number_format($sale->items->sum('subtotal'), 2);
                })
                ->addColumn('items_count', function ($sale) {
                    return $sale->items->count();
                })
                ->addColumn('actions', function ($sale) {
                    return '
                        <a href="' . route('pos.receipt', $sale->id) . '" 
                           class="btn btn-sm btn-info" 
                           target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        
        return view('pos.sales-list');
    }
}