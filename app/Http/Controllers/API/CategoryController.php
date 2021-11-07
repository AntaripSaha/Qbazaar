<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller {

    public function index() {
        $catregories = Category::with(['translation', 'child_categories'])
            ->where('is_active', 1)
            ->get();
        return CategoryResource::collection($catregories);
    }

    public function show($slug) {
        $category = Category::where('slug', $slug)->first();

        return new CategoryResource($category);
    }

    public function products($slug) {

        $products = Product::where('is_active', 1)
            ->where('product_type','general')
            ->whereHas('category', function (Builder $query) use ($slug) {
                $query->where('slug',$slug);
            })
            ->orderBy('slug')
            ->paginate(15);

        return ProductResource::collection($products);
    }

}
