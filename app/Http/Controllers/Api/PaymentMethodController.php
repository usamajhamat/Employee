<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->searchQuery) {
            $searchQuery = $request->searchQuery;
            $paymentMethods = PaymentMethod::where(
                "title",
                "LIKE",
                "%" . $searchQuery . "%"
            )->orderBy('created_at')
                ->paginate(Constants::$PAGE_LIMIT);
            return $paymentMethods;
        }

        $paymentMethods = PaymentMethod::orderBy('created_at')
            ->paginate(Constants::$PAGE_LIMIT);
        return $paymentMethods;
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
            'title' => 'required',
        ]);

        $paymentMethod = PaymentMethod::create([
            'title' => $request->title,
        ]);

        return [
            'message' => 'Payment methods created successfully.'
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
            'title' => 'required',
        ]);

        $paymentMethod = PaymentMethod::where('id', $id)->first();
        $paymentMethod->title = $request->title;
        $paymentMethod->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $paymentMethods = PaymentMethod::whereIn('id', $request->ids)->get();

        if (!$paymentMethods) {
            return [
                'message' => 'Payment methods not found.'
            ];
        }

        foreach ($paymentMethods as $paymentMethod) {
            $paymentMethod->delete();
        }
    }
}
