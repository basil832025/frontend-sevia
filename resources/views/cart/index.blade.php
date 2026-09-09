@extends('front.sevia::layouts.app')

@section('title', 'Кошик | Sevia')
@section('meta_description', 'Кошик Sevia')

@php
    $money = fn ($value): string => number_format((float) $value, 0, '.', ' ') . ' ₴';
    $countLabel = static function (int $count): string {
        $last = $count % 10;
        $lastTwo = $count % 100;

        if ($last === 1 && $lastTwo !== 11) {
            return $count . ' аромат';
        }

        if (in_array($last, [2, 3, 4], true) && ! in_array($lastTwo, [12, 13, 14], true)) {
            return $count . ' аромати';
        }

        return $count . ' ароматів';
    };
    $bottleFee = 0;
    $bottleCount = 0;
    $bottleBreakdown = [];
    $bottleTitleLabel = static function (string $title, int $count): string {
        $label = trim(\Illuminate\Support\Str::lower($title));
        $label = preg_replace('/^флакон\s+/iu', '', $label) ?: $label;

        if ($count !== 1) {
            $label = str_replace(
                ['стандартний', 'преміальний'],
                ['стандартні', 'преміальні'],
                $label
            );
        }

        return $count . ' ' . $label;
    };
    $bottleSummaryLabel = static function (array $breakdown, int $fallbackCount) use ($bottleTitleLabel): string {
        if ($breakdown === []) {
            return $fallbackCount . ' стандартні';
        }

        return collect($breakdown)
            ->map(fn (array $row): string => $bottleTitleLabel((string) $row['title'], (int) $row['count']))
            ->implode(', ');
    };
    $itemsSubtotal = collect($items)->sum(fn ($item) => (float) ($item['old_subtotal'] ?? $item['subtotal'] ?? 0));
    $discountTotal = max(0, $itemsSubtotal - (float) $total);
    $freeShippingFrom = max(0, (float) \App\Models\Setting::admin('cart.free_shipping_from', 0));
    $cartTotalWithBottles = (float) $total;
    $freeShippingLeft = $freeShippingFrom > 0 ? max(0, $freeShippingFrom - $cartTotalWithBottles) : 0;
    $freeShippingProgress = $freeShippingFrom > 0 ? min(100, max(0, ($cartTotalWithBottles / $freeShippingFrom) * 100)) : 0;
    $bottleOptions = collect($bottles ?? []);
    $defaultBottle = $bottleOptions->first();
    $cartItems = collect($items);
    $discoverySetGroups = $cartItems
        ->filter(fn (array $item): bool => (bool) data_get($item, 'meta.discovery_53') && filled(data_get($item, 'meta.discovery_set_id')))
        ->groupBy(fn (array $item): string => (string) data_get($item, 'meta.discovery_set_id'))
        ->map(function ($setItems, string $setId) {
            $setItems = collect($setItems)->values();
            $qty = max(1, (int) $setItems->min('qty'));
            $originalTotal = $setItems->sum(fn (array $item): float => (float) data_get($item, 'meta.discovery_original_price', $item['price'] ?? 0) * (int) ($item['qty'] ?? 1));
            $discountedTotal = $setItems->sum(fn (array $item): float => (float) ($item['subtotal'] ?? 0));

            return [
                'id' => $setId,
                'items' => $setItems,
                'qty' => $qty,
                'original_total' => $originalTotal,
                'discounted_total' => $discountedTotal,
                'discount' => max(0, $originalTotal - $discountedTotal),
            ];
        })
        ->values();
    $regularItems = $cartItems
        ->reject(fn (array $item): bool => (bool) data_get($item, 'meta.discovery_53') && filled(data_get($item, 'meta.discovery_set_id')))
        ->values();
    $regularQty = $regularItems->sum(fn (array $item): int => (int) ($item['qty'] ?? 0));
    $discoveryOriginalTotal = $discoverySetGroups->sum('original_total');
    $discoveryDiscountedTotal = $discoverySetGroups->sum('discounted_total');
    $discoveryDiscountTotal = $discoverySetGroups->sum('discount');
    $itemsSubtotal = $regularItems->sum(fn ($item) => (float) ($item['old_subtotal'] ?? $item['subtotal'] ?? 0)) + $discoveryOriginalTotal;
    $discountTotal = max(0, $itemsSubtotal - (float) $total);
@endphp

@section('content')
    @if ($qty <= 0)
            <section class="mx-auto flex min-h-[602px] w-full max-w-[620px] flex-col items-center bg-[#FDFBF8] px-5 pb-[150px] pt-[152px] text-center max-sm:min-h-[520px] max-sm:pb-20 max-sm:pt-20">
            <div class="flex size-[66px] items-center justify-center rounded-full border border-[#E8DAD0] bg-[#FBF4F0]">
                <svg class="size-6" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.5 7.5H19.5L18 21H6L4.5 7.5Z" stroke="#A98088" stroke-width="2.1" stroke-linejoin="round"/>
                    <path d="M9 7.5C9 4.7 10.2 2.4 12 2.4C13.8 2.4 15 4.7 15 7.5" stroke="#A98088" stroke-width="2.1" stroke-linecap="round"/>
                </svg>
            </div>

            <h1 class="m-0 pt-8 font-cormorant text-[46px] font-semibold leading-[47px] tracking-[-0.69px] text-[#5B2730] max-sm:text-[38px] max-sm:leading-10">Кошик порожній</h1>
            <p class="m-0 mt-4 max-w-[356px] text-[14px] leading-[22px] text-[#7A4751]">Розпив 5 мл - найпростіший спосіб спробувати аромат, не купуючи повний флакон.</p>

            <div class="mt-[26px] flex w-full max-w-[437px] justify-center gap-[18px] max-sm:flex-col">
                <a class="flex min-h-[52px] flex-1 items-center justify-center gap-3 bg-[#5B2730] px-10 text-[11.5px] font-medium uppercase leading-[17px] tracking-[1.84px] text-[#FFF8F4]" href="{{ route('catalog.index') }}">
                    До каталогу
                    <svg class="size-[15px]" width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.8 7.5H12.2M8.4 3.3L12.2 7.5L8.4 11.7" stroke="#FFF8F4" stroke-width="1.3125" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <a class="flex min-h-[52px] flex-1 items-center justify-center border border-[#5B2730] px-10 text-[11.5px] font-medium uppercase leading-[17px] tracking-[1.84px] text-[#5B2730]" href="{{ route('home') }}#discovery">
                    Discovery 5x3 мл
                </a>
            </div>
        </section>
    @else
    <section class="mx-auto flex w-full max-w-[1440px] items-start gap-[212px] bg-[#FDFBF8] px-16 pb-[110px] pt-14 max-xl:gap-16 max-lg:flex-col max-lg:px-6 max-lg:pb-16 max-sm:px-5 max-sm:pb-10 max-sm:pt-[26px]">
        <main class="w-full max-w-[700px] flex-1 max-sm:max-w-none">
            <div class="flex min-h-[104px] items-end justify-between border-b border-[#E8DAD0] pb-6 max-sm:min-h-[99px] max-sm:flex-col max-sm:items-start max-sm:justify-between max-sm:gap-1 max-sm:pb-[18px]">
                <div class="grid gap-2.5">
                    <p class="m-0 text-[10.5px] font-medium uppercase leading-4 tracking-[1.89px] text-[#A98088]">Крок 1 із 3</p>
                    <h1 class="m-0 font-cormorant text-[52px] font-semibold leading-[53px] tracking-[-0.78px] text-[#5B2730] max-sm:text-[33px] max-sm:leading-[34px] max-sm:tracking-[-0.495px]">Кошик</h1>
                </div>
                <p class="m-0 text-[13px] leading-5 text-[#A98088] max-sm:w-full">{{ $countLabel((int) $qty) }}</p>
            </div>

            <div>
                @foreach ($discoverySetGroups as $set)
                    <article class="mb-[22px] border border-[#E8DAD0] bg-white">
                        <header class="relative z-20 flex min-h-[105px] items-center gap-[22px] px-[22px] py-[17px] max-sm:min-h-0 max-sm:flex-wrap max-sm:gap-4 max-sm:px-4 max-sm:py-4">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2.5">
                                    <p class="m-0 text-[10px] uppercase leading-[15px] tracking-[1.5px] text-[#7A4751]">Discovery 5×3</p>
                                    <span class="inline-flex h-[19px] items-center bg-[#5B2730] px-[7px] text-[9.5px] uppercase leading-[14px] tracking-[0.95px] text-[#FFF8F4]">15%</span>
                                </div>
                                <h2 class="m-0 mt-1 font-cormorant text-[21px] font-medium leading-6 text-[#5B2730]">Мій сет</h2>
                                <p class="m-0 mt-1 text-[12px] leading-[18px] text-[#7A4751]">Пʼять ароматів по 3 мл · атомайзери в комплекті</p>
                            </div>

                            <form class="flex h-[30px] items-center border border-[#E8DAD0]" method="POST" action="{{ route('cart.discovery-set.quantity') }}">
                                @csrf
                                <input type="hidden" name="discovery_set_id" value="{{ $set['id'] }}">
                                <button class="grid size-7 place-items-center text-[14px] leading-none text-[#7A4751]" type="submit" name="delta" value="-1" aria-label="Менше">-</button>
                                <span class="grid h-7 min-w-[26px] place-items-center px-2 text-[13px] leading-5 text-[#5B2730]">{{ $set['qty'] }}</span>
                                <button class="grid size-7 place-items-center text-[14px] leading-none text-[#7A4751]" type="submit" name="delta" value="1" aria-label="Більше">+</button>
                            </form>

                            <div class="min-w-24 text-right">
                                <p class="m-0 text-[16px] font-medium leading-6 text-[#B03B45]">{{ $money($set['discounted_total']) }}</p>
                                <p class="m-0 text-[12.5px] leading-[19px] text-[#C9A9B0] line-through">{{ $money($set['original_total']) }}</p>
                            </div>

                            <form class="relative z-30" method="POST" action="{{ route('cart.discovery-set.remove') }}" data-cart-remove-form>
                                @csrf
                                <input type="hidden" name="discovery_set_id" value="{{ $set['id'] }}">
                                <button class="grid size-[30px] place-items-center text-[#A98088]" type="button" aria-label="Прибрати сет" data-cart-remove-open>
                                    <svg class="size-[13px]" width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.25 3.25L9.75 9.75M9.75 3.25L3.25 9.75" stroke="#A98088" stroke-width="1.1375" stroke-linecap="round"/>
                                    </svg>
                                </button>
                                <div class="absolute right-0 top-9 z-50 hidden w-[218px] border border-[#E8DAD0] bg-white p-3.5 text-left shadow-[0_12px_30px_rgba(91,39,48,0.14)]" hidden data-cart-remove-confirm>
                                    <p class="m-0 font-cormorant text-[20px] font-medium leading-6 text-[#5B2730]">Видалити сет?</p>
                                    <p class="m-0 mt-1 text-[12px] leading-[18px] text-[#7A4751]">Усі 5 ароматів буде прибрано з кошика.</p>
                                    <div class="mt-3 flex items-center justify-between gap-2">
                                        <button class="h-9 flex-1 border border-[#E8DAD0] px-3 text-[10.5px] font-medium uppercase leading-4 tracking-[1.2px] text-[#7A4751]" type="button" data-cart-remove-cancel>Скасувати</button>
                                        <button class="h-9 flex-1 bg-[#5B2730] px-3 text-[10.5px] font-medium uppercase leading-4 tracking-[1.2px] text-[#FFF8F4]" type="submit">Видалити</button>
                                    </div>
                                </div>
                            </form>
                        </header>

                        <div class="relative z-0 grid grid-cols-5 border-y border-[#E8DAD0] bg-[#FBF4F0] max-sm:grid-cols-1">
                            @foreach ($set['items'] as $setItem)
                                @php
                                    $setMeta = is_array($setItem['meta'] ?? null) ? $setItem['meta'] : [];
                                    $setLabelParts = collect(preg_split('/\s*·\s*/u', (string) ($setMeta['cart_label'] ?? '')))->filter()->values();
                                    $setBrand = (string) ($setMeta['brand'] ?? ($setLabelParts->count() >= 3 ? $setLabelParts->get(0) : ''));
                                    $setName = (string) ($setMeta['name'] ?? ($setLabelParts->count() >= 3 ? $setLabelParts->get(1) : ($setItem['name'] ?? 'Товар')));
                                    $setVolume = (string) ($setMeta['volume'] ?? ($setLabelParts->count() >= 3 ? $setLabelParts->get(2) : ($setItem['variant'] ?? '3 мл')));
                                    $setOriginalPrice = (float) data_get($setMeta, 'discovery_original_price', $setItem['price'] ?? 0);
                                @endphp
                                <div class="relative flex min-h-[181px] flex-col items-center justify-between px-2.5 pb-4 pt-[15px] text-center max-sm:min-h-[61px] max-sm:flex-row max-sm:gap-3 max-sm:border-t max-sm:border-[#F0E6DE] max-sm:px-5 max-sm:py-[9px]">
                                    <span class="absolute left-2.5 top-[9px] text-[10px] leading-[15px] text-[#8A5D66] max-sm:static max-sm:w-3">{{ $loop->iteration }}</span>
                                    <div class="flex h-[62px] w-full items-center justify-center max-sm:h-[42px] max-sm:w-10">
                                        @if (! empty($setItem['image']))
                                            <img class="max-h-[60px] max-w-[42px] object-contain max-sm:max-h-10 max-sm:max-w-[28px]" src="{{ $setItem['image'] }}" alt="{{ trim($setBrand . ' ' . $setName) }}">
                                        @endif
                                    </div>
                                    <div class="w-full min-w-0 max-sm:flex max-sm:flex-1 max-sm:items-center max-sm:justify-between max-sm:gap-3 max-sm:text-left">
                                        <div class="min-w-0">
                                            <p class="m-0 mt-[9px] truncate text-[10px] uppercase leading-[15px] tracking-[1.4px] text-[#7A4751] max-sm:hidden">{{ $setBrand }}</p>
                                            <h3 class="m-0 mt-[3px] font-cormorant text-[15.5px] font-medium leading-[18px] text-[#5B2730] max-sm:mt-0">{{ $setName }}</h3>
                                        </div>
                                        <p class="m-0 mt-2 text-[11px] leading-4 text-[#7A4751] max-sm:mt-0 max-sm:w-[77px] max-sm:text-[14px] max-sm:leading-[21px]">{{ $setVolume }} · {{ $money($setOriginalPrice) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <footer class="flex min-h-[49px] items-center justify-between gap-5 px-[22px] py-[13px] max-sm:flex-col max-sm:items-start max-sm:px-4">
                            <p class="m-0 flex items-center gap-2 text-[12px] leading-[17px] text-[#457A5C]">
                                <span class="grid size-[13px] place-items-center rounded-full border border-[#457A5C] text-[9px]">✓</span>
                                Сет дешевший на {{ $money($set['discount']) }}, ніж ті самі аромати окремо
                            </p>
                            <a class="text-[13px] leading-5 text-[#7A4751]" href="{{ route('discovery-53', ['edit_set' => $set['id']]) }}">Змінити склад</a>
                        </footer>
                    </article>
                @endforeach

                @foreach ($regularItems as $item)
                        @php
                            $meta = is_array($item['meta'] ?? null) ? $item['meta'] : [];
                            $labelParts = collect(preg_split('/\s*·\s*/u', (string) ($meta['cart_label'] ?? '')))->filter()->values();
                            $brand = (string) ($meta['brand'] ?? ($labelParts->count() >= 3 ? $labelParts->get(0) : ''));
                            $name = (string) ($meta['name'] ?? ($labelParts->count() >= 3 ? $labelParts->get(1) : ($item['name'] ?? 'Товар')));
                            $volume = (string) ($meta['volume'] ?? ($labelParts->count() >= 3 ? $labelParts->get(2) : ($item['variant'] ?? '')));
                            $notes = (string) ($meta['notes'] ?? '');
                            $unitPrice = (float) ($item['price'] ?? 0);
                            $itemQty = (int) ($item['qty'] ?? 1);
                            $lineTotal = (float) ($item['subtotal'] ?? 0);
                            $oldLineTotal = (float) ($item['old_subtotal'] ?? 0);
                            $hasDiscount = $oldLineTotal > $lineTotal;
                            $productId = (int) ($item['product_id'] ?? 0);
                            $selectedBottleId = (int) data_get($meta, 'bottle_id', data_get($defaultBottle, 'id', 0));
                            $selectedBottle = $bottleOptions->firstWhere('id', $selectedBottleId) ?? $defaultBottle;
                            $itemBottleFee = (float) data_get($selectedBottle, 'price', 0);
                            $itemBottleTotal = $itemBottleFee * max(1, $itemQty);
                            $lineDisplayTotal = $lineTotal + $itemBottleTotal;
                            $bottleFee += $itemBottleTotal;
                            if ($selectedBottle) {
                                $bottleQty = max(1, $itemQty);
                                $bottleCount += $bottleQty;
                                $bottleTitle = (string) data_get($selectedBottle, 'title', 'Стандартний');
                                $bottleKey = (string) data_get($selectedBottle, 'id', $bottleTitle);
                                $bottleBreakdown[$bottleKey] = [
                                    'title' => $bottleTitle,
                                    'count' => (int) data_get($bottleBreakdown, $bottleKey . '.count', 0) + $bottleQty,
                                ];
                            }
                            $cartTotalWithBottles = (float) $total + $bottleFee;
                            $freeShippingLeft = $freeShippingFrom > 0 ? max(0, $freeShippingFrom - $cartTotalWithBottles) : 0;
                            $freeShippingProgress = $freeShippingFrom > 0 ? min(100, max(0, ($cartTotalWithBottles / $freeShippingFrom) * 100)) : 0;
                        @endphp

                        <article class="relative min-h-[165px] border-b border-[#E8DAD0] py-[18px] pl-[104px] pr-[50px] max-sm:min-h-[198.8px] max-sm:py-5 max-sm:pl-[78px] max-sm:pr-[56px]">
                            <div class="absolute left-0 top-[30px] flex h-[104px] w-[82px] items-center justify-center bg-[#FDFBF8] p-[9px] max-sm:top-5 max-sm:h-[78px] max-sm:w-[62px] max-sm:p-[7px]">
                                @if (! empty($item['image']))
                                    <img class="max-h-[86px] max-w-16 object-contain max-sm:max-h-16 max-sm:max-w-12" src="{{ $item['image'] }}" alt="{{ trim($brand . ' ' . $name) }}">
                                @endif
                            </div>

                            <div class="max-w-[322px] max-sm:max-w-none">
                                @if ($brand !== '')
                                    <p class="m-0 text-[10px] uppercase leading-[15px] tracking-[1.5px] text-[#A98088] max-sm:text-[9.5px] max-sm:leading-[14px] max-sm:tracking-[1.425px]">{{ $brand }}</p>
                                @endif
                                <h2 class="m-0 pt-0.5 font-cormorant text-[21px] font-medium leading-6 text-[#5B2730] max-sm:pt-0 max-sm:text-[17px] max-sm:leading-5">{{ $name }}</h2>
                                <p class="m-0 pt-1 text-[12px] leading-[18px] text-[#7A4751] max-sm:pt-0 max-sm:text-[11px] max-sm:leading-4">{{ $volume !== '' ? 'Розпив ' . $volume : 'Розпив' }}</p>
                                @if ($notes !== '')
                                    <p class="m-0 text-[12px] leading-[18px] text-[#A98088]">{{ $notes }}</p>
                                @endif

                                @if ($selectedBottle)
                                    <details class="group relative mt-2 w-full max-w-[300px] max-sm:mt-1 max-sm:max-w-none">
                                        <summary class="flex h-[32px] cursor-pointer list-none items-center justify-between border border-[#E8DAD0] bg-white px-2.5 marker:hidden max-sm:h-[38px] max-sm:px-3">
                                            <p class="m-0 flex min-w-0 items-baseline gap-[7px] text-[12.5px] leading-[19px] text-[#5B2730] max-sm:text-[13px] max-sm:leading-5">
                                                <em class="italic">Флакон</em>
                                                <span class="truncate">{{ $selectedBottle['title'] }}</span>
                                                <span class="shrink-0 text-[11.5px] text-[#A98088]">+ {{ $money($itemBottleFee) }}</span>
                                            </p>
                                            <svg class="size-[13px] shrink-0 transition group-open:rotate-180" width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3.25 5.25L6.5 8.5L9.75 5.25" stroke="#7A4751" stroke-width="1.1375" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </summary>

                                        <div class="absolute left-0 right-0 top-[45px] z-20 border border-[#E8DAD0] bg-white shadow-[0_8px_20px_rgba(91,39,48,0.07)]">
                                            @foreach ($bottleOptions as $bottle)
                                                @php
                                                    $isSelectedBottle = (int) $bottle['id'] === (int) $selectedBottle['id'];
                                                @endphp

                                                <form class="relative border-t first:border-t-0 border-[#F0E6DE]" method="POST" action="{{ route('cart.quantity') }}">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $productId }}">
                                                    <input type="hidden" name="qty" value="{{ $itemQty }}">
                                                    <input type="hidden" name="price" value="{{ $unitPrice }}">
                                                    <input type="hidden" name="meta[cart_label]" value="{{ (string) ($meta['cart_label'] ?? '') }}">
                                                    <input type="hidden" name="meta[brand]" value="{{ $brand }}">
                                                    <input type="hidden" name="meta[name]" value="{{ $name }}">
                                                    <input type="hidden" name="meta[volume]" value="{{ $volume }}">
                                                    <input type="hidden" name="meta[notes]" value="{{ $notes }}">
                                                    <input type="hidden" name="meta[bottle_id]" value="{{ $bottle['id'] }}">
                                                    <input type="hidden" name="meta[bottle_title]" value="{{ $bottle['title'] }}">
                                                    <input type="hidden" name="meta[bottle_description]" value="{{ $bottle['description'] }}">
                                                    <input type="hidden" name="meta[bottle_price]" value="{{ $bottle['price'] }}">
                                                    <input type="hidden" name="meta[bottle_image]" value="{{ $bottle['image'] }}">

                                                    <button class="flex h-[61px] w-full items-center justify-between gap-3 px-3.5 py-2.5 text-left" type="submit">
                                                        @if ($isSelectedBottle)
                                                            <span class="absolute bottom-0 left-0 top-0 w-0.5 bg-[#5B2730]"></span>
                                                        @endif

                                                        <span class="flex w-5 shrink-0 justify-center">
                                                            @if (! empty($bottle['image']))
                                                                <img class="h-[34px] w-5 object-contain" src="{{ $bottle['image'] }}" alt="{{ $bottle['title'] }}">
                                                            @endif
                                                        </span>
                                                        <span class="min-w-0 flex-1">
                                                            <span class="block truncate text-[13px] leading-5 {{ $isSelectedBottle ? 'text-[#5B2730]' : 'text-[#7A4751]' }}">{{ $bottle['title'] }}</span>
                                                            <span class="block truncate text-[11.5px] leading-[17px] text-[#A98088]">{{ $bottle['description'] }}</span>
                                                        </span>
                                                        <span class="shrink-0 text-[12.5px] leading-[19px] text-[#A98088]">+ {{ $money($bottle['price']) }}</span>
                                                    </button>
                                                </form>
                                            @endforeach
                                        </div>
                                    </details>
                                @endif
                            </div>

                            <form class="absolute right-[168px] top-[67px] flex h-[30px] items-center border border-[#E8DAD0] max-sm:left-[78px] max-sm:right-auto max-sm:top-[134px] max-sm:mt-0 max-sm:w-[84px]" method="POST" action="{{ route('cart.quantity') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $productId }}">
                                <input type="hidden" name="price" value="{{ $unitPrice }}">
                                <input type="hidden" name="meta[volume]" value="{{ $volume }}">
                                <button class="grid size-7 place-items-center text-[14px] leading-none text-[#7A4751]" type="submit" name="delta" value="-1" aria-label="Менше">-</button>
                                <span class="grid h-7 min-w-[26px] place-items-center px-2 text-[13px] leading-5 text-[#5B2730]">{{ $itemQty }}</span>
                                <button class="grid size-7 place-items-center text-[14px] leading-none text-[#7A4751]" type="submit" name="delta" value="1" aria-label="Більше">+</button>
                            </form>

                            <div class="absolute right-[50px] top-[66px] min-w-24 text-right max-sm:right-0 max-sm:top-[137px] max-sm:min-w-0">
                                <p class="m-0 text-[16px] font-medium leading-6 {{ $hasDiscount ? 'text-[#B03B45]' : 'text-[#5B2730]' }} max-sm:text-[15px] max-sm:leading-[22px]">{{ $money($lineDisplayTotal) }}</p>
                                @if ($hasDiscount)
                                    <p class="m-0 text-[12.5px] leading-[19px] text-[#C9A9B0] line-through">{{ $money($oldLineTotal) }}</p>
                                @endif
                            </div>

                            <form class="absolute right-0 top-[67px] z-10 max-sm:right-0 max-sm:top-5" method="POST" action="{{ route('cart.remove') }}" data-cart-remove-form>
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $productId }}">
                                <input type="hidden" name="meta[volume]" value="{{ $volume }}">
                                <button class="grid size-[30px] place-items-center text-[#A98088]" type="button" aria-label="Прибрати" data-cart-remove-open>
                                    <svg class="size-[13px]" width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.25 3.25L9.75 9.75M9.75 3.25L3.25 9.75" stroke="#A98088" stroke-width="1.1375" stroke-linecap="round"/>
                                    </svg>
                                </button>
                                <div class="absolute right-0 top-9 hidden w-[218px] border border-[#E8DAD0] bg-white p-3.5 text-left shadow-[0_12px_30px_rgba(91,39,48,0.14)] max-sm:right-0 max-sm:top-8" hidden data-cart-remove-confirm>
                                    <p class="m-0 font-cormorant text-[20px] font-medium leading-6 text-[#5B2730]">Видалити товар?</p>
                                    <p class="m-0 mt-1 text-[12px] leading-[18px] text-[#7A4751]">Позицію буде прибрано з кошика.</p>
                                    <div class="mt-3 flex items-center justify-between gap-2">
                                        <button class="h-9 flex-1 border border-[#E8DAD0] px-3 text-[10.5px] font-medium uppercase leading-4 tracking-[1.2px] text-[#7A4751]" type="button" data-cart-remove-cancel>Скасувати</button>
                                        <button class="h-9 flex-1 bg-[#5B2730] px-3 text-[10.5px] font-medium uppercase leading-4 tracking-[1.2px] text-[#FFF8F4]" type="submit">Видалити</button>
                                    </div>
                                </div>
                            </form>
                        </article>
                @endforeach
            </div>

            <a class="mt-[38px] inline-flex text-[13px] leading-5 text-[#7A4751] max-sm:mt-0 max-sm:py-3.5" href="{{ route('catalog.index') }}">← Продовжити добирати аромати</a>
        </main>

        <aside class="w-full max-w-[400px] max-sm:flex max-sm:max-w-none max-sm:flex-col max-sm:items-start max-sm:gap-5">
            <div class="border border-[#E8DAD0] bg-white max-sm:-mx-5 max-sm:w-[calc(100%+40px)] max-sm:border-x-0">
                <div class="flex h-[79px] items-baseline justify-between px-[26px] pb-5 pt-[22px] max-sm:h-[63px] max-sm:px-5 max-sm:py-4">
                    <p class="m-0 text-[10.5px] font-medium uppercase leading-4 tracking-[1.68px] text-[#A98088]">Підсумок</p>
                    <strong class="font-cormorant text-[24px] font-semibold leading-9 tracking-[-0.24px] text-[#5B2730] max-sm:text-xl max-sm:leading-[30px] max-sm:tracking-[-0.2px]">{{ $money($cartTotalWithBottles) }}</strong>
                </div>

                <div class="{{ $freeShippingFrom > 0 ? 'block' : 'hidden' }} bg-[#FBF4F0] px-[26px] py-[18px] max-sm:px-5 max-sm:py-3.5 max-sm:pb-4">
                    <p class="m-0 text-[12.5px] leading-[18px] text-[#7A4751]">
                        @if ($freeShippingLeft > 0)
                            Ще {{ $money($freeShippingLeft) }} і доставимо безкоштовно
                        @else
                            Доставка буде безкоштовною
                        @endif
                    </p>
                    <div class="mt-3 h-0.5 w-full bg-[#EDDCD5]">
                        <div class="h-0.5 bg-[#5B2730]" style="width: {{ $freeShippingProgress }}%"></div>
                    </div>
                </div>

                <form class="border-t border-[#E8DAD0] px-[26px] py-8 max-sm:px-5 max-sm:pb-[15px] max-sm:pt-7" action="#" method="GET">
                    <label class="flex h-12 border border-[#E8DAD0]">
                        <input class="min-w-0 flex-1 px-4 text-[13px] leading-4 text-[#5B2730] outline-none placeholder:text-[#C9A9B0]" type="text" name="promo" placeholder="Промокод або сертифікат">
                        <button class="w-[120px] text-[10.5px] uppercase leading-4 tracking-[1.47px] text-[#7A4751] max-sm:w-24" type="submit">Застосувати</button>
                    </label>
                </form>

                <div class="border-t border-[#E8DAD0] px-[26px] pb-6 pt-4 max-sm:px-5 max-sm:pb-5">
                    <dl class="m-0 grid gap-0 text-[13.5px] leading-5">
                        <div class="flex justify-between py-[5.5px]">
                            <dt class="text-[#7A4751]">Аромати · {{ $regularQty }} розпиви</dt>
                            <dd class="m-0 text-[#5B2730]">{{ $money($regularItems->sum(fn ($item) => (float) ($item['old_subtotal'] ?? $item['subtotal'] ?? 0))) }}</dd>
                        </div>
                        <div class="flex justify-between py-[5.5px]">
                            <dt class="text-[#7A4751]">Знижка на аромати</dt>
                            <dd class="m-0 text-[#4D8566]">{{ ($discountTotal - $discoveryDiscountTotal) > 0 ? '- ' . $money($discountTotal - $discoveryDiscountTotal) : $money(0) }}</dd>
                        </div>
                        @if ($discoverySetGroups->isNotEmpty())
                            <div class="flex justify-between py-[5.5px]">
                                <dt class="text-[#7A4751]">Discovery 5×3 · сет</dt>
                                <dd class="m-0 text-[#5B2730]">{{ $money($discoveryOriginalTotal) }}</dd>
                            </div>
                            <div class="flex justify-between py-[5.5px]">
                                <dt class="text-[#7A4751]">Знижка на сет · 15%</dt>
                                <dd class="m-0 text-[#4D8566]">- {{ $money($discoveryDiscountTotal) }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between py-[5.5px]">
                            <dt class="text-[#7A4751]">Флакони · {{ $bottleSummaryLabel($bottleBreakdown, $bottleCount) }}</dt>
                            <dd class="m-0 text-[#5B2730]">{{ $money($bottleFee) }}</dd>
                        </div>
                        <div class="flex justify-between pb-[19px] pt-[5.5px]">
                            <dt class="text-[#7A4751]">Доставка</dt>
                            <dd class="m-0 text-[#5B2730]">на кроці 3</dd>
                        </div>
                    </dl>

                    <div class="flex items-baseline justify-between border-t border-[#E8DAD0] pt-4 max-sm:pt-4">
                        <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.68px] text-[#7A4751]">Разом</span>
                        <strong class="font-cormorant text-[30px] font-semibold leading-[45px] tracking-[-0.3px] text-[#5B2730] max-sm:text-[25px] max-sm:leading-[38px] max-sm:tracking-[-0.25px]">{{ $money($cartTotalWithBottles) }}</strong>
                    </div>

                    <a class="mt-5 flex h-[52px] items-center justify-center gap-3 bg-[#5B2730] text-[11.5px] font-medium uppercase leading-[17px] tracking-[1.84px] text-[#FFF8F4]" href="{{ route('checkout') }}">
                        Оформити замовлення
                        <svg class="size-[15px]" width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.8 7.5H12.2M8.4 3.3L12.2 7.5L8.4 11.7" stroke="#FFF8F4" stroke-width="1.3125" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="flex min-w-0 w-full max-w-full items-center justify-between px-0.5 pt-[13px] max-sm:flex-col max-sm:items-start max-sm:gap-2.5 max-sm:px-0 max-sm:pt-[21px]">
                <span class="text-[10px] uppercase leading-[15px] tracking-[1.4px] text-[#A98088]">Оплата захищена</span>
                <img class="h-5 w-[162px] shrink-0 object-contain" src="{{ asset('vendor/frontend-sevia/images/oplatu.png') }}" alt="LiqPay, Visa, Mastercard">
            </div>

            <ul class="m-0 mt-4 grid min-w-0 w-full max-w-full list-none gap-[11px] p-0 text-[12px] leading-[17px] text-[#7A4751] max-sm:mt-0">
                <li class="flex items-center gap-2.5">
                    <span class="grid size-[13px] place-items-center rounded-full border border-[#A98088] text-[9px]">✓</span>
                    100% оригінал · розпив із власного флакона бренду
                </li>
                <li class="flex items-center gap-2.5">
                    <span class="grid size-[13px] place-items-center rounded-full border border-[#A98088] text-[9px]">✓</span>
                    Доставляємо Новою Поштою по всій Україні
                </li>
            </ul>
        </aside>
    </section>
    @endif
@endsection
