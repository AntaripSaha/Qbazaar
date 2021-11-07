<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id'            => $this->id,
            'cat_id'        => $this->category_id,
            'slug'          => $this->slug,
            'thumbnail'     => '/uploads/media/' . $this->thumbnail,
            'banner'        => '/uploads/media/' . $this->banner,
            'name'          => $this->translation->name,
            'description'   => $this->translation->description,
            'price'         => $this->price,
            'special_price' => $this->special_price,
            'current_price' => $this->special_price > 0  ? $this->special_price : $this->price,
            '_price'        => $this->special_price > 0 ? '<del>'.decimalPlace($this->price, currency()) .'</del>  '.decimalPlace($this->special_price, currency()) : decimalPlace($this->price, currency()),
            'in_stock'      => $this->in_stock,
            'unit'          => $this->unit_number . ' ' . $this->unit->translation->short_name,
        ];
    }
}
