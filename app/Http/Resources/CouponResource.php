<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id'         => $this->id,
            'code'       => $this->code,
            'name'       => $this->translation->name,
            'value'      => $this->value,
            'is_percent' => $this->is_percent,
            'used'       => $this->used,
            'is_active'  => $this->is_active,
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'products'   => $this->products,
            'categories' => $this->categories,
        ];
    }
}
