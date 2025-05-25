<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankTransaction;
use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\DirectEntry;
use App\Models\DirectEntryCustomer;
use App\Models\PaymentMethod;
use App\Models\SupplierPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DirectEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'supplier' => 'required',
            'product' => 'required',
            'date' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
            'selected_customers' => 'required'
        ]);

        $paymentMethod = PaymentMethod::where('slug', $request->payment_method)->first();

        $payment = new SupplierPayment();
        $payment->date = Carbon::now();
        $payment->supplier_id = $request->supplier;
        $payment->amount = $paymentMethod->slug == 'due' ? 0 : $request->amount;
        if ($paymentMethod->slug == 'due') {
            $payment->total = $request->amount;
            $payment->amount = 0;
        } else {
            $payment->total = $request->amount;
            $payment->amount = $request->amount;
        }
        $payment->payment_method_id = $paymentMethod->id;
        $payment->bank_id = $request->bank;
        $payment->details = $request->details;
        $payment->save();

        foreach ($request->selected_customers as $selectedCustomer) {
            $payment = new CustomerPayment();
            $payment->date = Carbon::now();
            $payment->customer_id = $selectedCustomer['customer_id'];
            if ($paymentMethod->slug == 'due') {
                $payment->total = $selectedCustomer['amount'];
                $payment->amount = 0;
            } else {
                $payment->total = $selectedCustomer['amount'];
                $payment->amount = $selectedCustomer['amount'];
            }
            $payment->payment_method_id = $paymentMethod->id;
            $payment->bank_id = $request->bank;
            $payment->details = $request->details;
            $payment->save();

            if ($paymentMethod->slug == 'bank-transfer') {
                // updating bank balance on new sale
                $lastTransaction = BankTransaction::orderBy('created_at', 'desc')->first();
                $balance = $lastTransaction ? $lastTransaction->balance : 0;

                // Create a new transaction for the sale
                $newTransaction = new BankTransaction();
                $newTransaction->date = Carbon::now();
                $newTransaction->bank_id = $request->bank;
                $newTransaction->inbound_payment = $selectedCustomer['amount'];
                $newTransaction->outbound_payment = null;
                $newTransaction->balance = $balance + $selectedCustomer['amount'];
                $newTransaction->save();
            }
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
