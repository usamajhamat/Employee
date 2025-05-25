<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->searchQuery) {
            $searchQuery = $request->searchQuery;
            $banks = Bank::where(
                "name",
                "LIKE",
                "%" . $searchQuery . "%"
            )->with('transactions')->orderBy('created_at')
                ->paginate(Constants::$PAGE_LIMIT);
            return $banks;
        }

        $banks = Bank::with('transactions')->orderBy('created_at')
            ->paginate(Constants::$PAGE_LIMIT);
        return $banks;
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
            'name' => 'required',
            'address' => 'required',
            'account_number' => 'required'
        ]);

        $bank = Bank::create([
            'name' => $request->name,
            'address' => $request->address,
            'account_number' => $request->account_number
        ]);

        return [
            'message' => 'Bank created successfully.'
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
            'name' => 'required',
            'address' => 'required',
            'account_number' => 'required',
        ]);

        $bank = Bank::where('id', $id)->first();
        $bank->name = $request->name;
        $bank->address = $request->address;
        $bank->account_number = $request->account_number;
        $bank->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $banks = Bank::whereIn('id', $request->ids)->get();

        if (!$banks) {
            return [
                'message' => 'Banks not found.'
            ];
        }

        foreach ($banks as $bank) {
            $bank->delete();
        }
    }

    public function storeBankTransaction(Request $request)
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

    public function deleteBankTransactions(Request $request)
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
