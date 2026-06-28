<?php

namespace App\Http\Requests\order;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_status' => [
                'sometimes',
                Rule::in(OrderStatus::values()),
            ],

            'items' => [
                'sometimes',
                'array',
                'min:1',
            ],

            'items.*.product_name' => [
                'required_with:items',
                'string',
                'max:255',
            ],

            'items.*.quantity' => [
                'required_with:items',
                'integer',
                'min:1',
            ],

            'items.*.price' => [
                'required_with:items',
                'numeric',
                'min:0.01',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'order_status.in' => 'The selected order status is invalid.',

            'items.array' => 'Items must be an array.',
            'items.min' => 'The order must contain at least one item.',

            'items.*.product_name.required_with' => 'Product name is required.',
            'items.*.quantity.required_with' => 'Quantity is required.',
            'items.*.quantity.integer' => 'Quantity must be an integer.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
            'items.*.price.required_with' => 'Price is required.',
            'items.*.price.numeric' => 'Price must be numeric.',
            'items.*.price.min' => 'Price must be greater than zero.',
        ];
    }
}
