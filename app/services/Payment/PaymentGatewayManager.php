<?php


namespace App\Services\Payment;

use App\Contracts\Payment\PaymentGatewayInterface;
use App\Enums\PaymentMethod;
use App\Gateways\Payment\BankTransferGateway;
use App\Gateways\Payment\CashGateway;
use App\Gateways\Payment\CreditCardGateway;
use App\Gateways\Payment\PayPalGateway;
use App\Gateways\Payment\WalletGateway;
class PaymentGatewayManager
{
    public function __construct(
        private CreditCardGateway $creditCard,
        private PayPalGateway $paypal,
        private WalletGateway $wallet,
        private CashGateway $cash,
        private BankTransferGateway $bankTransfer,
    ) {
    }


    public function resolve(PaymentMethod $paymentMethod): PaymentGatewayInterface
    {
        return match ($paymentMethod) {

            PaymentMethod::CreditCard => $this->creditCard,

            PaymentMethod::PayPal => $this->paypal,

            PaymentMethod::Wallet => $this->wallet,

            PaymentMethod::Cash => $this->cash,

            PaymentMethod::BankTransfer => $this->bankTransfer,

        };
    }
}