<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\CartItem;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\SupplierPayment;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Purchase::with('supplier', 'purchaseItems', 'purchaseItems.product', 'purchaseItems.product.unit', 'paymentMethod');

        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->has('searchQuery')) {
            $searchQuery = $request->searchQuery;
            $query->where(function ($q) use ($searchQuery) {
                $q->whereHas('supplier', function ($query) use ($searchQuery) {
                    $query->where('name', 'LIKE', '%' . $searchQuery . '%');
                })
                    ->orWhereBetween('created_at', [request('purchase_date_filter.0'), request('purchase_date_filter.1')]);
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
        $cartItems = CartItem::with('product')->where('cart_type', 'purchase')->get();

        $purchase = new Purchase();
        if ($request->supplier_id) {
            $purchase->supplier_id = $request->supplier_id;
        }
        $purchase->payment_method_id = $paymentMethod->id;
        $totalBill = 0;

        foreach ($cartItems as $item) {
            $totalBill += $item->total;
        }
        $purchase->total = $totalBill;
        $purchase->previous_balance = $request->previous_balance ?? 0;
        $purchase->save();

        foreach ($cartItems as $item) {
            $product = Product::where('id', $item->product_id)->first();
            $product->stock_quantity += $item->quantity;
            $product->save();

            $purchaseItem = new PurchaseItem();
            $purchaseItem->purchase_id = $purchase->id;
            $purchaseItem->product_id = $item->product_id;
            $purchaseItem->price = $item->price;
            $purchaseItem->quantity = $item->quantity;
            // $purchaseItem->discount = $item->discount;
            // $purchaseItem->discounted_price = $item->discounted_price;
            // $purchaseItem->discount_type = $item->discount_type;
            $purchaseItem->total = $item->total;
            $purchaseItem->save();

            $item->delete();
        }

        $payment = new SupplierPayment();
        $payment->date = Carbon::now();
        $payment->supplier_id = $request->supplier_id;
        $payment->amount = $paymentMethod->slug == 'due' ? 0 : $purchase->total;
        if ($paymentMethod->slug == 'due') {
            $payment->total = $purchase->total;
            $payment->amount = 0;
        } else {
            $payment->total = $purchase->total;
            $payment->amount = $purchase->total;
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
            $newTransaction->inbound_payment = null;
            $newTransaction->outbound_payment = $purchase->total;
            $newTransaction->balance = $balance - $purchase->total;
            $newTransaction->save();
        }

        return [
            'message' => 'Purchase created successfully.',
            'purchase_id' => $purchase->id
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
        $purchases = Purchase::whereIn('id', $request->ids)->get();

        if (!$purchases) {
            return [
                'message' => 'Purchase not found.'
            ];
        }

        foreach ($purchases as $purchase) {
            $purchase->delete();
        }
    }
}
