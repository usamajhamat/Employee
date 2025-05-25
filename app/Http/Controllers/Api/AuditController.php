<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        if ($request->start_date) {
            $audits = Audit::whereBetween('date', [$request->start_date, $request->end_date])->orderBy('date', 'desc')->get();

            // total sales amount
            $totalSales = 0;
            foreach ($audits as $audit) {
                if ($audit->audit_type == 'sale') {
                    $totalSales += $audit->amount;
                }
            }

            // total expense amount
            $totalExpense = 0;
            foreach ($audits as $audit) {
                if ($audit->audit_type == 'expense') {
                    $totalSales += $audit->amount;
                }
            }

            return response()->json([
                'total_sales' => $totalSales,
                'total_expense' => $totalExpense,
                'audits' => $audits,
            ]);
        }

        $audits = Audit::orderBy('date', 'desc')->get();

        // total sales amount
        $totalSales = 0;
        foreach ($audits as $audit) {
            if ($audit->audit_type == 'sale') {
                $totalSales += $audit->amount;
            }
        }

        // total expense amount
        $totalExpense = 0;
        foreach ($audits as $audit) {
            if ($audit->audit_type == 'expense') {
                $totalExpense += $audit->amount;
            }
        }
        return response()->json([
            'total_sales' => $totalSales,
            'total_expense' => $totalExpense,
            'audits' => $audits,
        ]);
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
            'audit_type' => 'required',
            'date' => 'required',
            'amount' => 'required'
        ]);

        $lastAudit = Audit::orderBy('created_at', 'desc')->first();
        $grossProfit = $lastAudit ? $lastAudit->gross_profit : 0;

        $audit = new Audit();
        $audit->audit_type = $request->audit_type;
        $audit->date = $request->date;
        $audit->amount = $request->amount;
        $audit->details = $request->details;
        $audit->save();

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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $audits = Audit::whereIn('id', $request->ids)->get();

        if (!$audits) {
            return [
                'message' => 'Audits not found.'
            ];
        }

        foreach ($audits as $audit) {
            $audit->delete();
        }
    }
}
