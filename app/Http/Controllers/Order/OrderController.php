<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Services\Order\OrderService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        return OrderResource::collection(
            $this->orderService->paginate()
        );
    }

    public function store(
        StoreOrderRequest $request
    ): OrderResource {
        $order = $this->orderService->create(
            $request->validated()
        );

        return new OrderResource($order);
    }

    public function show(Order $order): OrderResource
    {
        return new OrderResource(
            $this->orderService->find($order)
        );
    }

    public function update(
        UpdateOrderRequest $request,
        Order $order
    ): OrderResource {
        $order = $this->orderService->update(
            $order,
            $request->validated()
        );

        return new OrderResource($order);
    }

    public function destroy(Order $order): Response
    {
        $this->orderService->delete($order);

        return response()->noContent();
    }
}