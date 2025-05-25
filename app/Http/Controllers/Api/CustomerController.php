<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->searchQuery) {
            $searchQuery = $request->searchQuery;
            $customers = Customer::where(
                "name",
                "LIKE",
                "%" . $searchQuery . "%"
            )->orWhere(
                "area",
                "LIKE",
                "%" . $searchQuery . "%"
            )->orWhere(
                "address",
                "LIKE",
                "%" . $searchQuery . "%"
            )->orWhere(
                "phone",
                "LIKE",
                "%" . $searchQuery . "%"
            )->with('payments', 'expenses')->orderBy('created_at')
                ->paginate(Constants::$PAGE_LIMIT);
            return $customers;
        }

        $customers = Customer::with('payments', 'expenses')->orderBy('created_at')
            ->paginate(Constants::$PAGE_LIMIT);
        return $customers;
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
            'phone' => 'required',
            'installment_plan' => 'required'
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'area' => $request->area,
            'installment_plan' => $request->installment_plan
        ]);

        $paymentMethod = PaymentMethod::where('slug', 'due')->first();

        if ($customer && $request->opening_balance_date && $request->opening_balance) {
            $payment = new CustomerPayment();
            $payment->date = $request->opening_balance_date;
            $payment->customer_id = $customer->id;
            $payment->total = $request->opening_balance;
            $payment->amount = 0;
            $payment->balance -= $request->opening_balance;
            $payment->payment_method_id = $paymentMethod->id;
            $payment->save();
        }

        return [
            'message' => 'Customer created successfully.'
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
            'phone' => 'required',
            'installment_plan' => 'required'
        ]);

        $customer = Customer::where('id', $id)->first();
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->phone = $request->phone;
        $customer->area = $request->area;
        $customer->installment_plan = $request->installment_plan;
        $customer->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $customers = Customer::whereIn('id', $request->ids)->get();

        if (!$customers) {
            return [
                'message' => 'Customer not found.'
            ];
        }

        foreach ($customers as $customer) {
            $customer->delete();
        }
    }
}
