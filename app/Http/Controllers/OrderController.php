<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'products']);
        if ($request->has('status')) {
            $validStatuses = ['pending', 'completed', 'cancelled'];
            if (in_array($request->status, $validStatuses)) {
                $query->where('status', $request->status);
            }
        }
        $orders = $query->paginate(10);
        return response()->json($orders);
    }

    public function create(CreateOrderRequest $request)
    {
        $order = new Order();
        $order->customer_id = $request->customer_id;
        $order->total_amount = 0;
        $order->save();

        $totalAmount = 0;
        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            $quantity = $productData['quantity'];
            $unitPrice = $product->price;
            $totalPrice = $unitPrice * $quantity;

            $order->products()->attach($product->id, [
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ]);

            $totalAmount += $totalPrice;
        }
        $order->total_amount = $totalAmount;
        $order->save();

        $order->load('customer', 'products');

        return response()->json(['message' => 'Sifariş uğurla yaradıldı', 'order' => $order], 201);
    }

    public function update(UpdateOrderRequest $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Sifariş tapılmadı'], 404);
        }
        if ($order->is_paid) {
            return response()->json(['error' => 'Ödənilmiş sifarişlər yenilənə bilməz'], 403);
        }
        if ($request->has('status')) {
            $validStatuses = ['pending', 'completed', 'cancelled'];
            if (in_array($request->status, $validStatuses)) {
                $order->status = $request->status;
            } else {
                return response()->json(['error' => 'Yanlış status'], 400);
            }
        }
        if ($request->has('products') && is_array($request->products) && count($request->products) > 0) {
            $order->products()->detach();
            $totalAmount = 0;

            foreach ($request->products as $productData) {
                $product = Product::find($productData['id']);
                if (!$product) {
                    return response()->json(['error' => 'Məhsul tapılmadı'], 404);
                }

                $quantity = $productData['quantity'];
                $unitPrice = $product->price;
                $totalPrice = $unitPrice * $quantity;

                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);

                $totalAmount += $totalPrice;
            }

            if ($request->has('discount')) {
                $totalAmount -= $request->discount;
            }

            if ($request->has('tax')) {
                $totalAmount += $request->tax;
            }

            $order->total_amount = max(0, $totalAmount);
        }

        $order->save();

        return response()->json(['message' => 'Sifariş uğurla yeniləndi', 'order' => $order]);
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'products'])->find($id);
        if (!$order) {
            return response()->json(['error' => 'Sifariş tapılmadı'], 404);
        }
        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Sifariş tapılmadı'], 404);
        }
        if ($order->is_paid) {
            return response()->json(['error' => 'Ödənilmiş sifarişlər silinə bilməz'], 403);
        }
        $order->delete();
        return response()->json(['message' => 'Sifariş uğurla silindi']);
    }
}
