<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankTransaction;
use Illuminate\Http\Request;

class BankTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BankTransaction::query()->with('bank')->orderBy('date', 'desc');

        if ($request->bank_id) {
            $query->where('bank_id', $request->bank_id);
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
            'transaction_type' => 'required',
            'bank' => 'required',
            'amount' => 'required',
        ]);

        $bank = Bank::where('id', $request->bank)->first();
        if (!$bank) {
            return response()->json([
                'message' => 'Bank not found.'
            ], 404);
        }

        $lastTransaction = BankTransaction::where('bank_id', $bank->id)->orderBy('created_at', 'desc')->first();
        $balance = $lastTransaction ? $lastTransaction->balance : 0;

        $newTransaction = new BankTransaction();
        $newTransaction->date = $request->date;
        $newTransaction->bank_id = $request->bank;
        if ($request->transaction_type == 'inbound') {
            $newTransaction->inbound_payment = $request->amount;
            $newTransaction->balance = $balance + $request->amount;
        } else {
            $newTransaction->outbound_payment = $request->amount;
            $newTransaction->balance = $balance - $request->amount;
        }
        $newTransaction->details = $request->details;
        $newTransaction->save();

        return response()->json([
            'message' => 'Transaction created successfully.'
        ]);
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
    public function destroy(Request $request)
    {
        $bankTransactions = BankTransaction::whereIn('id', $request->ids)->get();

        if (!$bankTransactions) {
            return [
                'message' => 'Bank transaction not found.'
            ];
        }

        foreach ($bankTransactions as $bankTransaction) {
            $bankTransaction->delete();
        }
    }
}
