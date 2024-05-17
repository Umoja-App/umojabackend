<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'data' => $this->makeHidden([
            //     'change_amount', 'distance', 'duration', 'grand_total',
            //     'service_charge', 'delivery_fee', 'delivered_time',
            //     'recieved_time', 'reject_reason', 'rejected_time',
            //     'served_time', 'transit_time', 'total'
            // ]),
            'id' => $this->id,
            'order_id' => $this->order_number,
            'created_at' => $this->created_at,
            'customer_fullname'=> $this->shippingAddress?->shipping_full_name,
            'total' => $this->total_amount,
            'discount' => $this->discount_code?->discount_amount,
            'delivery_price' => $this->delivery_charge,
            'sub_total' => $this->sub_total,
            'payment_status' => [
                'is_paid' => $this->payment_status->isPaid(),
                'is_pending' => $this->payment_status->isPending(),
            ],
            'items' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'qty' => $product->pivot->qty, // Accessing qty from pivot table
                    'photo' => $product->photo, // Accessing qty from pivot table
                    'cost_per_item' => $product->cost_per_item,
                    'colors' => $product->colors, // Accessing qty from pivot table
                    'price' => $product->pivot->price, // Accessing qty from pivot table
                    // Add other fields you want to include
                ];
            }),
            'fulfillment_status' => [
                'is_fulfilled' => $this->fulfillment_status->isFulfilled(),
                'is_unfulfilled' => $this->fulfillment_status->isUnfulfilled(),
                'is_cancelled' => $this->fulfillment_status->isCancelled(),
            ],
            'delivery_method' => $this->shippingMethod?->type,
            'delivery_duration' => $this->shippingMethod?->duration,
            'customer_email' => $this->shippingAddress?->shipping_email,
            'customer_phone' => $this->shippingAddress?->shipping_phone_number,
            'customer_address' => $this->shippingAddress?->shipping_address,
            'customer_city' => $this->shippingAddress?->shipping_city,
            'customer_region' => $this->shippingAddress?->shipping_region,
            'customer_postal_code' => $this->shippingAddress?->shipping_postal_code,
            'customer_country' => $this->shippingAddress?->shipping_country,
            // 'billing_address' => $this->billingAddress?->billing_address,
            // 'billing_city' => $this->billingAddress?->billing_city,
            // 'billing_region' => $this->billingAddress?->billing_region,
            // 'billing_postal_code' => $this->billingAddress?->billing_postal_code,
            // 'billing_country' => $this->billingAddress?->billing_country,
           

            
        ];

    }
}
