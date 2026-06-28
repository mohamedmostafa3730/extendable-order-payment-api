<?php

namespace App\Services\Payment;

use App\DTOs\Payment\PaymentDataDTO;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function __construct(
        private readonly PaymentGatewayManager $gatewayManager,
    ) {
    }

    public function process(
        Order $order,
        PaymentMethod $paymentMethod,
        float $amount,
    ): Payment {
        $this->ensureOrderIsConfirmed($order);

        $this->ensureOrderHasNoSuccessfulPayment($order);

        $this->ensureAmountMatchesOrder($order, $amount);

        $paymentData = $this->buildPaymentData(
            $order,
            $paymentMethod,
            $amount,
        );

        $gateway = $this->gatewayManager->resolve($paymentMethod);

        $result = $gateway->pay($paymentData);

        return DB::transaction(function () use ($order, $paymentMethod, $amount, $result) {
            return Payment::create([
                'order_id' => $order->id,

                'payment_reference' => $result->reference,

                'payment_method' => $paymentMethod,

                'payment_status' => $result->status,

                'amount' => $amount,
            ]);
        });
    }

    /**
     * Ensure that the order is confirmed.
     */
    private function ensureOrderIsConfirmed(Order $order): void
    {
        if ($order->order_status !== OrderStatus::Confirmed) {
            throw ValidationException::withMessages([
                'order' => [
                    'Only confirmed orders can be paid.',
                ],
            ]);
        }
    }

    /**
     * Prevent duplicate successful payments.
     */
    private function ensureOrderHasNoSuccessfulPayment(
        Order $order,
    ): void {
        $alreadyPaid = $order
            ->payments()
            ->where(
                'payment_status',
                PaymentStatus::Paid
            )
            ->exists();

        if ($alreadyPaid) {
            throw ValidationException::withMessages([
                'order' => [
                    'This order has already been paid.',
                ],
            ]);
        }
    }

    /**
     * Ensure the requested amount equals the order total.
     */
    private function ensureAmountMatchesOrder(
        Order $order,
        float $amount,
    ): void {
        if (
            bccomp(
                (string) $amount,
                (string) $order->total,
                2
            ) !== 0
        ) {

            throw ValidationException::withMessages([
                'amount' => [
                    'Payment amount must equal the order total.',
                ],
            ]);
        }
    }

    /**
     * Build the payment DTO.
     */
    private function buildPaymentData(
        Order $order,
        PaymentMethod $paymentMethod,
        float $amount,
    ): PaymentDataDTO {
        return new PaymentDataDTO(
            order: $order,
            amount: $amount,
            paymentMethod: $paymentMethod,
        );
    }
}