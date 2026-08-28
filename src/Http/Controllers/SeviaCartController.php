<?php

namespace Basil832025\FrontendSevia\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeviaCartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function page()
    {
        $info = $this->cart->info();

        return view('front.sevia::cart.index', [
            'items' => $info['items'] ?? [],
            'qty' => (int) ($info['qty'] ?? 0),
            'total' => (float) ($info['total'] ?? $info['total_price'] ?? 0),
            'bottles' => $this->bottles(),
        ]);
    }

    public function add(Request $request)
    {
        $productId = (int) $request->input('product_id', $request->input('product', 0));

        if ($productId <= 0) {
            return response()->json([
                'ok' => false,
                'message' => 'Не передано товар.',
            ], 422);
        }

        $qty = max(1, (int) $request->input('qty', 1));
        $price = $request->has('price') ? (float) $request->input('price') : null;
        $meta = is_array($request->input('meta')) ? $request->input('meta') : [];

        return response()->json($this->cart->add($productId, $qty, $price, $meta));
    }

    public function quantity(Request $request)
    {
        $productId = (int) $request->input('product_id', 0);

        if ($productId <= 0) {
            return $this->cartResponse($request, [
                'ok' => false,
                'message' => 'Не передано товар.',
            ], 422);
        }

        $price = $request->has('price') ? (float) $request->input('price') : null;
        $meta = is_array($request->input('meta')) ? $request->input('meta') : [];

        if ($request->has('qty')) {
            $payload = $this->cart->setQty($productId, (int) $request->input('qty'), $price, $meta);
        } else {
            $payload = $this->cart->changeQty($productId, (int) $request->input('delta', 0), $price, $meta);
        }

        return $this->cartResponse($request, $payload);
    }

    public function remove(Request $request)
    {
        $productId = (int) $request->input('product_id', 0);

        if ($productId <= 0) {
            return $this->cartResponse($request, [
                'ok' => false,
                'message' => 'Не передано товар.',
            ], 422);
        }

        $meta = is_array($request->input('meta')) ? $request->input('meta') : [];

        return $this->cartResponse($request, $this->cart->remove($productId, $meta));
    }

    public function info()
    {
        return response()->json($this->cart->info());
    }

    private function cartResponse(Request $request, array $payload, int $status = 200)
    {
        if ($request->expectsJson()) {
            return response()->json($payload, $status);
        }

        return redirect()->route('cart.page');
    }

    private function bottles(): array
    {
        $locale = app()->getLocale();

        $category = ProductCategory::query()
            ->where('slug', 'flakoni')
            ->first();

        if (! $category) {
            return [];
        }

        return Product::query()
            ->where(function ($query) use ($category): void {
                $query
                    ->where('category_id', $category->id)
                    ->orWhereHas('categories', fn ($relation) => $relation->whereKey($category->id));
            })
            ->orderBy('sort')
            ->orderBy('id')
            ->get()
            ->map(fn (Product $product): array => [
                'id' => (int) $product->id,
                'title' => $this->label($product, 'title', $locale) ?: 'Флакон',
                'description' => Str::limit($this->cleanText($this->label($product, 'description', $locale)), 60, ''),
                'price' => (float) ($product->price ?? 0),
                'image' => $this->thumbnailUrl($product),
            ])
            ->values()
            ->all();
    }

    private function thumbnailUrl(Product $product): ?string
    {
        if (! empty($product->main_image_small)) {
            return $this->storageUrl((string) $product->main_image_small);
        }

        if (method_exists($product, 'getFirstMediaUrl')) {
            $thumb = $product->getFirstMediaUrl('images', 'thumb');

            if ($thumb !== '') {
                return $thumb;
            }
        }

        return $product->main_image_url ?: $product->image_url;
    }

    private function storageUrl(string $path): string
    {
        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    private function cleanText(string $value): string
    {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = preg_replace('/[\x{00A0}\x{200B}\x{200C}\x{200D}\x{FEFF}]+/u', ' ', $value) ?? $value;
        $value = preg_replace('/[•·|]+/u', ' ', $value) ?? $value;
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return trim($value, " \t\n\r\0\x0B-–—,.;:");
    }

    private function label($model, string $field, string $locale): string
    {
        if (method_exists($model, 'getTranslation')) {
            foreach ([$locale, 'uk', 'ru', 'en'] as $candidate) {
                $value = $model->getTranslation($field, $candidate, false);

                if (is_string($value) && trim($value) !== '') {
                    return trim(strip_tags($value));
                }
            }
        }

        $value = $model->{$field} ?? '';

        if (is_array($value)) {
            foreach ([$locale, 'uk', 'ru', 'en'] as $candidate) {
                if (! empty($value[$candidate]) && is_string($value[$candidate])) {
                    return trim(strip_tags($value[$candidate]));
                }
            }

            return '';
        }

        return is_string($value) ? trim(strip_tags($value)) : '';
    }
}
