<?php


namespace App\Services\Payment;

use App\Contracts\Payment\PaymentGatewayInterface;
use App\Enums\PaymentMethod;
use App\Gateways\Payment\BankTransferGateway;
use App\Gateways\Payment\CashGateway;
use App\Gateways\Payment\CreditCardGateway;
use App\Gateways\Payment\PayPalGateway;
use App\Gateways\Payment\WalletGateway;
use InvalidArgumentException;

class PaymentGatewayManager
{
    /**
     * Resolve the appropriate payment gateway.
     */
    public function resolve(PaymentMethod $paymentMethod): PaymentGatewayInterface
    {
        return match ($paymentMethod) {

            PaymentMethod::CreditCard => new CreditCardGateway(),

            PaymentMethod::PayPal => new PayPalGateway(),

            PaymentMethod::Wallet => new WalletGateway(),

            PaymentMethod::Cash => new CashGateway(),

            PaymentMethod::BankTransfer => new BankTransferGateway(),

            default => throw new InvalidArgumentException(
                "Unsupported payment method [{$paymentMethod->value}]."
            ),
        };
    }
}