<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'order_id'         => $this->id,
            'created_at'       => $this->created_at,
            'name'             => $this->customer_name,
            'email'            => $this->customer_email,
            'phone'            => $this->customer_phone,
            'shipping_address' => $this->shipping_address,
            'delivery_time'    => $this->delivery_time,
            'payment_method'   => $this->payment_method,
            'shipping_method'  => $this->shipping_method,
            'sub_total'        => $this->sub_total,
            'discount'         => $this->discount,
            'shipping_cost'    => $this->shipping_cost,
            'grand_total'      => $this->total,
            'payment_status'   => $this->payment_status,
            'delivery_status'  => $this->delivery_status,
            'products'         => $this->products,
        ];
    }
}
