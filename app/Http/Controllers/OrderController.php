<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $response = Order::with(['products'])->where('user_id', auth()->id());
        if (isset($request->status)) {
            $response->where('status', $request->status);
        }
        return $response->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => ['required', 'min:10'],
            'phone' => ['required', 'string', 'regex:/^(\+84|84|0)([1-9][0-9]{8})\b/'],
            'total' => ['required', 'numeric'],
            'coupon' => ['nullable'],
            'products' => ['required', 'array'],
            'products.*' => ['required', 'array'],
            'products.*.product_id' => ['required', 'integer'],
            'products.*.quantity' => ['required', 'integer'],
        ]);

        $order = Order::query()->create([
            ...$request->except(['products']),
            'user_id' => auth()->id()
        ]);

        $order->products()->attach(
            collect($request->products)
                ->keyBy('product_id')
                ->select('quantity')
        );

        return $order->fresh();
    }


    public function destroy(Order $order)
    {
        $order->products()->detach();
        return $order->delete();
    }
}
