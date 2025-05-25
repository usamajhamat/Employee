<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupplierPayment;
use App\Models\PaymentMethod;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->searchQuery) {
            $searchQuery = $request->searchQuery;
            $suppliers = Supplier::where(
                "name",
                "LIKE",
                "%" . $searchQuery . "%"
            )->orWhere(
                "email",
                "LIKE",
                "%" . $searchQuery . "%"
            )->orWhere(
                "phone",
                "LIKE",
                "%" . $searchQuery . "%"
            )->orderBy('created_at')
                ->paginate(Constants::$PAGE_LIMIT);
            return $suppliers;
        }

        $suppliers = Supplier::orderBy('created_at')
            ->paginate(Constants::$PAGE_LIMIT);
        return $suppliers;
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
            'email' => 'required',
            'phone' => 'required'
        ]);

        $supplier = Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'area' => $request->area
        ]);

        $paymentMethod = PaymentMethod::where('slug', 'due')->first();

        if ($supplier && $request->opening_balance_date && $request->opening_balance) {
            $payment = new SupplierPayment();
            $payment->date = $request->opening_balance_date;
            $payment->supplier_id = $supplier->id;
            $payment->total = $request->opening_balance;
            $payment->amount = 0;
            $payment->balance -= $request->opening_balance;
            $payment->payment_method_id = $paymentMethod->id;
            $payment->save();
        }

        return [
            'message' => 'Supplier created successfully.'
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
            'email' => 'required',
            'phone' => 'required'
        ]);

        $supplier = Supplier::where('id', $id)->first();
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->area = $request->area;
        $supplier->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $suppliers = Supplier::whereIn('id', $request->ids)->get();

        if (!$suppliers) {
            return [
                'message' => 'Supplier not found.'
            ];
        }

        foreach ($suppliers as $supplier) {
            $supplier->delete();
        }
    }
}
