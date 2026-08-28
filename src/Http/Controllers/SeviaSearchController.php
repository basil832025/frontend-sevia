<?php

namespace Basil832025\FrontendSevia\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shop\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SeviaSearchController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale() ?: 'uk';
        $term = trim((string) $request->query('q', ''));
        $perPage = 24;

        $query = Product::query()
            ->active()
            ->mainProduct()
            ->where(function (Builder $query): void {
                $query->whereNull('is_imported')->orWhere('is_imported', false);
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

        if ($term !== '') {
            $like = '%' . addcslashes(Str::lower($term), '\\%_') . '%';

            $query->where(function (Builder $searchQuery) use ($like): void {
                $this->applyProductSearch($searchQuery, $like);
                $searchQuery->orWhereHas('children', fn (Builder $childQuery) => $this->applyProductSearch($childQuery, $like));
            });
        } else {
            $query->whereRaw('1 = 0');
        }

        $items = $query
            ->orderBy('sort')
            ->orderBy('id')
            ->get()
            ->map(fn (Product $product) => $this->productCard($product, $locale))
            ->values();

        $items = $this->sortProducts($items, $request, $term);

        $page = max(1, (int) $request->query('page', 1));
        $products = $items->forPage($page, $perPage)->values();
        $lastPage = max(1, (int) ceil($items->count() / $perPage));

        return view('front.sevia::search.results', [
            'query' => $term,
            'products' => $products,
            'productsTotal' => $items->count(),
            'page' => min($page, $lastPage),
            'lastPage' => $lastPage,
            'sort' => (string) $request->query('sort', 'relevance'),
        ]);
    }

    private function applyProductSearch(Builder $query, string $like): void
    {
        $query->where(function (Builder $query) use ($like): void {
            $query
                ->whereRaw('LOWER(sku) LIKE ?', [$like])
                ->orWhereRaw('LOWER(code2) LIKE ?', [$like])
                ->orWhereRaw('LOWER(title) LIKE ?', [$like]);
        });
    }

    private function sortProducts(Collection $items, Request $request, string $term): Collection
    {
        return match ((string) $request->query('sort', 'relevance')) {
            'price_asc' => $items->sortBy(fn (array $product) => [(float) $product['price'], (int) $product['id']])->values(),
            'price_desc' => $items->sortBy(fn (array $product) => [-1 * (float) $product['price'], (int) $product['id']])->values(),
            'new' => $items->sortBy(fn (array $product) => [$product['badge'] === 'New' ? 0 : 1, (int) $product['id']])->values(),
            default => $items->sortBy(fn (array $product) => [$this->relevanceScore($product, $term), (int) $product['id']])->values(),
        };
    }

    private function relevanceScore(array $product, string $term): int
    {
        $needle = Str::lower($term);
        $title = Str::lower(trim($product['brand'] . ' ' . $product['name']));
        $article = Str::lower(trim(($product['sku'] ?? '') . ' ' . ($product['code2'] ?? '')));

        if ($needle !== '' && $article === $needle) {
            return 0;
        }

        if ($needle !== '' && Str::startsWith($title, $needle)) {
            return 1;
        }

        if ($needle !== '' && str_contains($title, $needle)) {
            return 2;
        }

        if ($needle !== '' && str_contains($article, $needle)) {
            return 3;
        }

        return 4;
    }

    private function productCard(Product $product, string $locale): array
    {
        $variants = collect([$product])->merge($product->children ?? collect());
        $priceVariant = $variants->filter(fn (Product $variant) => (float) $variant->price > 0)->sortBy('price')->first() ?: $product;
        $title = $this->label($product, 'title', $locale);
        $brand = $this->productBrand($product, $locale);
        $name = $this->productName($title, $brand);
        $notes = $this->notes($product, $locale);
        $volume = $this->firstCharacteristicValue($priceVariant, ['obiem', 'obyem', 'volume', 'ml'], $locale) ?: '5 мл';
        $gender = $this->firstCharacteristicValue($product, ['stat', 'gender', 'sex', 'dlia-kogo', 'pol'], $locale);
        $type = $this->firstCharacteristicValue($product, ['tip', 'typ', 'type'], $locale) ?: 'Eau de Parfum';

        return [
            'id' => (int) $product->id,
            'sku' => (string) ($product->sku ?? ''),
            'code2' => (string) ($product->code2 ?? ''),
            'brand' => $brand,
            'name' => $name,
            'meta' => collect([$gender, $type])->filter()->implode(' · '),
            'notes' => $notes,
            'image' => $product->main_image_url ?: $product->image_url,
            'price' => (float) ($priceVariant->price ?? 0),
            'old_price_label' => (float) ($priceVariant->old_price ?? 0) > (float) ($priceVariant->price ?? 0)
                ? $this->money($priceVariant->old_price)
                : null,
            'price_label' => $this->money($priceVariant->price ?? 0),
            'unit' => '/ ' . $volume,
            'badge' => $product->is_new ? 'New' : ($product->is_hit ? 'Bestseller' : ($product->is_promo ? 'Sale' : null)),
            'url' => route('product.show', ['product' => $product->slug]),
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

    private function splitProductTitle(string $title): array
    {
        $title = trim($title);

        if ($title === '') {
            return ['Sevia', 'Perfume'];
        }

        $parts = preg_split('/\s+/', $title, 2);

        return [Str::upper($parts[0] ?? 'Sevia'), $parts[1] ?? $title];
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

        return $this->splitProductTitle($this->label($product, 'title', $locale))[0];
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
            $slug = (string) ($row->characteristic?->slug ?? '');

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
}
