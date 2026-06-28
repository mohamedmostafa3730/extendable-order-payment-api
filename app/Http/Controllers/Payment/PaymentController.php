<?php

namespace App\Http\Controllers\Payment;

use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\payment\ProcessPaymentRequest;
use App\Http\Resources\Payment\PaymentResource;
use App\Models\Order;
use App\Services\Payment\PaymentService;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {
    }

    public function process(
        ProcessPaymentRequest $request,
        Order $order,
    ): PaymentResource {
        $payment = $this->paymentService->process(
            order: $order,
            paymentMethod: PaymentMethod::from(
                $request->validated('payment_method')
            ),
            amount: (float) $request->validated('amount'),
        );

        return new PaymentResource($payment);
    }
}