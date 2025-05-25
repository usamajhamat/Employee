<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->cart_type == 'sale') {
            $cartItems = CartItem::where('cart_type', 'sale')->with('product')->get();
        } else {
            $cartItems = CartItem::where('cart_type', 'purchase')->with('product')->get();
        }
        return $cartItems;
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
            'product_id' => 'required',
        ]);

        $product = Product::where('id', $request->product_id)->first();

        // Check if the product already exists in the cart
        $cartItem = CartItem::where('cart_type', $request->cart_type)->where('product_id', $request->product_id)->first();

        if ($cartItem) {
            // If product exists, increment the quantity and update the total price
            $cartItem->quantity += 1;
            $cartItem->total = $cartItem->quantity * $cartItem->price;
            $cartItem->save();
        } else {
            // If product does not exist, create a new cart item
            $cartItem = CartItem::create([
                'cart_type' => $request->cart_type,
                'product_id' => $request->product_id,
                'price' => $request->cart_type == 'sale' ? $product->selling_price : $product->purchasing_price,
                'quantity' => 1,
                'total' => $request->cart_type == 'sale' ? $product->selling_price : $product->purchasing_price,
            ]);
        }

        return [
            'message' => 'Product saved to cart successfully.'
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
        $cartItem = CartItem::where('id', $id)->first();


        if ($request->quantity) {
            $cartItem->quantity = $request->quantity;
        }

        $discountChanged = false;
        $discountTypeChanged = false;

        if ($request->has('discount_type')) {
            $cartItem->discount_type = $request->discount_type;
            $discountTypeChanged = true;
        }

        if ($request->has('discount')) {
            $cartItem->discount = $request->discount;
            $discountChanged = true;

            if ($request->discount == 0) {
                $cartItem->discount_type = 0;
                $cartItem->discounted_price = 0;
            }
        }

        if ($discountChanged || $discountTypeChanged) {
            if ($cartItem->discount_type == 1) {
                $cartItem->discounted_price = ($cartItem->discount / 100) * ($cartItem->price * $cartItem->quantity);
            } else {
                $cartItem->discounted_price = $cartItem->discount;
            }
        }

        if ($request->has('price')) {
            $cartItem->price = $request->price;
        }

        if ($cartItem->discount_type == 1) {
            $cartItem->total = ($cartItem->price * $cartItem->quantity) - $cartItem->discounted_price;
        } else {
            $cartItem->total = ($cartItem->price * $cartItem->quantity) - $cartItem->discount;
        }

        $cartItem->save();

        return [
            'cart_type' => $cartItem->cart_type,
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->cart_type == 'sale') {
            $cartItems = CartItem::where('cart_type', 'sale')->whereIn('id', $request->ids)->get();
        } else {
            $cartItems = CartItem::where('cart_type', 'purchase')->whereIn('id', $request->ids)->get();
        }

        if (!$cartItems) {
            return [
                'message' => 'Cart items not found.'
            ];
        }

        foreach ($cartItems as $cartItem) {
            $cartItem->delete();
        }
    }
}
