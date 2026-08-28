<?php

namespace Basil832025\FrontendSevia\Http\Controllers;

use App\Enums\ReviewStatus;
use App\Http\Controllers\Controller;
use App\Mail\ReviewNotificationMail;
use App\Models\Shop\Product;
use App\Models\Shop\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SeviaProductReviewController extends Controller
{
    public function store(Product $product, Request $request)
    {
        if ($request->filled('hp')) {
            return response()->json(['status' => 'ok']);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'content' => ['required', 'string', 'max:3000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        if ($product->parent_id) {
            $product = $product->parent ?: $product;
        }

        $review = ProductReview::create([
            'product_id' => $product->getKey(),
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'content' => $data['content'],
            'rating' => (int) $data['rating'],
            'status' => ReviewStatus::Pending,
            'ip' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 255),
        ]);

        try {
            $emails = config('notifications.order_notification_email', []);

            if (is_string($emails)) {
                $emails = array_filter(array_map('trim', explode(',', $emails)));
            }

            if (! empty($emails)) {
                $moderationUrl = \App\Filament\Clusters\Products\Resources\ProductReviewResource::getUrl('edit', [
                    'record' => $review,
                ]);

                $review->loadMissing('product.parent');

                Mail::to($emails)->send(new ReviewNotificationMail(
                    type: 'product',
                    review: $review,
                    moderationUrl: $moderationUrl,
                ));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send Sevia review notification email: ' . $e->getMessage(), [
                'review_id' => $review->id ?? null,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
