<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case CreditCard = 'credit_card';
    case BankTransfer = 'bank_transfer';
    case Wallet = 'wallet';
    case PayPal = 'paypal';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
