<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\Product\Contracts\ProductReview as ProductReviewContract;
use Webkul\Product\Database\Factories\ProductReviewFactory;

class ProductReview extends Model implements ProductReviewContract
{
    use HasFactory;

    /**
     * Always eager load these relationships.
     * Ensures API/serialization includes review images with URLs.
     *
     * @var array
     */
    protected $with = ['images'];

    /**
     * The accessors to append to the model's array form.
     * Provides a simple list of image URLs alongside relations.
     *
     * @var array
     */
    protected $appends = ['image_urls'];

    protected $fillable = [
        'comment',
        'title',
        'rating',
        'status',
        'product_id',
        'customer_id',
        'name',
    ];

    /**
     * Get the product attribute family that owns the product.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerProxy::modelClass());
    }

    /**
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * The images that belong to the review.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductReviewAttachmentProxy::modelClass(), 'review_id');
    }

    /**
     * Get only the image URLs for convenience in API responses.
     */
    public function getImageUrlsAttribute(): array
    {
        return $this->images
            ? $this->images->pluck('url')->filter()->values()->toArray()
            : [];
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return ProductReviewFactory::new();
    }
}
