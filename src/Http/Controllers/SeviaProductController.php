<?php

namespace Basil832025\FrontendSevia\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shop\Product;
use App\Models\Shop\ProductReview;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeviaProductController extends Controller
{
    public function show(Product $product)
    {
        $locale = app()->getLocale() ?: 'uk';

        if ($product->parent_id) {
            $parent = Product::query()->whereKey($product->parent_id)->firstOrFail();

            return redirect()->route('product.show', ['product' => $parent->slug]);
        }

        abort_unless((int) $product->in_stock === 1, 404);

        $product->load([
            'children.productCharacteristicValues.characteristic',
            'children.productCharacteristicValues.characteristicValue',
            'mainCategory',
            'categories',
            'images',
            'ingredients',
            'productCharacteristicValues.characteristic',
            'productCharacteristicValues.characteristicValue',
        ]);

        abort_unless($this->hasVisibleCategory($product), 404);

        $detail = $this->productDetail($product, $locale);
        $related = $this->relatedProducts($product, $locale);
        $reviewProductIds = collect([$product->id])
            ->merge($product->children?->pluck('id') ?? collect())
            ->map(fn ($id) => (int) $id)
            ->values();
        $reviews = ProductReview::query()
            ->published()
            ->whereIn('product_id', $reviewProductIds)
            ->latest('created_at')
            ->paginate(10, ['*'], 'reviews_page');
        $stats = $this->reviewStats($reviewProductIds);

        return view('front.sevia::product.show', [
            'product' => $product,
            'detail' => $detail,
            'relatedProducts' => $related,
            'reviews' => $reviews,
            'stats' => $stats,
        ]);
    }

    private function productDetail(Product $product, string $locale): array
    {
        $variants = collect([$product])->merge($product->children ?? collect());
        $priceVariant = $variants->filter(fn (Product $variant) => (float) $variant->price > 0)->sortBy('price')->first() ?: $product;
        $title = $this->label($product, 'title', $locale);
        $brand = $this->productBrand($product, $locale);
        $name = $this->productName($title, $brand);
        $gender = $this->firstCharacteristicValue($product, ['stat', 'gender', 'sex', 'dlia-kogo', 'pol'], $locale);
        $type = $this->firstCharacteristicValue($product, ['tip', 'typ', 'type'], $locale) ?: 'Eau de Parfum';
        $volume = $this->firstCharacteristicValue($priceVariant, ['obiem', 'obyem', 'volume', 'ml'], $locale) ?: '5 мл';
        $selectedVolumeMl = $this->volumeAmount($volume) ?: 5;
        $pricePerMl = $this->pricePerUnit($priceVariant);
        $price = $pricePerMl * $selectedVolumeMl;
        $short = trim(strip_tags((string) ($product->short_desc ?: '')));
        $description = trim(strip_tags($this->label($product, 'description', $locale)));

        if ($short === '') {
            $short = Str::limit($description ?: $this->notes($product, $locale), 130);
        }

        if ($description === '') {
            $description = $short;
        }

        $image = $product->main_image_url ?: $product->image_url;

        $freeShippingFrom = max(0, (float) \App\Models\Setting::admin('cart.free_shipping_from', 0));
        $benefits = [
            'Гарантія оригіналу - чек і сертифікат',
        ];
        if ($freeShippingFrom > 0) {
            $benefits[] = 'Нова Пошта 1-2 дні - безкоштовно від ' . $this->money($freeShippingFrom);
        }
        $benefits[] = 'Без передоплати на першу покупку';

        return [
            'brand' => $brand,
            'name' => $name,
            'title' => trim($brand . ' ' . $name),
            'sku' => (string) ($product->sku ?? ''),
            'meta' => collect([$gender, $type])->filter()->implode(' · '),
            'image' => $image,
            'gallery' => $this->galleryImages($product, $image),
            'short' => $short,
            'description' => $description,
            'price' => $price,
            'price_label' => $this->money($price),
            'old_price_label' => (float) ($priceVariant->old_price ?? 0) > $price ? $this->money($priceVariant->old_price) : null,
            'unit' => 'за 1 мл',
            'unit_price' => $this->unitPricePerMlLabel($pricePerMl),
            'selected_variant_id' => (int) $priceVariant->id,
            'volumes' => $this->volumes($variants, $priceVariant, $locale),
            'notes' => $this->noteGroups($product, $locale),
            'benefits' => $benefits,
        ];
    }

    private function volumes(Collection $variants, Product $selected, string $locale): Collection
    {
        $fallbackUnitPrice = $this->pricePerUnit($selected);
        $variantVolumes = $variants
            ->filter(fn (Product $variant) => (float) $variant->price > 0)
            ->sortBy(fn (Product $variant) => [(float) $variant->price, (int) $variant->id])
            ->mapWithKeys(function (Product $variant) use ($locale): array {
                $label = $this->normalizeVolumeLabel(
                    $this->firstCharacteristicValue($variant, ['obiem', 'obyem', 'volume', 'ml'], $locale) ?: ($variant->parent_id ? '5 мл' : '3 мл')
                );
                $amount = $this->volumeAmount($label) ?: 1;
                $unitPrice = $this->pricePerUnit($variant);

                return [$label => [
                    'id' => (int) $variant->id,
                    'price' => $unitPrice * $amount,
                    'price_label' => $this->money($unitPrice * $amount),
                    'unit_price_label' => $this->unitPricePerMlLabel($unitPrice),
                ]];
            });

        $selectedLabel = $this->normalizeVolumeLabel(
            $this->firstCharacteristicValue($selected, ['obiem', 'obyem', 'volume', 'ml'], $locale) ?: '5 мл'
        );

        return collect($this->configuredVolumeLabels())
            ->map(function (string $label) use ($variantVolumes, $fallbackUnitPrice, $selectedLabel): array {
                $amount = $this->volumeAmount($label) ?: 1;
                $price = $variantVolumes[$label]['price'] ?? ($fallbackUnitPrice * $amount);
                $unitPriceLabel = $variantVolumes[$label]['unit_price_label'] ?? $this->unitPricePerMlLabel($fallbackUnitPrice);

                return [
                    'id' => $variantVolumes[$label]['id'] ?? null,
                    'label' => $label,
                    'price' => $price,
                    'price_label' => $this->money($price),
                    'unit_price_label' => $unitPriceLabel,
                    'selected' => $label === $selectedLabel,
                ];
            })
            ->values();
    }

    private function normalizeVolumeLabel(string $label): string
    {
        if (preg_match('/(\d+)/', $label, $matches)) {
            return ((int) $matches[1]) . ' мл';
        }

        return trim($label);
    }

    private function volumeAmount(string $label): ?float
    {
        if (preg_match('/([\d.,]+)/', $label, $matches)) {
            return (float) str_replace(',', '.', $matches[1]);
        }

        return null;
    }

    private function galleryImages(Product $product, string $mainImage): Collection
    {
        return collect([$mainImage])
            ->merge(($product->images ?? collect())->pluck('path')->map(fn (?string $path): ?string => $this->imageUrl($path)))
            ->filter()
            ->unique()
            ->values();
    }

    private function imageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    private function pricePerUnit(Product $product): float
    {
        $quantity = (float) ($product->price_unit_quantity ?? 1);

        if ($quantity <= 0) {
            $quantity = 1;
        }

        return (float) ($product->price ?? 0) / $quantity;
    }

    private function relatedProducts(Product $product, string $locale): Collection
    {
        $categoryIds = collect([$product->category_id])
            ->merge($product->categories?->pluck('id') ?? collect())
            ->filter()
            ->unique()
            ->values();

        $query = Product::query()
            ->active()
            ->mainProduct()
            ->whereKeyNot($product->id)
            ->where(function (Builder $query): void {
                $query->whereNull('is_imported')->orWhere('is_imported', false);
            })
            ->where(function (Builder $query): void {
                $query
                    ->whereHas('mainCategory', fn (Builder $q) => $q->where('is_visible', true))
                    ->orWhereHas('categories', fn (Builder $q) => $q->where('bs_product_categories.is_visible', true));
            })
            ->with([
                'children.productCharacteristicValues.characteristic',
                'children.productCharacteristicValues.characteristicValue',
                'mainCategory',
                'categories',
                'ingredients',
                'productCharacteristicValues.characteristic',
                'productCharacteristicValues.characteristicValue',
            ]);

        if ($categoryIds->isNotEmpty()) {
            $query->where(function (Builder $query) use ($categoryIds): void {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereHas('categories', fn (Builder $q) => $q->whereIn('bs_product_categories.id', $categoryIds));
            });
        }

        $items = $query->orderBy('sort')->orderBy('id')->limit(5)->get();

        if ($items->count() < 5) {
            $fallback = Product::query()
                ->active()
                ->mainProduct()
                ->whereKeyNot($product->id)
                ->whereNotIn('id', $items->pluck('id'))
                ->where(function (Builder $query): void {
                    $query
                        ->whereHas('mainCategory', fn (Builder $q) => $q->where('is_visible', true))
                        ->orWhereHas('categories', fn (Builder $q) => $q->where('bs_product_categories.is_visible', true));
                })
                ->with([
                    'children.productCharacteristicValues.characteristic',
                    'children.productCharacteristicValues.characteristicValue',
                    'mainCategory',
                    'categories',
                    'ingredients',
                    'productCharacteristicValues.characteristic',
                    'productCharacteristicValues.characteristicValue',
                ])
                ->orderBy('sort')
                ->orderBy('id')
                ->limit(5 - $items->count())
                ->get();

            $items = $items->merge($fallback);
        }

        return $items->map(fn (Product $item) => $this->productCard($item, $locale))->values();
    }

    private function hasVisibleCategory(Product $product): bool
    {
        if ((bool) ($product->mainCategory?->is_visible ?? false)) {
            return true;
        }

        return $product->categories?->contains(fn ($category): bool => (bool) ($category->is_visible ?? false)) ?? false;
    }

    private function reviewStats(Collection $productIds)
    {
        return ProductReview::query()
            ->published()
            ->whereIn('product_id', $productIds)
            ->selectRaw('COUNT(*) as total,
                AVG(rating) as avg_rating,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as r5,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as r4,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as r3,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as r2,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as r1')
            ->first();
    }

    private function productCard(Product $product, string $locale): array
    {
        $variants = collect([$product])->merge($product->children ?? collect());
        $priceVariant = $variants->filter(fn (Product $variant) => (float) $variant->price > 0)->sortBy('price')->first() ?: $product;
        $title = $this->label($product, 'title', $locale);
        $brand = $this->productBrand($product, $locale);
        $name = $this->productName($title, $brand);
        $gender = $this->firstCharacteristicValue($product, ['stat', 'gender', 'sex', 'dlia-kogo', 'pol'], $locale);
        $volume = $this->firstCharacteristicValue($priceVariant, ['obiem', 'obyem', 'volume', 'ml'], $locale) ?: '5 мл';

        return [
            'id' => (int) $product->id,
            'brand' => $brand,
            'name' => $name,
            'meta' => collect([$gender, $volume])->filter()->implode(' · '),
            'notes' => $this->notes($product, $locale),
            'image' => $product->main_image_url ?: $product->image_url,
            'cart_product_id' => (int) $priceVariant->id,
            'cart_price' => (float) ($priceVariant->price ?? 0),
            'cart_label' => collect([$brand, $name, $volume, $this->money($priceVariant->price ?? 0)])->filter()->implode(' · '),
            'price_label' => $this->money($priceVariant->price ?? 0),
            'url' => route('product.show', ['product' => $product->slug]),
        ];
    }

    private function noteGroups(Product $product, string $locale): array
    {
        $notes = collect($product->ingredients ?? [])
            ->map(fn ($ingredient) => $this->label($ingredient, 'name', $locale))
            ->filter()
            ->values();

        if ($notes->isEmpty()) {
            $notes = collect(['Неролі', 'Малина', 'Жасмин', 'Пачулі', 'Амбра', 'Мускус']);
        }

        $chunks = $notes->chunk(max(1, (int) ceil($notes->count() / 3)))->values();

        return [
            ['title' => 'Верхні', 'items' => $chunks->get(0, collect())->values()],
            ['title' => 'Серце', 'items' => $chunks->get(1, collect())->values()],
            ['title' => 'База', 'items' => $chunks->get(2, collect())->values()],
        ];
    }

    private function label($model, string $field, string $locale): string
    {
        if (method_exists($model, 'getTranslation')) {
            foreach ([$locale, 'uk', 'ru', 'en'] as $candidate) {
                $value = $model->getTranslation($field, $candidate, false);

                if (is_string($value) && trim($value) !== '') {
                    return trim($value);
                }
            }
        }

        $raw = $model->{$field} ?? '';

        if (is_array($raw)) {
            return (string) ($raw[$locale] ?? $raw['uk'] ?? $raw['ru'] ?? $raw['en'] ?? reset($raw) ?: '');
        }

        return trim((string) $raw);
    }

    private function productBrand(Product $product, string $locale): string
    {
        $category = $product->mainCategory ?: $product->categories?->first();

        if ($category) {
            $brand = $this->label($category, 'title', $locale);

            if ($brand !== '') {
                return $brand;
            }
        }

        $title = trim($this->label($product, 'title', $locale));
        $knownBrands = ['Paco Rabanne', 'Tom Ford', 'Louis Vuitton', 'Marc-Antoine Barrois', 'Clive Christian', 'Maison Francis Kurkdjian'];

        foreach ($knownBrands as $brand) {
            if (Str::startsWith(Str::lower($title), Str::lower($brand . ' '))) {
                return $brand;
            }
        }

        $parts = preg_split('/\s+/', $title, 2);

        return $parts[0] ?? 'Sevia';
    }

    private function productName(string $title, string $brand): string
    {
        $title = trim($title);

        if ($title === '') {
            return 'Perfume';
        }

        if ($brand !== '' && Str::startsWith(Str::lower($title), Str::lower($brand . ' '))) {
            return trim(Str::after($title, $brand));
        }

        return $title;
    }

    private function notes(Product $product, string $locale): string
    {
        $ingredients = collect($product->ingredients ?? [])
            ->map(fn ($ingredient) => $this->label($ingredient, 'name', $locale))
            ->filter()
            ->take(3)
            ->implode(' · ');

        if ($ingredients !== '') {
            return $ingredients;
        }

        $short = trim(strip_tags((string) ($product->short_desc ?? '')));

        if ($short !== '') {
            return Str::limit($short, 68);
        }

        return Str::limit(strip_tags($this->label($product, 'description', $locale)), 68);
    }

    private function firstCharacteristicValue(Product $product, array $needles, string $locale): ?string
    {
        foreach ($product->productCharacteristicValues ?? collect() as $row) {
            $slug = Str::lower((string) ($row->characteristic?->slug ?? ''));

            if (! collect($needles)->contains(fn (string $needle) => str_contains($slug, $needle))) {
                continue;
            }

            if ($row->characteristicValue) {
                return $this->label($row->characteristicValue, 'value', $locale);
            }

            if ($row->value_text) {
                return (string) $row->value_text;
            }
        }

        return null;
    }

    private function money($value): string
    {
        return rtrim(rtrim(number_format((float) $value, 2, '.', ' '), '0'), '.') . ' ₴';
    }

    private function unitPricePerMlLabel(float $price): string
    {
        return $this->money($price);
    }

    private function unitPriceLabel(float $price, string $volume): string
    {
        preg_match('/([\d.,]+)/', $volume, $matches);
        $ml = isset($matches[1]) ? (float) str_replace(',', '.', $matches[1]) : 0.0;

        if ($price <= 0 || $ml <= 0) {
            return '';
        }

        return $this->money($price / $ml) . '/мл';
    }

    private function configuredVolumeLabels(): array
    {
        $raw = (string) config('services.callcenter.order_menu_unit_options.ml', '3,5,10,15,20,30');

        $labels = collect(explode(',', $raw))
            ->map(fn (string $value): int => (int) trim($value))
            ->filter(fn (int $value): bool => $value > 0)
            ->unique()
            ->map(fn (int $value): string => $value . ' мл')
            ->values()
            ->all();

        return $labels !== [] ? $labels : ['3 мл', '5 мл', '10 мл', '15 мл', '20 мл', '30 мл'];
    }
}
