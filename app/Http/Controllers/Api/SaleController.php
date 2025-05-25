<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\CartItem;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\CustomerPayment;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sale::with('customer', 'customer.payments', 'saleItems', 'saleItems.product', 'saleItems.product.unit', 'paymentMethod');

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->has('searchQuery')) {
            $searchQuery = $request->searchQuery;
            $query->where(function ($q) use ($searchQuery) {
                $q->whereHas('customer', function ($query) use ($searchQuery) {
                    $query->where('name', 'LIKE', '%' . $searchQuery . '%');
                });
            });
        } else if ($request->start_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $query->orderBy('created_at');

        return $query->paginate(Constants::$PAGE_LIMIT);
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
            'payment_method' => 'required',
        ]);

        $paymentMethod = PaymentMethod::where('slug', $request->payment_method)->first();
        $cartItems = CartItem::with('product')->where('cart_type', 'sale')->get();

        $sale = new Sale();
        if ($request->customer_id) {
            $sale->customer_id = $request->customer_id;
        }
        $sale->payment_method_id = $paymentMethod->id;

        $totalBill = 0;

        foreach ($cartItems as $item) {
            $totalBill += $item->total;
        }
        $sale->total = $totalBill;
        $sale->previous_balance = $request->previous_balance ?? 0;
        $sale->save();

        foreach ($cartItems as $item) {
            $product = Product::where('id', $item->product_id)->first();
            $product->stock_quantity -= $item->quantity;
            $product->save();

            $saleItem = new SaleItem();
            $saleItem->sale_id = $sale->id;
            $saleItem->product_id = $item->product_id;
            $saleItem->price = $item->price;
            $saleItem->quantity = $item->quantity;
            $saleItem->discount = $item->discount;
            $saleItem->discounted_price = $item->discounted_price;
            $saleItem->discount_type = $item->discount_type;
            $saleItem->total = $item->total;
            $saleItem->save();

            $item->delete();
        }

        $payment = new CustomerPayment();
        $payment->date = Carbon::now();
        $payment->customer_id = $request->customer_id;
        if ($paymentMethod->slug == 'due') {
            $payment->total = $sale->total;
            $payment->amount = 0;
        } else {
            $payment->total = $sale->total;
            $payment->amount = $sale->total;
        }
        $payment->payment_method_id = $paymentMethod->id;
        $payment->bank_id = $request->bank;
        $payment->save();

        if ($paymentMethod->slug == 'bank-transfer') {
            $lastTransaction = BankTransaction::orderBy('created_at', 'desc')->first();
            $balance = $lastTransaction ? $lastTransaction->balance : 0;

            $newTransaction = new BankTransaction();
            $newTransaction->date = Carbon::now();
            $newTransaction->bank_id = $request->bank;
            $newTransaction->inbound_payment = $sale->total;
            $newTransaction->outbound_payment = null;
            $newTransaction->balance = $balance + $sale->total;
            $newTransaction->save();
        }

        return [
            'message' => 'Sale created successfully.',
            'sale_id' => $sale->id
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
        // $request->validate([
        //     'date' => 'required',
        //     'customer_id' => 'required',
        //     'product_id' => 'required',
        //     'payment_method_id' => 'required',
        //     'quantity' => 'required'
        // ]);

        // $sale =  Sale::where('id', $id)->first();
        // $sale->date = $request->date;
        // $sale->customer_id = $request->customer_id;
        // $sale->product_id = $request->product_id;
        // $sale->payment_method_id = $request->payment_method_id;
        // $sale->bank_id = $request->bank_id;
        // $sale->quantity = $request->quantity;
        // $sale->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $sales = Sale::whereIn('id', $request->ids)->get();

        if (!$sales) {
            return [
                'message' => 'Sale not found.'
            ];
        }

        foreach ($sales as $sale) {
            $sale->delete();
        }
    }
}
