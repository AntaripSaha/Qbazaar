<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;

class PageController extends Controller {

    public function get_page() {
        $page = Page::where('slug', request()->slug)
            ->where('status', 1)
            ->first();

        if ($page) {
            return new PageResource($page);
        }

        return response()->json(['data' => []], 404);

    }

}
