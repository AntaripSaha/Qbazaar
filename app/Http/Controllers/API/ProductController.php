<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller {

    public function search() {
        $keyword = request('keyword');
        if ($keyword == '') {
            return ProductResource::collection([]);
        }

        $products = Product::where('is_active', 1)
            ->whereHas('translation', function (Builder $query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            })
            ->orderBy('slug')
            ->limit(15)
            ->get();

        return ProductResource::collection($products);
    }

    public function offers() {
        $products = Product::where('is_active', 1)
            ->where('product_type', 'offer')
            ->orderBy('slug')
            ->paginate(12);

        return ProductResource::collection($products);
    }

}
