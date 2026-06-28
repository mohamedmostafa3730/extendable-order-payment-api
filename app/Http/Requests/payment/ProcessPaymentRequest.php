<?php

namespace App\Http\Requests\payment;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'uuid',
                'exists:orders,id',
            ],

            'payment_method' => [
                'required',
                Rule::in(PaymentMethod::values()),
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Order ID is required.',
            'order_id.uuid' => 'Order ID must be a valid UUID.',
            'order_id.exists' => 'The selected order does not exist.',
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'The selected payment method is invalid.',
            'amount.required' => 'Payment amount is required.',
            'amount.numeric' => 'Payment amount must be a valid number.',
            'amount.min' => 'Payment amount must be greater than zero.',
        ];
    }

    public function attributes(): array
    {
        return [
            'order_id' => 'order',
            'payment_method' => 'payment method',
            'amount' => 'payment amount',
        ];
    }
}