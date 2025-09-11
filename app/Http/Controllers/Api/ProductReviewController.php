<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Product\Models\ProductReview;

class ProductReviewController extends Controller
{
    /**
     * GET /api/v1/products/{id}/reviews
     * Returns approved product reviews including image URLs.
     */
    public function index(Request $request, int $id)
    {
        $perPage = (int) ($request->query('per_page', 10));

        $reviews = ProductReview::query()
            ->where('product_id', $id)
            ->where('status', 'approved')
            ->with(['images'])
            ->orderByDesc('id')
            ->paginate($perPage);

        // Ensure images relation includes url and convenience image_urls.
        // ProductReviewAttachment already appends 'url'; ProductReview appends 'image_urls'.

        return response()->json($reviews);
    }
}

