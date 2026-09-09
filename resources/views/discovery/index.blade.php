@extends('front.sevia::layouts.app')

@section('title', 'Discovery 5x3 мл | Sevia')
@section('meta_description', 'Збери сет Discovery 5x3 мл з будь-яких пʼяти ароматів Sevia зі знижкою 15%.')

@php
    $sortLabels = [
        'popular' => 'рекомендоване',
        'price_asc' => 'дешевші',
        'price_desc' => 'дорожчі',
        'new' => 'новинки',
    ];

    $mobileSortLabels = [
        'popular' => 'За популярністю',
        'price_asc' => 'Спочатку дешевші',
        'price_desc' => 'Спочатку дорожчі',
        'new' => 'Спершу новинки',
    ];

    $selectedChips = collect($filterGroups)->flatMap(function ($group) use ($selectedFilters) {
        $selected = $selectedFilters[$group['id']] ?? [];

        return collect($group['values'])
            ->whereIn('id', $selected)
            ->map(fn ($value) => [
                'title' => $value['title'],
                'url' => request()->fullUrlWithQuery([
                    'filters' => array_replace(
                        request()->query('filters', []),
                        [$group['id'] => collect($selected)->reject(fn ($id) => (int) $id === (int) $value['id'])->values()->all()]
                    ),
                    'page' => null,
                ]),
            ]);
    })->values();

    $pageUrl = fn (int $targetPage) => request()->fullUrlWithQuery(['page' => $targetPage]);
@endphp

@section('content')
    <main class="bg-[#FDFBF8] pb-[246px] text-[#5B2730] sm:pb-[118px]" data-discovery-set data-set-size="5" data-add-url="{{ route('cart.add') }}" data-cart-url="{{ route('cart.page') }}" data-remove-set-url="{{ route('cart.discovery-set.remove') }}" data-edit-set-id="{{ $editingDiscoverySetId ?? '' }}">
        <script type="application/json" data-discovery-initial-selection>@json($editingDiscoveryItems ?? [])</script>
        <section class="border-b border-[#E8DAD0] bg-[#FDFBF8] px-5 py-9 sm:px-[64px] sm:pb-10 sm:pt-12">
            <div class="mx-auto grid w-full max-w-[1312px] gap-8 lg:grid-cols-[minmax(0,752px)_480px] lg:gap-20">
                <div>
                    <p class="m-0 text-[10.5px] font-medium uppercase leading-4 tracking-[1.89px] text-[#A98088]">Discovery · парфумерний щоденник</p>
                    <h1 class="m-0 mt-3 font-cormorant text-[42px] font-semibold leading-[44px] text-[#5B2730] sm:text-[52px] sm:leading-[55px]">Збери свій сет 5×3 мл</h1>
                    <p class="m-0 mt-3 max-w-[453px] text-[13.5px] leading-[22px] text-[#7A4751]">
                        Збери будь-які 5 ароматів по 3 мл і отримай -15% на весь сет. Спробуй удома, обери улюблений і повертайся за повним флаконом. До кожного сету додаємо подарункову мініатюру.
                    </p>
                </div>

                <ol class="m-0 grid list-none p-0 text-[13px] leading-5 text-[#7A4751]">
                    @foreach ([
                        'Обери будь-які 5 ароматів зі списку',
                        'Знижка -15% застосовується до всього сету автоматично',
                        'Повернися за повним флаконом і отримай ще -10%',
                    ] as $step)
                        <li class="grid grid-cols-[56px_minmax(0,1fr)] gap-3.5 border-t border-[#E8DAD0] py-[13px] last:border-b">
                            <span class="text-[#8A5D66]">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span>{{ $step }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>

        <section class="hidden h-[57px] items-center justify-between border-y border-[#E8DAD0] bg-[#FDFBF8] px-5 py-3 max-sm:flex">
            <button class="inline-flex h-[33px] items-center gap-[7px] border border-[#5B2730] px-3.5 text-[11.5px] font-medium uppercase leading-[14px] tracking-[1px] text-[#5B2730]" type="button" data-filter-open>
                <svg class="h-2 w-[13px]" width="13" height="8" viewBox="0 0 13 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M0.5625 0.5625H9M0.5625 3.84375H11.8125M0.5625 7.125H6.1875" stroke="#5B2730" stroke-width="1.125" stroke-linecap="round"/>
                </svg>
                <span>Фільтри</span>
                @if ($selectedChips->isNotEmpty() || request()->hasAny(['price_min', 'price_max']))
                    <span class="inline-flex size-4 items-center justify-center rounded-full bg-[#5B2730] pb-px text-[10.5px] leading-[13px] tracking-normal text-[#FDFBF8]">{{ $selectedChips->count() + (request()->hasAny(['price_min', 'price_max']) ? 1 : 0) }}</span>
                @endif
            </button>

            <button class="flex items-center gap-1.5 text-[11.5px] font-medium leading-[14px] tracking-[0.2px] text-[#7A4751]" type="button" data-sort-open>
                <span>{{ $mobileSortLabels[$sort] ?? $mobileSortLabels['popular'] }}</span>
                <svg class="h-[9px] w-2 {{ $sort === 'price_asc' ? 'rotate-180' : '' }}" width="8" height="9" viewBox="0 0 8 9" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M4.28684 0V7.00568L6.98003 4.30398L7.6448 4.96023L3.81809 8.77841L0 4.96023L0.647639 4.30398L3.34934 7.00568V0H4.28684Z" fill="#7A4751"/>
                </svg>
            </button>
        </section>

        <div class="fixed inset-0 z-[76] hidden bg-[rgba(42,31,25,0.28)] max-lg:data-[open=true]:flex" hidden data-filter-drawer>
            <button class="absolute inset-0 h-full w-full" type="button" aria-label="Закрити фільтри" data-filter-close></button>

            <aside class="relative flex h-full w-full max-w-[393px] flex-col bg-white" aria-label="Фільтри Discovery">
                <div class="flex h-[61px] w-full items-center justify-between border-b border-[#E8DAD0] bg-white px-5 py-4">
                    <h2 class="m-0 font-cormorant text-[24px] font-medium leading-[29px] text-[#5B2730]">Фільтри</h2>
                    <button class="text-[16px] leading-[19px] text-[#7A4751]" type="button" aria-label="Закрити фільтри" data-filter-close>×</button>
                </div>

                <form class="flex min-h-0 flex-1 flex-col" method="GET" action="{{ url()->current() }}" data-filter-count-form data-filter-count-url="{{ route('discovery-53.count') }}">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    @if (! empty($editingDiscoverySetId))
                        <input type="hidden" name="edit_set" value="{{ $editingDiscoverySetId }}">
                    @endif

                    <div class="flex min-h-0 flex-1 flex-col gap-[26px] overflow-y-auto px-5 py-[22px]">
                        @foreach ($filterGroups as $group)
                            @php
                                $selectedGroupValues = $selectedFilters[$group['id']] ?? [];
                            @endphp

                            <fieldset class="w-full">
                                <legend class="mb-3 text-[10px] font-medium uppercase leading-3 tracking-[1.6px] text-[#A98088]">{{ $group['title'] }}</legend>

                                @if (($group['role'] ?? null) === 'gender')
                                    <div class="grid gap-0">
                                        @foreach ($group['values'] as $value)
                                            <label class="flex h-[34px] cursor-pointer items-center gap-[11px] py-2 text-[13.5px] leading-4 text-[#7A4751] has-[:checked]:text-[#5B2730]">
                                                <input class="peer sr-only" type="checkbox" name="filters[{{ $group['id'] }}][]" value="{{ $value['id'] }}" @checked(in_array($value['id'], $selectedGroupValues, true))>
                                                <span class="grid size-[18px] place-items-center border border-[#E8DAD0] after:hidden after:h-[7px] after:w-2.5 after:rotate-[-45deg] after:border-b-[1.5px] after:border-l-[1.5px] after:border-white peer-checked:border-[#5B2730] peer-checked:bg-[#5B2730] peer-checked:after:block"></span>
                                                <span>{{ $value['title'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif (($group['role'] ?? null) === 'brand')
                                    <div class="mb-2.5 flex h-[37px] w-full items-center gap-2 border border-[#E8DAD0] px-3">
                                        <img class="size-[15px]" src="{{ asset('vendor/frontend-sevia/images/search.svg') }}" alt="">
                                        <input class="min-w-0 flex-1 bg-transparent text-[12.5px] leading-[15px] text-[#5B2730] outline-none placeholder:text-[#C9A9B0]" type="search" placeholder="Пошук бренду..." data-brand-search>
                                    </div>

                                    <div class="grid gap-1" data-brand-list>
                                        @foreach (collect($group['values'])->take(8) as $value)
                                            <label class="flex h-8 cursor-pointer items-center gap-[11px] py-[7px] text-[13.5px] leading-4 text-[#7A4751] has-[:checked]:text-[#5B2730]">
                                                <input class="peer sr-only" type="checkbox" name="filters[{{ $group['id'] }}][]" value="{{ $value['id'] }}" @checked(in_array($value['id'], $selectedGroupValues, true))>
                                                <span class="grid size-[18px] place-items-center border border-[#E8DAD0] after:hidden after:h-[7px] after:w-2.5 after:rotate-[-45deg] after:border-b-[1.5px] after:border-l-[1.5px] after:border-white peer-checked:border-[#5B2730] peer-checked:bg-[#5B2730] peer-checked:after:block"></span>
                                                <span class="truncate">{{ $value['title'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($group['values'] as $value)
                                            <label class="cursor-pointer">
                                                <input class="peer sr-only" type="checkbox" name="filters[{{ $group['id'] }}][]" value="{{ $value['id'] }}" @checked(in_array($value['id'], $selectedGroupValues, true))>
                                                <span class="inline-flex h-8 items-center border border-[#E8DAD0] px-3.5 text-[11.5px] font-medium leading-[14px] tracking-[0.3px] text-[#7A4751] peer-checked:border-[#5B2730] peer-checked:bg-[#5B2730] peer-checked:text-white">{{ $value['title'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </fieldset>
                        @endforeach

                        <fieldset class="w-full" data-price-filter data-price-min="{{ $priceMin }}" data-price-max="{{ $priceMax }}">
                            <legend class="mb-3 text-[10px] font-medium uppercase leading-3 tracking-[1.6px] text-[#A98088]">Ціна, ₴</legend>
                            <div class="sevia-price-range relative h-5 w-full">
                                <span class="absolute left-2 right-2 top-[9px] h-0.5 rounded bg-[#E8DAD0]"></span>
                                <span class="absolute top-[9px] h-0.5 rounded bg-[#5B2730]" data-price-range-fill></span>
                                <input class="sevia-price-range__input" type="range" min="{{ $priceMin }}" max="{{ $priceMax }}" value="{{ $currentPriceMin }}" step="1" aria-label="Мінімальна ціна" data-price-min-range>
                                <input class="sevia-price-range__input" type="range" min="{{ $priceMin }}" max="{{ $priceMax }}" value="{{ $currentPriceMax }}" step="1" aria-label="Максимальна ціна" data-price-max-range>
                            </div>
                            <div class="mt-2 flex justify-between text-[11.5px] font-medium leading-[14px] text-[#7A4751]">
                                <span>{{ $currentPriceMin }} ₴</span>
                                <span>{{ $currentPriceMax }} ₴</span>
                            </div>
                            <div class="mt-3 flex items-center gap-3">
                                <label class="flex h-10 min-w-0 flex-1 items-center justify-between border border-[#E8DAD0] px-3.5">
                                    <span class="text-[13px] leading-4 text-[#C9A9B0]">Від</span>
                                    <input class="min-w-0 flex-1 bg-transparent text-right text-[13px] leading-4 text-[#5B2730] outline-none" type="number" min="{{ $priceMin }}" max="{{ $priceMax }}" name="price_min" value="{{ $currentPriceMin }}" data-price-min-input>
                                </label>
                                <label class="flex h-10 min-w-0 flex-1 items-center justify-between border border-[#E8DAD0] px-3.5">
                                    <span class="text-[13px] leading-4 text-[#C9A9B0]">До</span>
                                    <input class="min-w-0 flex-1 bg-transparent text-right text-[13px] leading-4 text-[#5B2730] outline-none" type="number" min="{{ $priceMin }}" max="{{ $priceMax }}" name="price_max" value="{{ $currentPriceMax }}" data-price-max-input>
                                </label>
                            </div>
                        </fieldset>
                    </div>

                    <div class="flex h-[79px] items-center gap-3.5 border-t border-[#E8DAD0] bg-white px-5 pb-5 pt-3.5">
                        <a class="inline-flex h-[43px] items-center px-1 text-[12px] font-medium uppercase leading-[15px] tracking-[1px] text-[#7A4751]" href="{{ url()->current() }}">Скинути</a>
                        <button class="inline-flex h-[45px] flex-1 items-center justify-center bg-[#5B2730] text-[12px] font-medium uppercase leading-[15px] tracking-[1.2px] text-white" type="submit">Показати <span class="ml-1" data-filter-count>{{ $productsTotal }}</span></button>
                    </div>
                </form>
            </aside>
        </div>

        <div class="fixed inset-0 z-[75] hidden items-center justify-center bg-[rgba(42,31,25,0.28)] px-5 max-sm:data-[open=true]:flex" hidden data-sort-sheet>
            <button class="absolute inset-0 h-full w-full" type="button" aria-label="Закрити сортування" data-sort-close></button>
            <section class="relative flex w-full max-w-[353px] flex-col items-start rounded-[18px] bg-white pb-7 shadow-[0_24px_80px_-40px_rgba(42,31,25,0.7)]" aria-label="Сортування">
                <div class="flex h-5 w-full justify-center px-0 pb-1.5 pt-2.5">
                    <span class="h-1 w-10 rounded-sm bg-[#C9A9B0]"></span>
                </div>
                <div class="flex h-[50px] w-full items-start px-5 pb-3.5 pt-2">
                    <h2 class="m-0 font-cormorant text-[23px] font-medium leading-7 text-[#5B2730]">Сортування</h2>
                </div>
                @foreach ($mobileSortLabels as $value => $label)
                    <a class="flex h-11 w-full items-center gap-3 border-t border-[#E8DAD0] px-5 py-[13px]" href="{{ request()->fullUrlWithQuery(['sort' => $value, 'page' => null]) }}" data-sort-option data-selected="{{ $sort === $value ? 'true' : 'false' }}">
                        <span class="relative size-[18px] rounded-full {{ $sort === $value ? 'border-2 border-[#5B2730]' : 'border border-[#E8DAD0]' }}" data-sort-radio>
                            <span class="{{ $sort === $value ? '' : 'hidden' }} absolute left-1/2 top-1/2 size-2 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#5B2730]" data-sort-dot></span>
                        </span>
                        <span class="text-[13.5px] leading-4 {{ $sort === $value ? 'font-medium text-[#5B2730]' : 'font-normal text-[#7A4751]' }}" data-sort-label>{{ $label }}</span>
                    </a>
                @endforeach
            </section>
        </div>

        <section class="bg-white px-5 pb-10 pt-[34px] sm:px-[64px]">
            <div class="mx-auto grid w-full max-w-[1312px] grid-cols-1 gap-10 lg:grid-cols-[247px_minmax(0,1fr)]">
                <aside class="hidden lg:block">
                    <form class="flex w-[247px] flex-col gap-[30px]" method="GET" action="{{ url()->current() }}">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        @if (! empty($editingDiscoverySetId))
                            <input type="hidden" name="edit_set" value="{{ $editingDiscoverySetId }}">
                        @endif

                        @foreach ($filterGroups as $group)
                            <fieldset class="w-full">
                                <legend class="mb-2.5 border-b border-[#E8DAD0] pb-2.5 text-[10.5px] font-medium uppercase leading-4 tracking-[1.89px] text-[#A98088]">{{ $group['title'] }}</legend>

                                @if (($group['role'] ?? null) === 'brand')
                                    <div class="mb-2.5 flex h-[41px] items-center gap-[9px] border border-[#E8DAD0] px-3">
                                        <img class="size-[14px]" src="{{ asset('vendor/frontend-sevia/images/search.svg') }}" alt="">
                                        <input class="min-w-0 flex-1 bg-transparent text-[12.5px] leading-[15px] text-[#5B2730] outline-none placeholder:text-[#8A5D66]" type="search" placeholder="Пошук бренду" data-brand-search>
                                    </div>
                                @endif

                                <div class="grid" @if (($group['role'] ?? null) === 'brand') data-brand-list @endif>
                                    @foreach (collect($group['values'])->take(($group['role'] ?? null) === 'brand' ? 999 : 10) as $value)
                                        <label class="@if (($group['role'] ?? null) === 'brand' && $loop->iteration > 5) hidden @else flex @endif min-h-[34px] cursor-pointer items-center justify-between gap-3 py-[7px] text-[13px] leading-5 text-[#7A4751]" @if (($group['role'] ?? null) === 'brand' && $loop->iteration > 5) data-brand-extra @endif>
                                            <span class="flex min-w-0 items-center gap-3">
                                                <input class="size-3 border border-[#5B2730] bg-white accent-[#5B2730]" type="checkbox" name="filters[{{ $group['id'] }}][]" value="{{ $value['id'] }}" @checked(in_array($value['id'], $selectedFilters[$group['id']] ?? [], true)) onchange="this.form.submit()">
                                                <span class="truncate">{{ $value['title'] }}</span>
                                            </span>
                                            <span class="text-[12px] leading-[18px] text-[#8A5D66]">{{ $value['count'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endforeach
                    </form>
                </aside>

                <div class="min-w-0">
                    <div class="mb-[26px] flex min-h-[58px] items-start justify-between gap-5 border-b border-[#E8DAD0]">
                        <div>
                            <h2 class="m-0 font-cormorant text-[27px] font-medium leading-10 text-[#5B2730]" data-discovery-title>Обери ароматів · лишилось 5</h2>
                            @if ($selectedChips->isNotEmpty())
                                <div class="mt-1 flex flex-wrap gap-2 pb-3">
                                    @foreach ($selectedChips as $chip)
                                        <a class="inline-flex h-7 items-center border border-[#E8DAD0] bg-[#F8EDE7] px-2.5 text-[12px] text-[#5B2730]" href="{{ $chip['url'] }}">{{ $chip['title'] }} <span class="ml-1 text-[#A98088]">×</span></a>
                                    @endforeach
                                    <a class="inline-flex h-7 items-center text-[11px] uppercase tracking-[1.54px] text-[#5B2730]" href="{{ url()->current() }}">Скинути все</a>
                                </div>
                            @endif
                        </div>

                        <form class="hidden items-center gap-2 pt-[15px] text-[12.5px] leading-[19px] text-[#7A4751] sm:flex" method="GET" action="{{ url()->current() }}">
                            @foreach (request()->except(['sort', 'page']) as $key => $value)
                                @if (is_array($value))
                                    @foreach ($value as $groupKey => $groupValues)
                                        @foreach ((array) $groupValues as $groupValue)
                                            <input type="hidden" name="{{ $key }}[{{ $groupKey }}][]" value="{{ $groupValue }}">
                                        @endforeach
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <label class="flex items-center gap-2">
                                <span>Сортування:</span>
                                <select class="bg-transparent text-[#7A4751] outline-none" name="sort" onchange="this.form.submit()">
                                    @foreach ($sortLabels as $value => $label)
                                        <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </form>
                    </div>

                    @if ($productsTotal === 0)
                        <section class="grid min-h-[360px] place-items-center border border-[#E8DAD0] bg-[#FDFBF8] px-6 text-center">
                            <div>
                                <h2 class="m-0 font-cormorant text-[36px] font-medium leading-10 text-[#5B2730]">Ароматів не знайдено</h2>
                                <p class="m-0 mt-2 text-[14px] leading-6 text-[#7A4751]">Спробуй змінити фільтри або повернутися до всіх ароматів Discovery.</p>
                                <a class="mt-5 inline-flex h-11 items-center border border-[#5B2730] px-6 text-[11.5px] font-medium uppercase tracking-[1.84px] text-[#5B2730]" href="{{ url()->current() }}">Скинути фільтри</a>
                            </div>
                        </section>
                    @else
                        <div class="grid grid-cols-2 gap-x-3 gap-y-5 sm:grid-cols-3 sm:gap-x-5 sm:gap-y-10 xl:grid-cols-4">
                            @foreach ($products as $product)
                                @php($volume = $product['discovery_volume'])
                                <article class="group relative flex min-h-[430px] flex-col border border-transparent bg-white data-[selected=true]:border-[#5B2730] data-[selected=true]:bg-[#F8EDE7] max-sm:min-h-[340px]" data-discovery-card data-product-id="{{ $volume['id'] }}" data-product-root-id="{{ $product['id'] }}" data-product-title="{{ $product['name'] }}" data-product-brand="{{ $product['brand'] }}" data-product-image="{{ $product['image'] }}" data-product-price="{{ $volume['price'] }}" data-product-price-label="{{ $volume['price_label'] }}" data-product-volume="{{ $volume['label'] }}" data-product-notes="{{ $product['notes'] }}" data-cart-label="{{ $product['brand'] }} {{ $product['name'] }} / {{ $volume['label'] }}">
                                    <a class="relative flex h-[262px] items-center justify-center bg-[#FDFBF8] p-6 group-data-[selected=true]:bg-[#F8EDE7] max-sm:h-[190px] max-sm:p-4" href="{{ $product['url'] }}">
                                        <span class="absolute left-2.5 top-2.5 hidden h-[25px] items-center gap-1.5 border border-[#5B2730] bg-white px-[9px] text-[9.5px] uppercase leading-[14px] tracking-[1.14px] text-[#5B2730] group-data-[selected=true]:inline-flex">
                                            <span aria-hidden="true">✓</span>
                                            <span>У сеті</span>
                                        </span>
                                        <button class="absolute right-2.5 top-2.5 z-10 grid size-[30px] place-items-center rounded-full border border-[#E8DAD0] bg-white text-[#8A5D66]" type="button" aria-label="Додати в обране" aria-pressed="{{ in_array((int) $product['id'], $favoriteIds ?? [], true) ? 'true' : 'false' }}" data-favorite-toggle data-favorite-url="{{ route('favorites.toggle') }}" data-product-id="{{ $product['id'] }}">{{ in_array((int) $product['id'], $favoriteIds ?? [], true) ? '♥' : '♡' }}</button>
                                        <img class="max-h-full max-w-full object-contain transition duration-300 group-hover:scale-[1.03]" src="{{ $product['image'] }}" alt="{{ $product['brand'] }} {{ $product['name'] }}">
                                    </a>

                                    <div class="flex flex-1 flex-col px-3.5 pb-3.5 pt-3">
                                        <p class="m-0 text-[9.5px] uppercase leading-[14px] tracking-[1.425px] text-[#8A5D66]">{{ $product['meta'] }} · {{ $volume['label'] }}</p>
                                        <p class="m-0 mt-2 text-[12px] font-medium leading-[18px] tracking-[0.24px] text-[#7A4751]">{{ $product['brand'] }}</p>
                                        <h3 class="m-0 min-h-[49px] font-cormorant text-[21px] font-medium leading-6 text-[#5B2730]">{{ $product['name'] }}</h3>
                                        <p class="m-0 min-h-[34px] text-[11.5px] leading-[17px] text-[#7A4751]">{{ $product['notes'] }}</p>
                                        <p class="m-0 mt-auto pt-3 text-[12.5px] leading-[19px] text-[#7A4751]">{{ $volume['label'] }} · {{ $volume['price_label'] }}</p>
                                        <button class="mt-3 h-11 border border-[#5B2730] bg-[#5B2730] px-4 text-[11px] font-medium uppercase leading-4 tracking-[1.54px] text-[#FFF8F4] group-data-[selected=true]:bg-transparent group-data-[selected=true]:text-[#5B2730]" type="button" data-discovery-toggle>
                                            <span data-discovery-toggle-label>+ Додати</span>
                                        </button>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif

                    @if ($productsTotal > 0 && $lastPage > 1)
                        <nav class="mt-[52px] flex items-center justify-between border-t border-[#E8DAD0] pt-5 text-[11px] uppercase leading-4 tracking-[1.54px] text-[#7A4751]" aria-label="Pagination">
                            <a class="{{ $page <= 1 ? 'pointer-events-none opacity-40' : '' }}" href="{{ $pageUrl(max(1, $page - 1)) }}">← Назад</a>
                            <div class="flex items-center gap-1">
                                @for ($i = 1; $i <= $lastPage; $i++)
                                    @if ($i <= 3 || $i === $lastPage || abs($i - $page) <= 1)
                                        <a class="grid size-[30px] place-items-center {{ $page === $i ? 'bg-[#5B2730] text-[#FFF8F4]' : 'text-[#7A4751]' }}" href="{{ $pageUrl($i) }}">{{ $i }}</a>
                                    @elseif ($i === 4)
                                        <span class="px-1 text-[#A98088]">…</span>
                                    @endif
                                @endfor
                            </div>
                            <a class="{{ $page >= $lastPage ? 'pointer-events-none opacity-40' : '' }}" href="{{ $pageUrl(min($lastPage, $page + 1)) }}">Далі →</a>
                        </nav>
                    @endif
                </div>
            </div>
        </section>

        <section class="group fixed bottom-0 left-0 right-0 z-50 border-t border-[#5B2730] bg-white shadow-[0_-20px_44px_-34px_rgba(42,31,25,0.45)] max-sm:group-data-[expanded=true]:pt-6" data-discovery-bar data-expanded="false">
            <div class="hidden border-b border-[#E8DAD0] bg-[#FBF4F0]" data-discovery-details>
                <div class="flex h-[52px] items-center justify-between px-5 sm:h-[58px] sm:px-[64px]">
                    <p class="m-0 text-[10.5px] font-medium uppercase leading-4 tracking-[1.89px] text-[#A98088]">Склад сету</p>
                    <button class="inline-flex items-center gap-[9px] text-[10.5px] uppercase leading-4 tracking-[1.47px] text-[#7A4751] sm:text-[11px] sm:tracking-[1.54px] sm:text-[#5B2730]" type="button" data-discovery-collapse>Згорнути <span aria-hidden="true">⌄</span></button>
                </div>
                <div class="grid max-h-[305px] grid-cols-1 overflow-y-auto border-t border-[#E8DAD0] px-0 sm:min-h-[214px] sm:grid-cols-5 sm:overflow-visible sm:px-[44px]" data-discovery-detail-list></div>
            </div>

            <div class="relative min-h-[226px] px-5 py-0 max-sm:group-data-[expanded=true]:min-h-[132.5px] sm:flex sm:min-h-[98px] sm:items-center sm:gap-4 sm:px-[64px] sm:py-4">
                <button class="absolute right-5 top-3.5 grid h-[68px] w-[22px] shrink-0 place-items-center text-[#7A4751] max-sm:group-data-[expanded=true]:hidden sm:static sm:h-[66px] sm:w-[34px]" type="button" aria-label="Показати склад сету" aria-expanded="false" data-discovery-expand>
                    <span class="text-[16px] sm:text-[15px]" aria-hidden="true">⌃</span>
                </button>
                <div class="absolute left-5 right-14 top-3.5 flex h-[68px] justify-center gap-1.5 max-sm:group-data-[expanded=true]:hidden sm:static sm:h-[66px] sm:shrink-0 sm:justify-start sm:gap-2.5" data-discovery-slots></div>
                <div class="absolute left-5 right-5 top-[94px] h-px bg-[#F0E6DE] max-sm:group-data-[expanded=true]:hidden sm:hidden" aria-hidden="true"></div>
                <div class="absolute left-5 right-5 top-[106px] flex h-[41px] items-center justify-between gap-3.5 max-sm:group-data-[expanded=true]:top-3.5 sm:static sm:ml-auto sm:min-w-0 sm:flex-1 sm:justify-end sm:gap-6">
                    <strong class="text-[10px] font-bold uppercase leading-[15px] tracking-[1.6px] text-[#B03B45] sm:text-[18px] sm:font-semibold sm:normal-case sm:leading-[18px] sm:tracking-[0.24px]">-15% знижка</strong>
                    <span class="ml-auto font-cormorant text-[24px] font-semibold leading-10 tracking-[-0.27px] text-[#B7876D] line-through sm:ml-0 sm:text-[34px] sm:leading-[41px] sm:text-[#B49381]" data-discovery-total>0 ₴</span>
                    <span class="font-cormorant text-[27px] font-semibold leading-10 tracking-[-0.27px] text-[#5B2730] sm:text-[34px] sm:leading-[41px]" data-discovery-discounted>0 ₴</span>
                </div>
                <button class="absolute left-5 right-5 top-[159px] h-[50px] border border-[#E8DAD0] px-[26px] text-[11.5px] font-medium uppercase leading-[17px] tracking-[1.84px] text-[#8A5D66] max-sm:group-data-[expanded=true]:top-[66.5px] disabled:cursor-not-allowed disabled:bg-white disabled:text-[#8A5D66] enabled:border-[#5B2730] enabled:bg-[#5B2730] enabled:text-[#FFF8F4] sm:static sm:h-14 sm:min-w-[181px] sm:px-8" type="button" disabled data-discovery-cart>Оберіть ще 5 ароматів</button>
            </div>
        </section>
    </main>
@endsection
