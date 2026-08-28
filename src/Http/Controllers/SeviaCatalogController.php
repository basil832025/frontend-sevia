<?php

namespace Basil832025\FrontendSevia\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shop\Characteristic;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use App\Services\InstagramFeedService;
use App\Support\GuestFavoritesStore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeviaCatalogController extends Controller
{
    public function home()
    {
        $locale = app()->getLocale() ?: 'uk';
        $products = $this->baseQuery()
            ->where('is_home', true)
            ->where('is_hit', true)
            ->with([
                'children.productCharacteristicValues.characteristic',
                'children.productCharacteristicValues.characteristicValue',
                'mainCategory',
                'categories',
                'ingredients',
                'productCharacteristicValues.characteristic',
                'productCharacteristicValues.characteristicValue',
            ])
            ->get()
            ->map(fn (Product $product) => $this->productCard($product, $locale))
            ->filter(fn (array $product): bool => $product['volumes']->isNotEmpty())
            ->take(5)
            ->values();

        $discountProducts = $this->baseQuery()
            ->where('is_home', true)
            ->with([
                'children.productCharacteristicValues.characteristic',
                'children.productCharacteristicValues.characteristicValue',
                'mainCategory',
                'categories',
                'ingredients',
                'productCharacteristicValues.characteristic',
                'productCharacteristicValues.characteristicValue',
            ])
            ->get()
            ->map(fn (Product $product) => $this->productCard($product, $locale))
            ->filter(fn (array $product): bool => $product['old_price_label'] !== null && $product['volumes']->isNotEmpty())
            ->take(5)
            ->values();

        return view('front.sevia::home', [
            'homeProducts' => $products,
            'liveDiscountProducts' => $discountProducts,
            'instagramPosts' => app(InstagramFeedService::class)->latest(6),
        ]);
    }

    public function index(Request $request, ?ProductCategory $category = null)
    {
        abort_if($category && ! $category->is_visible, 404);

        $locale = app()->getLocale() ?: 'uk';
        $perPage = 24;

        $baseQuery = $this->baseQuery($category);
        $saleOnly = $request->boolean('sale') || $request->routeIs('sale.index');

        $rootIds = (clone $baseQuery)->pluck('id');
        $filterRootIds = $this->rootIdsMatchingPrice($rootIds, $request);
        $filterGroups = $this->filterGroups($filterRootIds, $locale);
        $selectedFilters = collect($request->input('filters', []))
            ->map(fn ($values) => collect((array) $values)->map(fn ($value) => (int) $value)->filter()->values()->all())
            ->filter()
            ->all();

        $allProducts = $this->filteredProducts($baseQuery, $request, $locale, $selectedFilters);

        if ($saleOnly) {
            $allProducts = $allProducts
                ->filter(fn (array $product): bool => $product['old_price_label'] !== null && $product['discount_percent'] > 0)
                ->values();
        }

        if ($allProducts->isEmpty() && $rootIds->isEmpty()) {
            $allProducts = $this->fallbackProducts();
        }

        $page = max(1, (int) $request->query('page', 1));
        $products = $allProducts->forPage($page, $perPage)->values();
        $lastPage = max(1, (int) ceil($allProducts->count() / $perPage));
        [$priceMin, $priceMax] = $this->priceBounds($rootIds);

        return view('front.sevia::catalog.index', [
            'category' => $category,
            'breadcrumbs' => $this->breadcrumbs($category, $locale),
            'filterGroups' => $filterGroups,
            'selectedFilters' => $selectedFilters,
            'products' => $products,
            'productsTotal' => $allProducts->count(),
            'page' => min($page, $lastPage),
            'lastPage' => $lastPage,
            'sort' => (string) $request->query('sort', 'popular'),
            'priceMin' => $priceMin,
            'priceMax' => $priceMax,
            'currentPriceMin' => $request->query('price_min', $priceMin),
            'currentPriceMax' => $request->query('price_max', $priceMax),
            'volumeAmounts' => $this->configuredVolumeAmounts(),
            'selectedVolume' => (int) $request->query('volume', 0),
            'favoriteIds' => $this->favoriteIds(),
            'saleOnly' => $saleOnly,
        ]);
    }

    private function favoriteIds(): array
    {
        $client = Auth::guard('web')->user();

        if ($client) {
            return $client->favorites()->pluck('bs_products.id')->map(fn ($id) => (int) $id)->all();
        }

        return GuestFavoritesStore::idsFromRequest();
    }

    public function count(Request $request, ?ProductCategory $category = null)
    {
        abort_if($category && ! $category->is_visible, 404);

        $locale = app()->getLocale() ?: 'uk';
        $selectedFilters = $this->selectedFilters($request);

        return response()->json([
            'count' => $this->filteredProducts($this->baseQuery($category), $request, $locale, $selectedFilters)->count(),
        ]);
    }

    private function baseQuery(?ProductCategory $category = null): Builder
    {
        return Product::query()
            ->active()
            ->mainProduct()
            ->where(function (Builder $query): void {
                $query->whereNull('is_imported')->orWhere('is_imported', false);
            })
            ->where(function (Builder $categoryQuery): void {
                $categoryQuery
                    ->whereHas('mainCategory', fn (Builder $q) => $q->where('is_visible', true))
                    ->orWhereHas('categories', fn (Builder $q) => $q->where('bs_product_categories.is_visible', true));
            })
            ->when($category, function (Builder $query) use ($category): void {
                $categoryIds = $this->visibleCategoryIdsFor($category);

                if ($categoryIds === []) {
                    $query->whereRaw('1 = 0');

                    return;
                }

                $query->where(function (Builder $categoryQuery) use ($categoryIds): void {
                    $categoryQuery
                        ->whereIn('category_id', $categoryIds)
                        ->orWhereHas('categories', fn (Builder $q) => $q->whereIn('bs_product_categories.id', $categoryIds));
                });
            });
    }

    private function visibleCategoryIdsFor(ProductCategory $category): array
    {
        if (! $category->is_visible) {
            return [];
        }

        $descendantIds = $category->getDescendantIds();
        $visibleDescendantIds = $descendantIds === []
            ? []
            : ProductCategory::query()
                ->whereIn('id', $descendantIds)
                ->where('is_visible', true)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

        return array_values(array_unique(array_merge([(int) $category->id], $visibleDescendantIds)));
    }

    private function selectedFilters(Request $request): array
    {
        return collect($request->input('filters', []))
            ->map(fn ($values) => collect((array) $values)->map(fn ($value) => (int) $value)->filter()->values()->all())
            ->filter()
            ->all();
    }

    private function filteredProducts(Builder $baseQuery, Request $request, string $locale, array $selectedFilters): Collection
    {
        $query = (clone $baseQuery)
            ->with([
                'children.productCharacteristicValues.characteristic',
                'children.productCharacteristicValues.characteristicValue',
                'mainCategory',
                'categories',
                'ingredients',
                'productCharacteristicValues.characteristic',
                'productCharacteristicValues.characteristicValue',
            ]);

        foreach ($selectedFilters as $filterKey => $valueIds) {
            if ($filterKey === 'brand') {
                $this->applyProductGroupFilter($query, $valueIds);
                continue;
            }

            $this->applyCharacteristicFilter($query, (int) $filterKey, $valueIds);
        }

        $this->applySort($query, (string) $request->query('sort', 'popular'));

        return $this->sortCardCollection(
            $query->get()
                ->map(fn (Product $product) => $this->productCard($product, $locale))
                ->filter(fn (array $product): bool => $this->matchesPrice($product, $request))
                ->filter(fn (array $product): bool => $this->matchesVolume($product, $request))
                ->values(),
            $request
        );
    }

    private function matchesVolume(array $product, Request $request): bool
    {
        $volume = (int) $request->query('volume', 0);

        if ($volume <= 0) {
            return true;
        }

        return collect($product['volumes'] ?? [])
            ->contains(fn (array $option): bool => (int) preg_replace('/\D+/', '', (string) ($option['label'] ?? '')) === $volume);
    }

    private function matchesPrice(array $product, Request $request): bool
    {
        $min = $request->query('price_min');
        $max = $request->query('price_max');

        if ($min !== null && $min !== '' && $product['price'] < (float) $min) {
            return false;
        }

        if ($max !== null && $max !== '' && $product['price'] > (float) $max) {
            return false;
        }

        return true;
    }

    private function applyCharacteristicFilter(Builder $query, int $characteristicId, array $valueIds): void
    {
        if ($valueIds === []) {
            return;
        }

        $matchingProductIds = DB::table('bs_product_characteristic_value')
            ->where('characteristic_id', $characteristicId)
            ->whereIn('characteristic_value_id', $valueIds)
            ->pluck('product_id');

        $matchingRootIds = Product::query()
            ->whereIn('id', $matchingProductIds)
            ->selectRaw('CASE WHEN parent_id IS NULL THEN id ELSE parent_id END AS root_id')
            ->pluck('root_id')
            ->filter()
            ->unique()
            ->values();

        $query->whereIn('id', $matchingRootIds->isEmpty() ? [0] : $matchingRootIds);
    }

    private function applyProductGroupFilter(Builder $query, array $categoryIds): void
    {
        if ($categoryIds === []) {
            return;
        }

        $query->where(function (Builder $categoryQuery) use ($categoryIds): void {
            $categoryQuery
                ->whereIn('category_id', $categoryIds)
                ->orWhereHas('categories', fn (Builder $q) => $q->whereIn('bs_product_categories.id', $categoryIds));
        });
    }

    private function rootIdsMatchingPrice(Collection $rootIds, Request $request): Collection
    {
        $min = $request->query('price_min');
        $max = $request->query('price_max');

        if (($min === null || $min === '') && ($max === null || $max === '')) {
            return $rootIds;
        }

        if ($rootIds->isEmpty()) {
            return $rootIds;
        }

        $rows = Product::query()
            ->where(function (Builder $query) use ($rootIds): void {
                $query->whereIn('id', $rootIds)
                    ->orWhereIn('parent_id', $rootIds);
            })
            ->where('price', '>', 0)
            ->selectRaw('CASE WHEN parent_id IS NULL THEN id ELSE parent_id END AS root_id, MIN(price) as card_price')
            ->groupBy('root_id')
            ->get();

        return $rows
            ->filter(function ($row) use ($min, $max): bool {
                $price = (float) $row->card_price;

                if ($min !== null && $min !== '' && $price < (float) $min) {
                    return false;
                }

                if ($max !== null && $max !== '' && $price > (float) $max) {
                    return false;
                }

                return true;
            })
            ->pluck('root_id')
            ->map(fn ($id) => (int) $id)
            ->values();
    }

    private function filterGroups(Collection $rootIds, string $locale): Collection
    {
        if ($rootIds->isEmpty()) {
            return collect();
        }

        $productIds = Product::query()
            ->whereIn('parent_id', $rootIds)
            ->pluck('id')
            ->merge($rootIds)
            ->unique()
            ->values();

        $counts = DB::table('bs_product_characteristic_value')
            ->whereIn('product_id', $productIds)
            ->whereNotNull('characteristic_value_id')
            ->groupBy('characteristic_id', 'characteristic_value_id')
            ->select('characteristic_id', 'characteristic_value_id', DB::raw('COUNT(DISTINCT product_id) as products_count'))
            ->get()
            ->groupBy('characteristic_id');

        $groups = Characteristic::query()
            ->whereIn('id', $counts->keys()->map(fn ($id) => (int) $id))
            ->with(['values' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('id')])
            ->get()
            ->map(function (Characteristic $characteristic) use ($counts, $locale): ?array {
                $rows = $counts->get($characteristic->id, collect())->keyBy('characteristic_value_id');
                $slug = (string) ($characteristic->slug ?: $characteristic->id);
                $title = $this->label($characteristic, 'name', $locale);
                $role = $this->filterRole($slug, $title);

                if ($role === null) {
                    return null;
                }

                return [
                    'id' => (int) $characteristic->id,
                    'slug' => $slug,
                    'role' => $role,
                    'title' => match ($role) {
                        'gender' => 'Стать',
                        'aroma' => 'Тип аромату',
                    },
                    'priority' => ['gender' => 0, 'aroma' => 1][$role],
                    'values' => $characteristic->values
                        ->map(function ($value) use ($rows, $locale): ?array {
                            $count = (int) ($rows->get($value->id)->products_count ?? 0);

                            if ($count <= 0) {
                                return null;
                            }

                            return [
                                'id' => (int) $value->id,
                                'title' => $this->label($value, 'value', $locale),
                                'count' => $count,
                            ];
                        })
                        ->filter()
                        ->values(),
                ];
            })
            ->filter(fn (?array $group) => $group !== null && $group['values']->isNotEmpty())
            ->groupBy('role')
            ->map(fn (Collection $sameRoleGroups) => $sameRoleGroups->sortByDesc(fn (array $group) => $group['values']->sum('count'))->first())
            ->sortBy('priority')
            ->values();

        $brandGroup = $this->productGroupFilter($rootIds, $locale);

        if ($brandGroup !== null) {
            $groups->push($brandGroup);
        }

        return $groups->sortBy('priority')->values();
    }

    private function filterRole(string $slug, string $title): ?string
    {
        $haystack = Str::of($slug . ' ' . $title)->lower()->toString();

        if (
            str_contains($haystack, 'stat')
            || str_contains($haystack, 'gender')
            || str_contains($haystack, 'sex')
            || str_contains($haystack, 'pol')
            || str_contains($haystack, 'стать')
            || str_contains($haystack, 'стат')
        ) {
            return 'gender';
        }

        if (
            str_contains($haystack, 'aromat')
            || str_contains($haystack, 'aroma')
            || str_contains($haystack, 'family')
            || str_contains($haystack, 'simeistvo')
            || str_contains($haystack, 'тип')
            || str_contains($haystack, 'аромат')
        ) {
            return 'aroma';
        }

        return null;
    }

    private function productGroupFilter(Collection $rootIds, string $locale): ?array
    {
        $mainCategoryRows = Product::query()
            ->whereIn('id', $rootIds)
            ->whereNotNull('category_id')
            ->get(['id', 'category_id']);

        $pivotCategoryRows = DB::table('bs_product_product_category')
            ->whereIn('product_id', $rootIds)
            ->whereNotNull('product_category_id')
            ->get(['product_id', 'product_category_id']);

        $productIdsByCategory = [];

        foreach ($mainCategoryRows as $row) {
            $categoryId = (int) $row->category_id;
            $productIdsByCategory[$categoryId] ??= [];
            $productIdsByCategory[$categoryId][(int) $row->id] = true;
        }

        foreach ($pivotCategoryRows as $row) {
            $categoryId = (int) $row->product_category_id;
            $productIdsByCategory[$categoryId] ??= [];
            $productIdsByCategory[$categoryId][(int) $row->product_id] = true;
        }

        $counts = collect($productIdsByCategory)
            ->map(fn (array $productIds) => count($productIds))
            ->filter(fn ($count) => (int) $count > 0);

        if ($counts->isEmpty()) {
            return null;
        }

        $values = ProductCategory::query()
            ->whereIn('id', $counts->keys()->map(fn ($id) => (int) $id))
            ->where('is_visible', true)
            ->orderBy('order')
            ->orderBy('id')
            ->get()
            ->map(fn (ProductCategory $category): array => [
                'id' => (int) $category->id,
                'title' => $this->label($category, 'title', $locale),
                'count' => (int) ($counts[$category->id] ?? 0),
            ])
            ->filter(fn (array $value) => $value['title'] !== '' && $value['count'] > 0)
            ->values();

        if ($values->isEmpty()) {
            return null;
        }

        return [
            'id' => 'brand',
            'slug' => 'brand',
            'role' => 'brand',
            'title' => 'Бренд',
            'priority' => 2,
            'values' => $values,
        ];
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
            'brand' => $brand,
            'name' => $name,
            'meta' => collect([$gender, $type])->filter()->implode(' · '),
            'notes' => $notes,
            'image' => $product->main_image_url ?: $product->image_url,
            'price' => (float) ($priceVariant->price ?? 0),
            'old_price' => (float) ($priceVariant->old_price ?? 0),
            'price_label' => $this->money($priceVariant->price ?? 0),
            'old_price_label' => (float) ($priceVariant->old_price ?? 0) > (float) ($priceVariant->price ?? 0)
                ? $this->money($priceVariant->old_price)
                : null,
            'discount_percent' => (float) ($priceVariant->old_price ?? 0) > (float) ($priceVariant->price ?? 0)
                ? (int) round((1 - ((float) $priceVariant->price / (float) $priceVariant->old_price)) * 100)
                : 0,
            'cart_product_id' => (int) $priceVariant->id,
            'cart_price' => (float) ($priceVariant->price ?? 0),
            'cart_label' => collect([$brand, $name, $volume, $this->money($priceVariant->price ?? 0)])->filter()->implode(' · '),
            'volumes' => $this->volumeOptions($variants, $locale),
            'unit' => '/ ' . $volume,
            'badge' => $product->is_new ? 'New' : ($product->is_hit ? 'Bestseller' : ($product->is_promo ? 'Sale' : null)),
            'url' => route('product.show', ['product' => $product->slug]),
        ];
    }

    private function volumeOptions(Collection $variants, string $locale): Collection
    {
        $baseVariant = $variants->filter(fn (Product $variant): bool => (float) $variant->price > 0)->sortBy('price')->first()
            ?: $variants->first();
        $unitPrice = $baseVariant
            ? (float) $baseVariant->price / max((float) ($baseVariant->price_unit_quantity ?? 1), 1)
            : 0;

        $byVolume = $variants
            ->filter(fn (Product $variant): bool => (float) $variant->price > 0)
            ->mapWithKeys(function (Product $variant) use ($locale): array {
                $label = $this->firstCharacteristicValue($variant, ['obiem', 'obyem', 'volume', 'ml'], $locale);
                preg_match('/(\d+)/', (string) $label, $matches);

                return [((int) ($matches[1] ?? 0)) => $variant];
            })
            ->filter(fn (Product $variant, int $amount): bool => $amount > 0);

        return collect($this->configuredVolumeAmounts())
            ->map(function (int $amount) use ($byVolume, $unitPrice, $baseVariant): ?array {
                $variant = $byVolume->get($amount);
                $price = $variant ? (float) $variant->price : $unitPrice * $amount;

                if ($price <= 0) {
                    return null;
                }

                return [
                    'id' => (int) ($variant?->id ?? $baseVariant?->id ?? 0),
                    'label' => $amount . ' мл',
                    'price' => $price,
                    'price_label' => $this->money($price),
                ];
            })
            ->filter()
            ->values();
    }

    private function applySort(Builder $query, string $sort): void
    {
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('sort', 'asc')
                    ->orderBy('id', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('sort', 'asc')
                    ->orderBy('id', 'asc');
                break;

            case 'new':
                $query->orderByDesc('is_new')
                    ->orderBy('created_at', 'desc')
                    ->orderBy('sort', 'asc');
                break;

            case 'popular':
            default:
                $query->orderBy('sort', 'asc')
                    ->orderBy('id', 'asc');
                break;
        }
    }

    private function sortCardCollection($items, Request $request): Collection
    {
        if (! $items instanceof Collection) {
            $items = collect($items);
        }

        $sort = $request->query('sort', 'popular');

        $items = match ($sort) {
            'price_asc' => $items->sortBy(fn (array $product) => [(float) ($product['price'] ?? 0), (int) ($product['id'] ?? 0)]),
            'price_desc' => $items->sortBy(fn (array $product) => [-1 * (float) ($product['price'] ?? 0), (int) ($product['id'] ?? 0)]),
            default => $items,
        };

        return $items->values();
    }

    private function priceBounds(Collection $rootIds): array
    {
        if ($rootIds->isEmpty()) {
            return [180, 1600];
        }

        $row = Product::query()
            ->where(function (Builder $query) use ($rootIds): void {
                $query->whereIn('id', $rootIds)->orWhereIn('parent_id', $rootIds);
            })
            ->where('price', '>', 0)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        $min = (int) floor((float) ($row->min_price ?? 0));
        $max = (int) ceil((float) ($row->max_price ?? 0));

        return [$min > 0 ? $min : 180, $max > $min ? $max : 1600];
    }

    private function breadcrumbs(?ProductCategory $category, string $locale): array
    {
        $items = [['title' => 'Sevia', 'url' => route('home')], ['title' => 'Парфуми', 'url' => route('catalog.index')]];

        if ($category) {
            $items[] = ['title' => $this->label($category, 'title', $locale), 'url' => null];
        }

        return $items;
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

    private function fallbackProducts(): Collection
    {
        $rows = [
            ['Paco Rabanne', 'Lady Million', 'bestseller-1.png', 580, 'Bestseller'],
            ['Dior', 'Miss Dior · Blooming Bouquet', 'bestseller-2.png', 720, 'New'],
            ['Hermes', 'Terre d’Hermes', 'bestseller-3.png', 690, 'Cult'],
            ['Paco Rabanne', '1 Million', 'bestseller-4.png', 560, 'Bestseller'],
            ['Lanvin', 'Eclat d’Arpege', 'bestseller-5.png', 420, null],
        ];

        return collect(range(0, 23))->map(function (int $index) use ($rows): array {
            $row = $rows[$index % count($rows)];

            return [
                'id' => $index + 1,
                'sku' => '',
                'brand' => Str::upper($row[0]),
                'name' => $row[1],
                'meta' => $index % 2 === 0 ? 'Унісекс · Eau de Parfum' : 'Жіночі · Eau de Parfum',
                'notes' => 'Півонія · Троянда · Білий мускус',
                'image' => asset('vendor/frontend-sevia/images/' . $row[2]),
                'price' => $row[3],
                'price_label' => $this->money($row[3]),
                'old_price_label' => null,
                'cart_product_id' => 0,
                'cart_price' => $row[3],
                'volumes' => collect($this->configuredVolumeAmounts())->map(fn (int $amount): array => [
                    'id' => 0,
                    'label' => $amount . ' ml',
                    'price' => $row[3],
                    'price_label' => $this->money($row[3]),
                ]),
                'cart_label' => collect([Str::upper($row[0]), $row[1], '5 мл', $this->money($row[3])])->filter()->implode(' · '),
                'unit' => '/ 5 мл',
                'badge' => $row[4],
                'url' => '#',
            ];
        });
    }

    private function configuredVolumeAmounts(): array
    {
        $raw = (string) config('services.callcenter.order_menu_unit_options.ml', '3,5,10,15,20,30');

        $amounts = collect(explode(',', $raw))
            ->map(fn (string $value): int => (int) trim($value))
            ->filter(fn (int $value): bool => $value > 0)
            ->unique()
            ->values()
            ->all();

        return $amounts !== [] ? $amounts : [3, 5, 10, 15, 20, 30];
    }
}
