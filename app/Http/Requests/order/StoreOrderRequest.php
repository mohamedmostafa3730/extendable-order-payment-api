<?php

namespace App\Http\Requests\order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_name' => [
                'required',
                'string',
                'max:255',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.price' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'items.required' => 'At least one order item is required.',
            'items.array' => 'Items must be provided as an array.',
            'items.min' => 'An order must contain at least one item.',

            'items.*.product_name.required' => 'Product name is required.',
            'items.*.product_name.max' => 'Product name may not exceed 255 characters.',

            'items.*.quantity.required' => 'Quantity is required.',
            'items.*.quantity.integer' => 'Quantity must be an integer.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',

            'items.*.price.required' => 'Price is required.',
            'items.*.price.numeric' => 'Price must be numeric.',
            'items.*.price.min' => 'Price must be greater than zero.',
        ];
    }
}
