<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->searchQuery) {
            $searchQuery = $request->searchQuery;
            $products = Product::where(
                "title",
                "LIKE",
                "%" . $searchQuery . "%"
            )->with('category', 'supplier', 'unit')->orderBy('created_at')
                ->paginate(Constants::$PAGE_LIMIT);
            return $products;
        }

        $products = Product::with('category', 'supplier', 'unit')->orderBy('created_at')
            ->paginate(Constants::$PAGE_LIMIT);
        return $products;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'title' => 'required',
            'unit_id' => 'required',
            'purchasing_price' => 'required',
            'selling_price' => 'required'
        ]);

        $product = new Product();
        $product->category_id = $request->category_id;
        if ($request->supplier_id) {
            $product->supplier_id = $request->supplier_id;
        }
        $product->title = $request->title;
        $product->unit_id = $request->unit_id;
        $product->purchasing_price = $request->purchasing_price;
        $product->selling_price = $request->selling_price;
        $product->stock_quantity = $request->stock_quantity ?? 0;
        $product->details = $request->details;
        $product->save();

        return [
            'message' => 'Product created successfully.'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'category_id' => 'required',
            'title' => 'required',
            'unit_id' => 'required',
            'purchasing_price' => 'required',
            'selling_price' => 'required'
        ]);

        $product =  Product::where('id', $id)->first();
        $product->category_id = $request->category_id;
        $product->category_id = $request->category_id;
        if ($request->supplier_id) {
            $product->supplier_id = $request->supplier_id;
        }
        $product->title = $request->title;
        $product->unit_id = $request->unit_id;
        $product->purchasing_price = $request->purchasing_price;
        $product->selling_price = $request->selling_price;
        $product->stock_quantity = $request->stock_quantity ?? 0;
        $product->details = $request->details;
        $product->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $products = Product::whereIn('id', $request->ids)->get();

        if (!$products) {
            return [
                'message' => 'Product not found.'
            ];
        }

        foreach ($products as $product) {
            $product->delete();
        }
    }
}
