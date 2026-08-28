<?php

namespace Basil832025\FrontendSevia\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Shop\Client;
use App\Models\Shop\ClientAddress;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use App\Services\NovaPostApiClient;
use App\Support\GuestFavoritesStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SeviaAccountController extends Controller
{
    public function overview(Request $request)
    {
        /** @var Client|null $client */
        $client = Auth::guard('web')->user();

        if (! $client) {
            return redirect()->route('auth.phone');
        }

        $orders = $client
            ? Order::query()
                ->with(['items.product'])
                ->where('clients_id', $client->id)
                ->where('status', '!=', OrderStatus::Cart->value)
                ->latest('id')
                ->limit(3)
                ->get()
            : collect();

        $favoriteProducts = $client
            ? $client->favorites()
                ->with([
                    'children.productCharacteristicValues.characteristic',
                    'children.productCharacteristicValues.characteristicValue',
                    'mainCategory',
                    'categories',
                    'ingredients',
                    'productCharacteristicValues.characteristic',
                    'productCharacteristicValues.characteristicValue',
                    'unit',
                ])
                ->limit(3)
                ->get()
            : collect();

        if ($favoriteProducts->isEmpty()) {
            $favoriteProducts = Product::query()
                ->active()
                ->mainProduct()
                ->where(function ($query): void {
                    $query
                        ->whereHas('mainCategory', fn ($categoryQuery) => $categoryQuery->where('is_visible', true))
                        ->orWhereHas('categories', fn ($categoryQuery) => $categoryQuery->where('bs_product_categories.is_visible', true));
                })
                ->with([
                    'children.productCharacteristicValues.characteristic',
                    'children.productCharacteristicValues.characteristicValue',
                    'mainCategory',
                    'categories',
                    'ingredients',
                    'productCharacteristicValues.characteristic',
                    'productCharacteristicValues.characteristicValue',
                    'unit',
                ])
                ->orderByDesc('id')
                ->limit(3)
                ->get();
        }

        $wishlist = $favoriteProducts
            ->filter(fn (Product $product): bool => trim((string) ($product->slug ?? '')) !== '')
            ->map(fn (Product $product): array => $this->accountProductCard($product))
            ->filter(fn (array $product): bool => ! empty($product['name']))
            ->values();

        $ordersCount = $client
            ? Order::query()
                ->where('clients_id', $client->id)
                ->where('status', '!=', OrderStatus::Cart->value)
                ->count()
            : 3;

        $favoritesCount = $client ? $client->favorites()->count() : max(12, $wishlist->count());
        $bonusBalance = $this->bonusBalance($client);

        return view('front.sevia::account.overview', [
            'client' => $client,
            'firstName' => $this->firstName($client),
            'bonusBalance' => $bonusBalance,
            'ordersCount' => $ordersCount,
            'favoritesCount' => $favoritesCount,
            'discoveryCount' => 5,
            'trackingOrder' => $this->trackingOrder($orders),
            'recentOrders' => $this->recentOrders($orders),
            'wishlistProducts' => $wishlist,
            'profileRows' => $this->profileRows($client),
        ]);
    }

    public function orders(Request $request)
    {
        /** @var Client|null $client */
        $client = Auth::guard('web')->user();

        if (! $client) {
            return redirect()->route('auth.phone');
        }

        $filter = (string) $request->query('status', 'all');
        $orders = Order::query()
            ->with(['items.product'])
            ->where('clients_id', $client->id)
            ->where('status', '!=', OrderStatus::Cart->value)
            ->when($filter === 'transit', fn ($query) => $query->where('status', OrderStatus::Shipped->value))
            ->when($filter === 'delivered', fn ($query) => $query->where('status', OrderStatus::Delivered->value))
            ->latest('id')
            ->get();

        $orderRows = $this->recentOrders($orders, false);

        return view('front.sevia::account.orders', [
            'client' => $client,
            'orders' => $orderRows,
            'filter' => $filter,
        ]);
    }

    public function order(Order $order)
    {
        /** @var Client|null $client */
        $client = Auth::guard('web')->user();

        if (! $client) {
            return redirect()->route('auth.phone');
        }

        abort_unless(
            (int) $order->clients_id === (int) $client->id
                && $order->status !== OrderStatus::Cart,
            404
        );

        $order->load(['items.product']);
        $novaTracking = $this->liveNovaTracking($order);
        $tracking = $this->trackingOrder(collect([$order]), $novaTracking);
        $tracking['steps'] = $this->orderTrackingSteps($order, $novaTracking);
        $items = $order->items->map(function ($item): array {
            $snapshot = (array) ($item->product_snapshot ?? []);
            $product = $item->product;
            $name = trim((string) data_get($snapshot, 'name', $product?->name ?? 'Аромат'));
            $brand = trim((string) data_get($snapshot, 'brand', 'Sevia'));
            $volume = trim((string) data_get($snapshot, 'volume', data_get($snapshot, 'unit', '')));
            $qty = (int) ($item->qty ?? 1);
            $unitPrice = (float) ($item->unit_price_effective ?? $item->unit_price ?? $item->subtotal ?? $item->total ?? 0);

            return [
                'meta' => trim($brand . ' · Унісекс' . ($volume !== '' ? ' · ' . $volume : '') . ' · ' . $qty),
                'name' => $name,
                'price' => $this->money($unitPrice * max($qty, 1)),
                'image' => $product?->main_image_url ?: $product?->image_url,
                'product_id' => (int) ($item->product_id ?? 0),
                'product_slug' => (string) ($product?->slug ?? ''),
            ];
        })->values();

        return view('front.sevia::account.order', [
            'client' => $client,
            'order' => $order,
            'tracking' => $tracking,
            'items' => $items,
            'orderTotal' => $this->money((float) ($order->grand_total ?: $order->total_price_sale ?: $order->total_price)),
            'orderedAt' => $order->created_at?->translatedFormat('j F Y · H:i') ?? '',
        ]);
    }

    public function profile(Request $request)
    {
        /** @var Client|null $client */
        $client = Auth::guard('web')->user();

        $ordersCount = $client
            ? Order::query()
                ->where('clients_id', $client->id)
                ->where('status', '!=', OrderStatus::Cart->value)
                ->count()
            : 3;

        $favoritesCount = $client ? $client->favorites()->count() : 12;

        $latestOrder = $client
            ? Order::query()
                ->with('clientAddress')
                ->where('clients_id', $client->id)
                ->where('status', '!=', OrderStatus::Cart->value)
                ->latest('id')
                ->first()
            : null;

        return view('front.sevia::account.profile', [
            'client' => $client,
            'ordersCount' => $ordersCount,
            'favoritesCount' => $favoritesCount,
            'profileRows' => $this->profileRows($client),
            'deliveryRows' => $this->deliveryRows($client, $latestOrder),
        ]);
    }

    public function favorites(Request $request)
    {
        /** @var Client|null $client */
        $client = Auth::guard('web')->user();

        $favoriteIds = $client
            ? $client->favorites()->pluck('bs_products.id')->map(fn ($id): int => (int) $id)->all()
            : GuestFavoritesStore::idsFromRequest();

        $products = collect();

        if ($favoriteIds !== []) {
            $products = Product::query()
                ->whereIn('id', $favoriteIds)
                ->with([
                'children.productCharacteristicValues.characteristic',
                'children.productCharacteristicValues.characteristicValue',
                'mainCategory',
                'categories',
                'ingredients',
                'productCharacteristicValues.characteristic',
                'productCharacteristicValues.characteristicValue',
                'unit',
                ])
                ->get()
                ->sortBy(fn (Product $product): int => array_search((int) $product->id, $favoriteIds, true))
                ->values()
                ->map(function (Product $product): array {
                    return array_merge($this->accountProductCard($product), [
                        'available' => (bool) $product->in_stock,
                    ]);
                });
        }

        return view('front.sevia::account.favorites', [
            'client' => $client,
            'favoritesCount' => $products->count(),
            'favoriteProducts' => $products,
        ]);
    }

    public function toggleFavorite(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:bs_products,id'],
        ]);
        $productId = (int) $data['product_id'];
        $client = Auth::guard('web')->user();

        if ($client) {
            $isFavorite = $client->favorites()->whereKey($productId)->exists();

            if ($isFavorite) {
                $client->favorites()->detach($productId);
            } else {
                $client->favorites()->syncWithoutDetaching([$productId]);
            }

            return response()->json([
                'favorite' => ! $isFavorite,
                'count' => $client->favorites()->count(),
            ]);
        }

        $ids = GuestFavoritesStore::idsFromRequest();
        $isFavorite = in_array($productId, $ids, true);
        $ids = $isFavorite
            ? array_values(array_diff($ids, [$productId]))
            : [...$ids, $productId];

        GuestFavoritesStore::queueIds($ids);

        return response()->json([
            'favorite' => ! $isFavorite,
            'count' => count($ids),
        ]);
    }

    public function editProfile(Request $request)
    {
        /** @var Client|null $client */
        $client = Auth::guard('web')->user();

        $ordersCount = $client
            ? Order::query()
                ->where('clients_id', $client->id)
                ->where('status', '!=', OrderStatus::Cart->value)
                ->count()
            : 3;

        return view('front.sevia::account.edit-profile', [
            'client' => $client,
            'ordersCount' => $ordersCount,
            'favoritesCount' => $client ? $client->favorites()->count() : 12,
            'form' => $this->profileForm($client),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var Client|null $client */
        $client = Auth::guard('web')->user();

        if (! $client) {
            return redirect()->route('auth.phone');
        }

        $birthdayLocked = $this->birthdayLocked($client);

        $rules = [
            'name' => ['nullable', 'string', 'max:120'],
            'surname' => ['nullable', 'string', 'max:120'],
            'full_name_mobile' => ['nullable', 'string', 'max:240'],
            'email' => ['nullable', 'email', 'max:190', Rule::unique('bs_clients', 'email')->ignore($client->id)],
        ];

        if (! $birthdayLocked) {
            $rules['birthday'] = ['nullable', 'date_format:d.m.Y', 'before:today', 'after:01.01.1900'];
        }

        $data = $request->validate($rules, [
            'email.unique' => 'Ця email-адреса вже використовується іншим акаунтом. Спробуй іншу або увійди.',
        ]);

        if ($request->isMethod('patch')
            && preg_match('/Mobile|Android|iPhone|iPad/i', (string) $request->userAgent())
            && $request->filled('full_name_mobile')) {
            $parts = preg_split('/\s+/u', trim((string) $data['full_name_mobile']), 2);
            $data['name'] = $parts[0] ?? null;
            $data['surname'] = $parts[1] ?? null;
        }

        $client->update([
            'name' => array_key_exists('name', $data) ? trim((string) $data['name']) : $client->name,
            'surname' => array_key_exists('surname', $data) ? trim((string) $data['surname']) : $client->surname,
            'email' => array_key_exists('email', $data) ? ($data['email'] ?: null) : $client->email,
        ]);

        if (! $birthdayLocked && ! empty($data['birthday'])) {
            $client->birthday = \Carbon\Carbon::createFromFormat('d.m.Y', $data['birthday'])->startOfDay();
            $client->save();
        }

        return redirect()->route('account.profile')->with('account_saved', true);
    }

    public function editAddress(Request $request)
    {
        /** @var Client|null $client */
        $client = Auth::guard('web')->user();

        if (! $client) {
            return redirect()->route('auth.phone');
        }

        $latestOrder = Order::query()
            ->where('clients_id', $client->id)
            ->where('status', '!=', OrderStatus::Cart->value)
            ->latest('id')
            ->first(['id', 'nova_city', 'nova_city_ref', 'nova_warehouse', 'nova_warehouse_ref']);

        return view('front.sevia::account.edit-address', [
            'client' => $client,
            'address' => $client->addresses()->latest('id')->first(),
            'latestOrder' => $latestOrder,
        ]);
    }

    public function updateAddress(Request $request): RedirectResponse
    {
        /** @var Client|null $client */
        $client = Auth::guard('web')->user();

        if (! $client) {
            return redirect()->route('auth.phone');
        }

        $data = $request->validate([
            'city' => ['nullable', 'string', 'max:255'],
            'city_ref' => ['nullable', 'string', 'max:80'],
            'formatted_address' => ['nullable', 'string', 'max:255'],
            'warehouse_ref' => ['nullable', 'string', 'max:80'],
            'street' => ['nullable', 'string', 'max:255'],
            'house' => ['nullable', 'string', 'max:50'],
            'apartment' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $address = $client->addresses()->latest('id')->first();

        $addressData = [
            'city' => $data['city'] ?? null,
            'nova_city_ref' => $data['city_ref'] ?? null,
            'formatted_address' => $data['formatted_address'] ?? null,
            'nova_warehouse_ref' => $data['warehouse_ref'] ?? null,
        ];

        // Client addresses require street and house even for Nova Poshta pickup points.
        // Keep the branch in the formatted fields and use neutral required values below.
        if (! $address || ! $address->street) {
            $addressData['street'] = 'Нова Пошта';
        }

        if (! $address || ! $address->house) {
            $addressData['house'] = $data['formatted_address'] ?? 'Відділення';
        }

        if ($address) {
            $address->update(array_merge($data, $addressData));
        } else {
            $client->addresses()->create(array_merge($data, $addressData));
        }

        return redirect()->route('account.profile')->with('account_saved', true);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function firstName(?Client $client): string
    {
        $name = trim((string) ($client?->name ?? ''));

        if ($name === '') {
            return 'Олено';
        }

        return trim((string) str($name)->before(' '));
    }

    private function bonusBalance(?Client $client): int
    {
        if (! $client) {
            return 580;
        }

        $account = method_exists($client, 'loyaltyAccount') ? $client->loyaltyAccount : null;

        return (int) ($account?->balance ?? 0);
    }

    private function trackingOrder(Collection $orders, ?array $novaTracking = null): array
    {
        /** @var Order|null $order */
        $order = $orders->first();

        if (! $order) {
            return [
                'status_line' => 'У дорозі · доставка завтра',
                'code' => 'SV-2026-00214',
                'items' => 'Imagination · Black Afgano · 1 340 ₴',
                'ttn' => '20 4500 0123 4567',
                'status' => 'Прийнято',
                'steps' => [
                    ['label' => 'Прийнято', 'done' => true],
                    ['label' => 'Зібрано', 'done' => true],
                    ['label' => 'Передано НП', 'done' => true],
                    ['label' => 'На відділенні', 'done' => false],
                ],
            ];
        }

        return [
            'status_line' => $this->statusLabel($order) . ($order->dat ? ' · доставка ' . $order->dat->format('d.m') : ''),
            'code' => $this->orderCode($order),
            'items' => $this->orderItemsLine($order),
            'ttn' => trim((string) ($order->nova_ttn ?? '')),
            'status' => $this->statusLabel($order),
            'steps' => [
                ['label' => 'Прийнято', 'done' => true],
                ['label' => 'Зібрано', 'done' => in_array($order->status?->value, ['assembled', 'shipped', 'delivered'], true)],
                ['label' => 'Передано НП', 'done' => in_array($order->status?->value, ['shipped', 'delivered'], true)],
                ['label' => 'На відділенні', 'done' => $order->status === OrderStatus::Delivered],
            ],
        ];
    }

    private function orderTrackingSteps(Order $order, array $novaTracking): array
    {
        $statusTimes = (array) ($order->status_times ?? []);
        $acceptedAt = $this->formatTrackingDate(data_get($statusTimes, OrderStatus::New->value) ?: $order->created_at);
        $assembledAt = $this->formatTrackingDate(
            data_get($statusTimes, OrderStatus::Assembled->value)
                ?: data_get($statusTimes, OrderStatus::Prepared->value)
        );
        $shippedAt = $this->formatTrackingDate(data_get($statusTimes, OrderStatus::Shipped->value));
        $scheduledDate = $this->formatTrackingDay($novaTracking['scheduled_delivery_date'] ?? null);
        $actualDate = $this->formatTrackingDay($novaTracking['actual_delivery_date'] ?? null);
        $novaStatusDate = $this->formatTrackingDate($novaTracking['status_date'] ?? null);
        $novaStatus = (string) ($novaTracking['status'] ?? '');
        $orderStatus = $order->status?->value;
        $novaPassedToCarrier = in_array($novaStatus, ['in_transit', 'arrived', 'received'], true)
            && in_array($orderStatus, ['assembled', 'shipped', 'delivered'], true);
        $shippedDone = $shippedAt !== '' || in_array($orderStatus, ['shipped', 'delivered'], true) || $novaPassedToCarrier;
        $assembledDone = $assembledAt !== '' || $shippedDone || in_array($orderStatus, ['assembled', 'shipped', 'delivered'], true);
        $isDelivered = $order->status === OrderStatus::Delivered || ($novaStatus === 'received' && $novaPassedToCarrier);
        $isArrived = $isDelivered || ($novaStatus === 'arrived' && $novaPassedToCarrier);

        return [
            ['label' => 'Прийнято', 'done' => true, 'date' => $acceptedAt],
            ['label' => 'Зібрано', 'done' => $assembledDone, 'date' => $assembledAt],
            ['label' => 'Передано до Нової Пошти', 'done' => $shippedDone, 'date' => $shippedAt],
            ['label' => 'У дорозі', 'done' => $isArrived, 'date' => $isArrived ? $novaStatusDate : ($scheduledDate !== '' ? 'очікувано ' . $scheduledDate : '')],
            ['label' => 'На відділенні', 'done' => $isDelivered, 'date' => $actualDate !== '' ? $actualDate : ($isArrived ? $novaStatusDate : '')],
        ];
    }

    private function liveNovaTracking(Order $order): array
    {
        $ttn = trim((string) ($order->nova_ttn ?? ''));
        if ($ttn === '') {
            return [];
        }

        try {
            return app(NovaPostApiClient::class)->trackingStatus($ttn, (string) ($order->recipient_phone ?? ''));
        } catch (\Throwable) {
            return [];
        }
    }

    private function formatTrackingDate(mixed $value): string
    {
        if (blank($value)) {
            return '';
        }

        try {
            return \Illuminate\Support\Carbon::parse($value)->translatedFormat('j F · H:i');
        } catch (\Throwable) {
            return '';
        }
    }

    private function formatTrackingDay(mixed $value): string
    {
        if (blank($value)) {
            return '';
        }

        try {
            return \Illuminate\Support\Carbon::parse($value)->translatedFormat('j F');
        } catch (\Throwable) {
            return '';
        }
    }

    private function recentOrders(Collection $orders, bool $withFallback = true): Collection
    {
        if ($orders->isEmpty() && $withFallback) {
            return collect([
                ['code' => 'SV-2026-00214', 'date' => '9 травня 2026', 'items' => 'Imagination · Black Afgano', 'status' => 'У дорозі', 'total' => '1 340 ₴'],
                ['code' => 'SV-2026-00198', 'date' => '24 квітня 2026', 'items' => 'Tilia · Mojave Ghost · Discovery 5×3', 'status' => 'Доставлено', 'total' => '3 120 ₴'],
                ['code' => 'SV-2026-00171', 'date' => '12 квітня 2026', 'items' => 'Symphony', 'status' => 'Доставлено', 'total' => '820 ₴'],
            ]);
        }

        return $orders->map(fn (Order $order): array => [
            'id' => (int) $order->id,
            'code' => $this->orderCode($order),
            'date' => $order->created_at?->translatedFormat('j F Y') ?? '',
            'items' => $this->orderItemsLine($order, false),
            'status' => $this->statusLabel($order),
            'total' => $this->money((float) ($order->grand_total ?: $order->total_price_sale ?: $order->total_price)),
        ]);
    }

    private function profileRows(?Client $client): array
    {
        return [
            ['label' => 'Імʼя', 'value' => $client?->full_name ?: 'Олена Ковальчук'],
            ['label' => 'Email', 'value' => $client?->email ?: 'olena@email.com'],
            ['label' => 'Телефон', 'value' => $client?->phone_pretty ?: '+380 63 000 00 00'],
            ['label' => 'Дата народження', 'value' => $this->birthdayLocked($client) ? $client->birthday->translatedFormat('j F') : ''],
            ['label' => 'Улюблений тип', 'value' => 'Деревні · Шкіряні'],
            ['label' => 'Улюблений бренд', 'value' => 'Marc-Antoine Barrois'],
        ];
    }

    private function profileForm(?Client $client): array
    {
        return [
            'name' => old('name', $client?->name ?: 'Олена'),
            'surname' => old('surname', $client?->surname ?: 'Ковальчук'),
            'email' => old('email', $client?->email ?: 'olena@email.com'),
            'phone' => old('phone', $client?->phone_pretty ?: '+380 63 000 00 00'),
            'birthday' => old('birthday', $this->birthdayLocked($client) ? $client?->birthday?->format('d.m.Y') : ''),
        ];
    }

    private function birthdayLocked(?Client $client): bool
    {
        return (bool) ($client?->birthday && $client->birthday->lte(now()->subYears(16)->endOfDay()));
    }

    private function deliveryRows(?Client $client, ?Order $latestOrder = null): array
    {
        /** @var ClientAddress|null $address */
        $address = $client?->addresses()->latest('id')->first();

        $city = trim((string) ($address?->city ?: $latestOrder?->nova_city ?: 'Київ'));
        $warehouse = trim((string) (
            $latestOrder?->nova_warehouse
            ?: $address?->formatted_address
            ?: collect([$address?->street, $address?->house])->filter()->implode(', ')
        ));

        if ($warehouse === '') {
            $warehouse = 'Нова Пошта №24';
        }

        return [
            ['label' => 'Місто', 'value' => $city],
            ['label' => 'Відділення', 'value' => $warehouse],
            ['label' => 'Отримувач', 'value' => $client?->full_name ?: 'Олена Ковальчук'],
        ];
    }

    private function accountProductCard(Product $product): array
    {
        $variants = collect([$product])->merge($product->children ?? collect());
        $priceVariant = $variants
            ->filter(fn (Product $variant): bool => (float) ($variant->price ?? 0) > 0)
            ->sortBy('price')
            ->first() ?: $product;

        $title = $this->productTitle($product);
        $parts = preg_split('/\s+/u', trim($title), 2) ?: [];
        $brand = trim((string) ($parts[0] ?? 'Sevia'));
        $name = trim((string) ($parts[1] ?? $title));

        if ($name === '') {
            $name = $title;
        }

        $volume = $this->productTitle($priceVariant) !== $title
            ? trim(str_replace($title, '', $this->productTitle($priceVariant)))
            : '';

        $basePrice = (float) ($priceVariant->price ?? $product->price ?? 0);
        $unitPrice = $basePrice / max((float) ($priceVariant->price_unit_quantity ?? 1), 1);
        $variantsByVolume = $variants
            ->filter(fn (Product $variant): bool => (float) ($variant->price ?? 0) > 0)
            ->mapWithKeys(function (Product $variant) use ($title): array {
                preg_match('/(\d+)/', $this->accountVolumeLabel($variant, $title), $matches);

                return [(int) ($matches[1] ?? 0) => $variant];
            })
            ->filter(fn (Product $variant, int $amount): bool => $amount > 0);

        $volumes = collect($this->accountVolumeAmounts())
            ->map(function (int $amount) use ($variantsByVolume, $unitPrice, $priceVariant): array {
                $variant = $variantsByVolume->get($amount);
                $price = $variant ? (float) $variant->price : $unitPrice * $amount;

                return [
                    'id' => (int) ($variant?->id ?? $priceVariant->id),
                    'label' => $amount . ' мл',
                    'price' => $price,
                    'price_label' => $this->money($price),
                ];
            })
            ->filter(fn (array $volume): bool => $volume['price'] > 0)
            ->values();

        return [
            'id' => (int) $product->id,
            'brand' => $brand,
            'name' => $name,
            'meta' => 'Нішеві',
            'image' => $product->main_image_url ?: $product->image_url,
            'price_label' => $this->money((float) ($priceVariant->price ?? $product->price ?? 0)),
            'price' => (float) ($priceVariant->price ?? $product->price ?? 0),
            'cart_product_id' => $priceVariant->id,
            'ingredients' => $product->ingredients
                ->map(fn ($ingredient): string => is_array($ingredient->name ?? null)
                    ? (string) collect($ingredient->name)->get(app()->getLocale(), collect($ingredient->name)->first())
                    : (string) ($ingredient->name ?? ''))
                ->filter()
                ->implode('  '),
            'unit' => $volume !== '' ? $volume : '5 мл',
            'url' => route('product.show', ['product' => $product->slug]),
            'volumes' => collect([
                [
                    'label' => $volume !== '' ? $volume : '5 мл',
                    'price_label' => $this->money((float) ($priceVariant->price ?? $product->price ?? 0)),
                ],
            ]),
            'volumes' => $volumes->isNotEmpty() ? $volumes : collect([
                [
                    'id' => (int) $priceVariant->id,
                    'label' => $volume !== '' ? $volume : '5 ml',
                    'price' => (float) ($priceVariant->price ?? $product->price ?? 0),
                    'price_label' => $this->money((float) ($priceVariant->price ?? $product->price ?? 0)),
                ],
            ]),
        ];
    }

    private function accountVolumeLabel(Product $product, string $baseTitle): string
    {
        foreach ($product->productCharacteristicValues ?? collect() as $row) {
            $slug = (string) ($row->characteristic?->slug ?? '');

            if (! collect(['obiem', 'obyem', 'volume', 'ml'])->contains(fn (string $needle): bool => str_contains($slug, $needle))) {
                continue;
            }

            $value = $row->characteristicValue;
            if ($value && method_exists($value, 'getTranslation')) {
                $translated = $value->getTranslation('value', app()->getLocale());
                if ($translated !== null && $translated !== '') {
                    return trim((string) $translated);
                }
            }

            if ($value) {
                $raw = is_array($value->value ?? null)
                    ? $value->value
                    : json_decode((string) ($value->value ?? ''), true);

                if (is_array($raw)) {
                    $translated = $raw[app()->getLocale()] ?? $raw['uk'] ?? $raw['en'] ?? $raw['ru'] ?? reset($raw);
                    if ($translated !== null && $translated !== '') {
                        return trim((string) $translated);
                    }
                }
            }

            if ($row->value_text !== null && $row->value_text !== '') {
                return trim((string) $row->value_text);
            }
        }

        preg_match('/(\d+(?:[.,]\d+)?)\s*(?:ml|мл)/iu', $this->productTitle($product), $matches);

        return isset($matches[1]) ? $matches[1] . ' ml' : '5 ml';
    }

    private function accountVolumeAmounts(): array
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

    private function productTitle(Product $product): string
    {
        $value = $product->title;

        if (is_array($value)) {
            return trim((string) ($value[app()->getLocale()] ?? $value['uk'] ?? $value['ru'] ?? $value['en'] ?? reset($value)));
        }

        return trim((string) $value);
    }

    private function orderCode(Order $order): string
    {
        return 'SV-' . str_pad((string) $order->id, 5, '0', STR_PAD_LEFT);
    }

    private function orderItemsLine(Order $order, bool $withTotal = true): string
    {
        $items = $order->items
            ->take(2)
            ->map(fn ($item): string => trim((string) data_get($item->product_snapshot, 'name', $item->product?->name ?? 'Аромат')))
            ->filter()
            ->implode(' · ');

        if ($items === '') {
            $items = 'Замовлення Sevia';
        }

        if (! $withTotal) {
            return $items;
        }

        return $items . ' · ' . $this->money((float) ($order->grand_total ?: $order->total_price_sale ?: $order->total_price));
    }

    private function statusLabel(Order $order): string
    {
        return match ($order->status) {
            OrderStatus::New => 'Прийнято',
            OrderStatus::Processing, OrderStatus::Filling, OrderStatus::Molding, OrderStatus::Baking => 'Збирається',
            OrderStatus::Prepared, OrderStatus::Assembled => 'Зібрано',
            OrderStatus::Shipped => 'У дорозі',
            OrderStatus::Delivered => 'Доставлено',
            OrderStatus::Cancelled => 'Скасовано',
            default => 'Прийнято',
        };
    }

    private function money(float $amount): string
    {
        return number_format($amount, 0, '.', ' ') . ' ₴';
    }
}
