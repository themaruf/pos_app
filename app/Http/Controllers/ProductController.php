<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function getData()
    {
        $products = Product::query();

        return DataTables::of($products)
            ->addColumn('action', function ($product) {
                return '
                    <a href="' . route('products.edit', $product->id) . '" class="btn btn-sm btn-primary">Edit</a>
                    <form action="' . route('products.destroy', $product->id) . '" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                    </form>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|unique:products',
            'size' => 'required|in:XS,S,M,L,XL',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'alert_quantity' => 'required|integer|min:0'
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|unique:products,product_code,' . $product->id,
            'size' => 'required|in:XS,S,M,L,XL',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'alert_quantity' => 'required|integer|min:0'
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function checkCode(Request $request)
    {
        $code = $request->get('code');
        $id = $request->get('id');
        
        $query = Product::where('product_code', $code);
        
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        $exists = $query->exists();
        
        return response()->json(!$exists);
    }
}