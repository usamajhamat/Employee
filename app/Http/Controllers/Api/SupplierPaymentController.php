<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankTransaction;
use App\Models\PaymentMethod;
use App\Models\SupplierPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupplierPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SupplierPayment::query()->with('supplier', 'paymentMethod', 'bank')->orderBy('date', 'desc');

        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->start_date || $request->end_date) {
            if ($request->start_date) {
                $query->where('date', '<=', $request->start_date);
            }
            if ($request->end_date) {
                $query->where('date', '>=', $request->end_date);
            }
        }

        return $query->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $paymentMethod = PaymentMethod::where('slug', $request->payment_method)->first();

        $payment = new SupplierPayment();
        $payment->date = $request->date;
        $payment->supplier_id = $request->supplier_id;
        $payment->bill_number = $request->bill_number;
        if ($request->payment_method == 'payment-advance') {
            $payment->total = 0;
            $payment->amount = $request->amount;
        } elseif ($request->payment_method == 'due') {
            $payment->total = $request->amount;
            $payment->amount = 0;
        } else {
            $payment->total = $request->amount;
            $payment->amount = 0;
        }
        $payment->payment_method_id = $paymentMethod->id;
        $payment->bank_id = $request->bank;
        $payment->save();

        if ($paymentMethod->slug == 'bank-transfer' || $paymentMethod->slug == 'payment-advance') {
            if ($request->bank) {
                $lastTransaction = BankTransaction::orderBy('created_at', 'desc')->first();
                $balance = $lastTransaction ? $lastTransaction->balance : 0;

                $newTransaction = new BankTransaction();
                $newTransaction->date = $request->date;
                $newTransaction->bank_id = $request->bank;
                $newTransaction->supplier_id = $request->supplier_id;
                $newTransaction->inbound_payment = null;
                $newTransaction->outbound_payment = $request->amount;
                $newTransaction->balance = $balance - $request->amount;
                $newTransaction->save();
            }
        }
    }

    public function destroy(Request $request)
    {
        $payments = SupplierPayment::whereIn('id', $request->ids)->get();

        if (!$payments) {
            return [
                'message' => 'Payment not found.'
            ];
        }

        foreach ($payments as $payment) {
            $payment->delete();
        }
    }
}
