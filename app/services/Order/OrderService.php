<?php

namespace App\Services\Order;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator {
        return Order::query()
            ->with([
                'items',
                'payments',
                'user',
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function find(Order $order): Order
    {
        return $order->load([
            'items',
            'payments',
            'user',
        ]);
    }

    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_status' => 'pending',
                'total' => 0,
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {

                $subtotal = $item['quantity'] * $item['price'];

                $order->items()->create([
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update([
                'total' => $total,
            ]);

            return $order->load([
                'items',
                'payments',
                'user',
            ]);
        });
    }

    public function update(
        Order $order,
        array $data
    ): Order {
        return DB::transaction(function () use ($order, $data) {

            if (isset($data['order_status'])) {
                $order->update([
                    'order_status' => $data['order_status'],
                ]);
            }

            if (isset($data['items'])) {

                $order->items()->delete();

                $total = 0;

                foreach ($data['items'] as $item) {

                    $subtotal = $item['quantity'] * $item['price'];

                    $order->items()->create([
                        'product_name' => $item['product_name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $subtotal,
                    ]);

                    $total += $subtotal;
                }

                $order->update([
                    'total' => $total,
                ]);
            }

            return $order->load([
                'items',
                'payments',
                'user',
            ]);
        });
    }

    /**
     * Delete an order.
     */
    public function delete(Order $order): void
    {
        $order->delete();
    }
}