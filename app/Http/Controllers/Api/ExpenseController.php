<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerPayment;
use App\Models\Expense;
use App\Models\SupplierPayment;

use Carbon\Carbon;  
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        


        $query = Expense::query()->with('expenseCategory', 'customer', 'supplier')->orderBy('date', 'desc');
        $totalExpeses = Expense::getTotalExpenseAmount();
        if ($request->start_date || $request->end_date) {
            if ($request->start_date) {
                $query->where('date', '<=', $request->start_date);
            }
            if ($request->end_date) {
                $query->where('date', '>=', $request->end_date);
            }
        }

        if ($request->searchQuery) {
            $query->whereHas('expenseCategory', function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->searchQuery . '%');
            });
        }

        // if ($request->has('page')) {
        //     return $query->paginate(Constants::$PAGE_LIMIT);
        // }

        return [
            'totalExpenses' => $totalExpeses,
            'expenses' => $query->paginate(Constants::$PAGE_LIMIT),
        ];

        // return $query->paginate(Constants::$PAGINATE_ALL);
    }

    // public function getTotalExpenseAmount()
    // {
    //     $totalAmount = Expense::sum('amount');
    //     return $totalAmount;
    // }

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
            'date' => 'required',
            'amount' => 'required'
        ]);

        $expense = new Expense();
        $expense->expense_type = $request->expense_type;
        if ($request->expense_type == 'customer') {
            $expense->customer_id = $request->customer;
        }
        if ($request->expense_type == 'supplier') {
            $expense->supplier_id = $request->supplier;
        }
        if ($request->expense_type == 'expense-category') {
            $expense->expense_category_id = $request->expense_category;
        }
        $expense->date = $request->date;
        $expense->amount = $request->amount;
        $expense->details = $request->details;
        $expense->save();

        if ($request->expense_type == 'customer') {
            $customerPayment = new CustomerPayment();
            $customerPayment->customer_id = $request->customer;
            $customerPayment->date = $request->date;
            $customerPayment->total = $request->amount;
            $customerPayment->amount = 0;
            $customerPayment->payment_method_id = '1';
            $customerPayment->details = $request->details;
            $customerPayment->save();
        }

        if ($request->expense_type == 'supplier') {
            $supplierPayment = new SupplierPayment();
            $supplierPayment->supplier_id = $request->supplier;
            $supplierPayment->date = $request->date;
            $supplierPayment->total = 0;
            $supplierPayment->amount = $request->amount;
            $supplierPayment->payment_method_id = '1';
            $supplierPayment->details = $request->details;
            $supplierPayment->save();
        }

        return [
            'message' => 'Audit created successfully.'
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required',
            'amount' => 'required'
        ]);

        $expense = Expense::where('id', $id)->first();
        $expense->expense_type = $request->expense_type;
        if ($request->expense_type == 'customer') {
            $expense->customer_id = $request->customer;
        }
        if ($request->expense_type == 'supplier') {
            $expense->supplier_id = $request->supplier;
        }
        if ($request->expense_type == 'expense-category') {
            $expense->expense_category_id = $request->expense_category;
        }
        $expense->date = $request->date;
        $expense->amount = $request->amount;
        $expense->details = $request->details;
        $expense->save();

        return [
            'message' => 'Audit updated successfully.'
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $expenses = Expense::whereIn('id', $request->ids)->get();

        if (!$expenses) {
            return [
                'message' => 'Expense not found.'
            ];
        }

        foreach ($expenses as $expense) {
            $expense->delete();
        }
    }
}
