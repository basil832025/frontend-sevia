@extends('front.sevia::layouts.app')

@section('title', 'Парфуми | Sevia')
@section('meta_description', 'Каталог парфумів Sevia з фільтрами за статтю, ароматом, брендом і ціною.')

@php
    $sortLabels = [
        'popular' => 'За популярністю',
        'price_asc' => 'Ціна ↑',
        'price_desc' => 'Ціна ↓',
        'new' => 'Новинки',
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

    $volumeAmounts = collect($volumeAmounts ?? [3, 5, 10, 15, 20, 30])->map(fn ($value) => (int) $value)->filter()->values();
    $requestedVolume = request()->query('volume');
    $hasSelectedVolumeFilter = $requestedVolume !== null && $requestedVolume !== '' && (int) $requestedVolume > 0;
    $selectedVolume = $hasSelectedVolumeFilter ? (int) $requestedVolume : (int) ($volumeAmounts->first() ?? 3);

    if ($hasSelectedVolumeFilter) {
        $selectedChips->push([
            'title' => $selectedVolume . ' мл',
            'url' => request()->fullUrlWithQuery(['volume' => null, 'page' => null]),
        ]);
    }

    $pageUrl = fn (int $targetPage) => request()->fullUrlWithQuery(['page' => $targetPage]);
@endphp

@section('content')
    <nav class="flex h-[50px] items-center gap-2.5 px-[68px] pb-2 pt-[22px] text-[13px] leading-5 max-lg:px-6 max-sm:hidden sm:max-lg:h-9 sm:max-lg:px-[49px] sm:max-lg:pb-1.5 sm:max-lg:pt-4 sm:max-lg:text-[9.4px] sm:max-lg:leading-[14px]" aria-label="Breadcrumb">
        @foreach ($breadcrumbs as $item)
            @if (! $loop->first)
                <span class="text-[#E8DAD0]">/</span>
            @endif

            @if ($item['url'])
                <a class="text-[#7A4751] hover:text-[#5B2730]" href="{{ $item['url'] }}">{{ $item['title'] }}</a>
            @else
                <span class="text-[#5B2730]">{{ $item['title'] }}</span>
            @endif
        @endforeach
    </nav>

    <nav class="hidden h-[31px] items-start gap-1.5 px-5 pb-1 pt-3.5 text-[11px] leading-[13px] max-sm:flex" aria-label="Breadcrumb">
        <a class="text-[#A98088]" href="{{ route('home') }}">Sevia</a>
        <span class="text-[#A98088]">/</span>
        <span class="font-cormorant font-semibold text-[#7A4751]">Парфуми</span>
    </nav>

    @if ($productsTotal > 0)
        <div class="hidden w-full gap-2 overflow-x-auto px-5 pb-3 max-sm:flex">
            @foreach (['Усі', 'Жіночі', 'Чоловічі', 'Унісекс', 'Sale'] as $tab)
                <a class="inline-flex h-[33px] shrink-0 items-center justify-center rounded-full px-4 text-[12px] font-medium leading-[15px] tracking-[0.3px] {{ $loop->first ? 'bg-[#5B2730] text-white' : 'border border-[#E8DAD0] text-[#7A4751]' }}" href="{{ route('catalog.index') }}">
                    {{ $tab }}
                </a>
            @endforeach
        </div>
    @endif

    <section class="{{ ($saleOnly ?? false) ? 'relative h-[215.79px] min-h-0 border-0 bg-[linear-gradient(180deg,#F3E3DF_0%,rgba(243,227,223,0)_100%)] px-[68px] pb-0 pt-0' : 'border-b border-[#EFE4D9] bg-white px-[68px] pb-[44px] pt-[57px]' }} max-lg:px-6 max-sm:h-auto max-sm:min-h-0 max-sm:border-0 max-sm:bg-white max-sm:px-5 max-sm:pb-[18px] max-sm:pt-2 sm:max-lg:flex sm:max-lg:h-[216.47px] sm:max-lg:min-h-[216.47px] sm:max-lg:flex-col sm:max-lg:items-end sm:max-lg:gap-[20.16px] sm:max-lg:px-[49px] sm:max-lg:pb-[22.49px] sm:max-lg:pt-[25.18px]">
        <div class="{{ ($saleOnly ?? false) ? 'relative block h-full max-w-none max-sm:h-auto' : 'mx-auto flex w-full max-w-[1304px] items-end justify-between gap-8 max-md:flex-col max-md:items-start' }} sm:max-lg:mx-auto sm:max-lg:flex sm:max-lg:h-[168.8px] sm:max-lg:w-full sm:max-lg:max-w-[700px] sm:max-lg:flex-col sm:max-lg:items-stretch sm:max-lg:gap-[20.16px]">
            <div class="{{ ($saleOnly ?? false) ? 'absolute left-0 top-[44.56px] max-w-[839.05px] max-sm:relative max-sm:left-auto max-sm:top-auto max-sm:max-w-none' : 'max-w-[620px]' }} sm:max-lg:flex sm:max-lg:h-[107.35px] sm:max-lg:w-full sm:max-lg:max-w-[700px] sm:max-lg:flex-col sm:max-lg:items-start sm:max-lg:gap-[9.35px]">
                @if ($saleOnly ?? false)
                    <h1 class="m-0 h-[76px] font-cormorant text-[75.6px] font-medium leading-[76px] tracking-[-1.134px] text-[#5B2730] max-sm:h-auto max-sm:text-[36px] max-sm:leading-[44px]">Sale · Знижки</h1>
                @else
                <h1 class="m-0 font-cormorant text-[78px] font-medium leading-none text-[#5B2730] max-sm:text-[36px] max-sm:leading-[44px] sm:max-lg:flex sm:max-lg:h-[50px] sm:max-lg:w-full sm:max-lg:items-center sm:max-lg:text-[49.4px] sm:max-lg:leading-[49px] sm:max-lg:tracking-[-0.741px]">
                    Парфуми
                </h1>
                @endif
                @if ($saleOnly ?? false)
                    <p class="m-0 mt-[14px] h-[50px] max-w-[518px] text-[16px] leading-[25px] text-[#7A4751] max-sm:h-auto max-sm:max-w-[530px] max-sm:mt-1.5 max-sm:text-[13px] max-sm:leading-[18px]">Обрані аромати за зниженою ціною, поки є в наявності. Без таймерів і галасу — просто гарна нагода.</p>
                @else
                <p class="m-0 mt-4 max-w-[530px] text-[16px] leading-[25px] text-[#7A4751] max-sm:mt-1.5 max-sm:text-[13px] max-sm:leading-[18px] sm:max-lg:mt-0 sm:max-lg:h-12 sm:max-lg:w-[368px] sm:max-lg:max-w-[404.23px] sm:max-lg:text-[15.2px] sm:max-lg:leading-6">
                    {{ $productsTotal }} ароматів від нішевих і люксових домів<span class="max-sm:hidden">. Спробуй на розлив від 3 мл або забери повним флаконом.</span>
                </p>
                @endif
            </div>

            <form class="{{ ($saleOnly ?? false) ? 'absolute right-0 top-[130.86px] flex h-[53.7px] items-center gap-8 pb-2 max-lg:top-[160px] max-lg:right-6' : 'flex items-center gap-5' }} text-[11px] uppercase tracking-[2.4px] text-[#A98088] max-sm:hidden sm:max-lg:h-[40.29px] sm:max-lg:w-full sm:max-lg:justify-between sm:max-lg:gap-6 sm:max-lg:text-[11.4px] sm:max-lg:font-normal sm:max-lg:leading-[18px] sm:max-lg:tracking-[2.508px] sm:max-lg:text-[#8A5D66]" method="GET" action="{{ url()->current() }}">
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

                <span class="sm:max-lg:flex sm:max-lg:h-[18px] sm:max-lg:items-center sm:max-lg:text-[11.4px] sm:max-lg:font-normal sm:max-lg:leading-[18px] sm:max-lg:tracking-[2.508px] sm:max-lg:text-[#8A5D66]">{{ $productsTotal }} позицій</span>
                <label class="flex items-center gap-3 sm:max-lg:h-[40.29px] sm:max-lg:gap-[8.64px]">
                    <span class="sm:max-lg:flex sm:max-lg:h-[18px] sm:max-lg:items-center sm:max-lg:text-[11.4px] sm:max-lg:font-normal sm:max-lg:leading-[18px] sm:max-lg:tracking-[2.508px] sm:max-lg:text-[#8A5D66]">Сортування</span>
                    <select class="h-[42px] border border-[#E8DAD0] bg-[#FDFBF8] px-4 text-[13px] normal-case tracking-normal text-[#5B2730] sm:max-lg:h-[40.29px] sm:max-lg:w-[153.37px] sm:max-lg:px-[13.68px] sm:max-lg:py-[8.64px] sm:max-lg:text-[13.3px] sm:max-lg:font-medium sm:max-lg:leading-[21px] sm:max-lg:tracking-[0.266px]" name="sort" onchange="this.form.submit()">
                        @foreach ($sortLabels as $value => $label)
                            <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </form>
        </div>
    </section>

    <section class="hidden h-[57px] items-center justify-between border-y border-[#E8DAD0] bg-[#FDFBF8] px-5 py-3 max-sm:flex">
        <button class="inline-flex h-[33px] items-center gap-[7px] border border-[#5B2730] px-3.5 text-[11.5px] font-medium uppercase leading-[14px] tracking-[1px] text-[#5B2730]" type="button" data-filter-open>
            <svg class="h-2 w-[13px]" width="13" height="8" viewBox="0 0 13 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M0.5625 0.5625H9M0.5625 3.84375H11.8125M0.5625 7.125H6.1875" stroke="#5B2730" stroke-width="1.125" stroke-linecap="round"/>
            </svg>
            <span>Фільтри</span>
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

        <aside class="relative flex h-full w-full max-w-[393px] flex-col bg-white" aria-label="Фільтри каталогу">
            <div class="flex h-[61px] w-full items-center justify-between border-b border-[#E8DAD0] bg-white px-5 py-4">
                <h2 class="m-0 font-cormorant text-[24px] font-medium leading-[29px] text-[#5B2730]">Фільтри</h2>
                <button class="text-[16px] leading-[19px] text-[#7A4751]" type="button" aria-label="Закрити фільтри" data-filter-close>×</button>
            </div>

            <form class="flex min-h-0 flex-1 flex-col" method="GET" action="{{ url()->current() }}" data-filter-count-form data-filter-count-url="{{ route('catalog.count') }}">
                <input type="hidden" name="sort" value="{{ $sort }}">

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

    <section class="border-b border-[#EFE4D9] {{ ($saleOnly ?? false) ? 'bg-[#F3E3DF]' : 'bg-[#FDFBF8]' }} px-[68px] py-[18px] max-lg:px-6 max-sm:border-0 max-sm:bg-white max-sm:px-5 max-sm:pb-2 max-sm:pt-3 sm:max-lg:px-[49px] sm:max-lg:pb-[13.68px] sm:max-lg:pt-[12.96px]">
        <div class="mx-auto flex min-h-[44px] w-full max-w-[1304px] items-center gap-4 text-[11px] uppercase tracking-[2px] text-[#A98088] max-sm:min-h-[25px] max-sm:justify-between max-sm:gap-3 max-sm:tracking-normal sm:max-lg:min-h-[34.52px] sm:max-lg:max-w-[700px] sm:max-lg:flex-wrap sm:max-lg:content-center sm:max-lg:gap-x-[12.96px] sm:max-lg:gap-y-0 sm:max-lg:text-[11.4px] sm:max-lg:leading-[18px] sm:max-lg:tracking-[2.508px] sm:max-lg:text-[#8A5D66]">
            <span class="max-sm:hidden">Обрано:</span>

            @if ($saleOnly ?? false)
                <a class="inline-flex h-[38px] items-center gap-2 border border-[#E8DAD0] bg-[#F8EDE7] px-3.5 text-[13px] font-medium normal-case tracking-normal text-[#5B2730] max-sm:h-[25px] max-sm:px-2.5 max-sm:text-[10.5px]" href="{{ route('sale.index') }}">
                    Sale <span class="text-[14px] font-medium leading-[22px] text-[#A98088]">{{ $productsTotal }}</span>
                </a>
                <a class="ml-1 text-[12px] font-normal uppercase leading-[19px] tracking-[2.16px] text-[#5B2730] max-sm:text-[10px] max-sm:tracking-[1px]" href="{{ route('sale.index') }}">Скинути все</a>
            @else
            @forelse ($selectedChips as $chip)
                <a class="inline-flex h-8 items-center border border-[#E8DAD0] bg-[#F8EDE7] px-3 text-[12px] normal-case tracking-normal text-[#5B2730] max-sm:h-[25px] max-sm:border-0 max-sm:px-2.5 max-sm:text-[10.5px] max-sm:uppercase max-sm:leading-[13px] max-sm:tracking-[0.5px] max-sm:text-[#7A4751] sm:max-lg:h-[34.52px] sm:max-lg:px-[10.8px] sm:max-lg:py-[5.76px] sm:max-lg:text-[12.4px] sm:max-lg:font-medium sm:max-lg:leading-[19px]">
                    {{ $chip['title'] }} <span class="ml-1 text-[#A98088]">×</span>
                </a>
            @empty
                <span class="normal-case tracking-normal text-[#7A4751] max-sm:hidden">Фільтри не вибрані</span>
            @endforelse

            @if ($selectedChips->isNotEmpty() || request()->hasAny(['price_min', 'price_max']))
                <a class="ml-1 text-[#5B2730] underline underline-offset-4 max-sm:hidden" href="{{ url()->current() }}">Скинути все</a>
            @endif
            @endif

            <span class="ml-auto hidden text-[11px] normal-case leading-[13px] tracking-normal text-[#A98088] max-sm:block">{{ $productsTotal }} позицій</span>
        </div>
    </section>

    <section class="bg-white px-[68px] pb-[88px] pt-[58px] max-lg:px-6 max-sm:px-4 max-sm:pb-6 max-sm:pt-1 sm:max-lg:px-[49px] sm:max-lg:pb-[88px] sm:max-lg:pt-[12.96px]">
        <div class="mx-auto grid w-full max-w-[1304px] grid-cols-[280px_minmax(0,1fr)] gap-[54px] max-lg:grid-cols-1 sm:max-lg:block sm:max-lg:max-w-[700px]">
            <aside class="hidden lg:block">
                <form class="flex w-[280px] flex-col items-start gap-9 max-lg:w-full" method="GET" action="{{ url()->current() }}">
                    <input type="hidden" name="sort" value="{{ $sort }}">

                    @foreach ($filterGroups as $group)
                        <fieldset class="w-full">
                            <legend class="mb-4 pb-3 text-[11px] font-semibold uppercase leading-[17px] tracking-[3.08px] text-[#5B2730]">{{ $group['title'] }}</legend>

                            @if (($group['role'] ?? null) === 'brand')
                                <input class="mb-4 h-[38px] w-full border border-[#E8DAD0] bg-[#FDFBF8] px-3 text-[13px] leading-4 text-[#5B2730] outline-none placeholder:text-[#A98088]" type="search" placeholder="Пошук бренду..." data-brand-search>
                            @endif

                            <div class="grid gap-2.5" @if (($group['role'] ?? null) === 'brand') data-brand-list @endif>
                                @foreach (collect($group['values'])->take(($group['role'] ?? null) === 'brand' ? 999 : 10) as $value)
                                    <label class="@if (($group['role'] ?? null) === 'brand' && $loop->iteration > 5) hidden @else flex @endif min-h-[22px] cursor-pointer items-center justify-between gap-3 text-[14px] leading-[22px] text-[#7A4751]" @if (($group['role'] ?? null) === 'brand' && $loop->iteration > 5) data-brand-extra @endif>
                                        <span class="flex min-w-0 items-center gap-2.5">
                                            <input class="size-3.5 border border-[#E8DAD0] bg-[#FDFBF8] accent-[#5B2730]" type="checkbox" name="filters[{{ $group['id'] }}][]" value="{{ $value['id'] }}" @checked(in_array($value['id'], $selectedFilters[$group['id']] ?? [], true)) onchange="this.form.submit()">
                                            <span class="truncate">{{ $value['title'] }}</span>
                                        </span>
                                        <span class="text-[12px] leading-[19px] tracking-[0.48px] text-[#A98088]">{{ $value['count'] }}</span>
                                    </label>
                                @endforeach
                            </div>

                            @if (($group['role'] ?? null) === 'brand' && collect($group['values'])->count() > 5)
                                <button class="mt-2.5 text-[12px] uppercase leading-[19px] tracking-[2.16px] text-[#5B2730]" type="button" data-brand-toggle data-expanded-label="Згорнути" data-collapsed-label="Показати всі ({{ collect($group['values'])->count() }}) +">Показати всі ({{ collect($group['values'])->count() }}) +</button>
                            @endif
                        </fieldset>
                    @endforeach

                    <fieldset class="w-full" data-price-filter data-price-min="{{ $priceMin }}" data-price-max="{{ $priceMax }}">
                        <legend class="mb-4 pb-3 text-[11px] font-semibold uppercase leading-[17px] tracking-[3.08px] text-[#5B2730]">Ціна</legend>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="grid h-[65px] gap-1 border border-[#E8DAD0] bg-[#FDFBF8] px-3 py-2.5">
                                <span class="text-[11px] uppercase leading-[17px] tracking-[2.42px] text-[#A98088]">від</span>
                                <span class="flex items-center gap-1 text-[14px] font-medium leading-[22px] text-[#5B2730]">
                                    <input class="w-full bg-transparent outline-none" type="number" min="{{ $priceMin }}" max="{{ $priceMax }}" name="price_min" value="{{ $currentPriceMin }}" data-price-min-input>
                                    <span>₴</span>
                                </span>
                            </label>
                            <label class="grid h-[65px] gap-1 border border-[#E8DAD0] bg-[#FDFBF8] px-3 py-2.5">
                                <span class="text-[11px] uppercase leading-[17px] tracking-[2.42px] text-[#A98088]">до</span>
                                <span class="flex items-center gap-1 text-[14px] font-medium leading-[22px] text-[#5B2730]">
                                    <input class="w-full bg-transparent outline-none" type="number" min="{{ $priceMin }}" max="{{ $priceMax }}" name="price_max" value="{{ $currentPriceMax }}" data-price-max-input>
                                    <span>₴</span>
                                </span>
                            </label>
                        </div>
                        <div class="sevia-price-range relative mt-5 h-[30px] w-full">
                            <span class="absolute left-0 right-0 top-[15px] h-0.5 bg-[#E8DAD0]"></span>
                            <span class="absolute top-[15px] h-0.5 bg-[#5B2730]" data-price-range-fill></span>
                            <input class="sevia-price-range__input" type="range" min="{{ $priceMin }}" max="{{ $priceMax }}" value="{{ $currentPriceMin }}" step="1" aria-label="Мінімальна ціна" data-price-min-range>
                            <input class="sevia-price-range__input" type="range" min="{{ $priceMin }}" max="{{ $priceMax }}" value="{{ $currentPriceMax }}" step="1" aria-label="Максимальна ціна" data-price-max-range>
                        </div>
                    </fieldset>

                    <a class="inline-flex h-[34px] items-center text-[13px] font-medium uppercase leading-5 tracking-[2.34px] text-[#5B2730]" href="{{ url()->current() }}">Скинути фільтри</a>
                </form>
            </aside>

            <div>
                @if ($productsTotal > 0)
                    <aside class="hidden w-full flex-col items-start gap-[13px] pb-[18px] sm:max-lg:flex" aria-label="Фільтри каталогу">
                        <button class="inline-flex h-[37px] items-center gap-[6px] border border-[#5B2730] bg-[#FDFBF8] px-[13px] text-[12.4px] font-medium uppercase leading-[19px] tracking-[1.48px] text-[#5B2730]" type="button" data-filter-open>
                            <span>Фільтри</span>
                            @if ($selectedChips->isNotEmpty())
                                <span class="inline-flex size-4 items-center justify-center rounded-full bg-[#5B2730] pb-px text-[10.5px] leading-[13px] tracking-[1.48px] text-[#FDFBF8]">{{ $selectedChips->count() }}</span>
                            @endif
                        </button>

                        <form class="flex w-full flex-col items-start gap-[9px]" method="GET" action="{{ url()->current() }}">
                            @foreach (request()->except(['volume', 'page']) as $key => $value)
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

                            <h4 class="m-0 h-[25px] text-[10.5px] font-semibold uppercase leading-4 tracking-[2.93px] text-[#5B2730]">Обʼєм розпиву</h4>
                            <div class="grid w-full grid-cols-6 gap-[4px]">
                                @foreach ($volumeAmounts as $amount)
                                    <button class="h-[36px] border px-2 text-center text-[12.4px] font-medium leading-[19px] tracking-[0.49px] {{ $selectedVolume === $amount ? 'border-[#5B2730] bg-[#5B2730] text-[#FDFBF8]' : 'border-[#E8DAD0] bg-[#FDFBF8] text-[#7A4751]' }}" type="submit" name="volume" value="{{ $amount }}">{{ $amount }} мл</button>
                                @endforeach
                            </div>
                        </form>

                        <div class="flex h-[33px] w-full items-center gap-[7px] border-t border-dashed border-[#EFE4D9] pt-[11px]">
                            <span class="block size-[10px] border border-[#E8DAD0] bg-[#FDFBF8]" aria-hidden="true"></span>
                            <span class="text-[13.3px] leading-[21px] text-[#7A4751]">Повний флакон 50-100 мл</span>
                            <span class="ml-auto text-[11.4px] leading-[18px] tracking-[0.46px] text-[#8A5D66]">41</span>
                        </div>
                    </aside>
                @endif

                @if ($productsTotal === 0)
                    <section class="hidden h-[369px] w-full flex-col items-center justify-center gap-4 px-9 pb-[72px] pt-[60px] text-center max-sm:flex">
                        <div class="flex size-[76px] items-center justify-center rounded-full bg-[#FBF1ED]" aria-hidden="true">
                            <svg class="size-[30px]" width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.25 21.25C17.6683 21.25 21.25 17.6683 21.25 13.25C21.25 8.83172 17.6683 5.25 13.25 5.25C8.83172 5.25 5.25 8.83172 5.25 13.25C5.25 17.6683 8.83172 21.25 13.25 21.25Z" stroke="#5B2730" stroke-width="1.4"/>
                                <path d="M19.25 19.25L24.25 24.25" stroke="#5B2730" stroke-width="1.4" stroke-linecap="round"/>
                                <path d="M10.6 10.6L15.9 15.9M15.9 10.6L10.6 15.9" stroke="#5B2730" stroke-width="1.2" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <h2 class="m-0 text-center font-cormorant text-[25px] font-medium leading-[30px] text-[#5B2730]">Нічого не знайдено</h2>
                        <p class="m-0 w-full max-w-[321px] text-center text-[13px] leading-[20px] text-[#7A4751]">
                            За обраними фільтрами ароматів немає. Спробуй змінити параметри або скинути фільтри.
                        </p>
                        <a class="inline-flex h-[43px] items-center justify-center border border-[#5B2730] px-[26px] text-[12px] font-medium uppercase leading-[15px] tracking-[1.4px] text-[#5B2730]" href="{{ url()->current() }}">Скинути фільтри</a>
                    </section>

                    <section class="box-border flex h-[603px] w-full flex-col items-center border border-[#EFE4D9] bg-white px-[72px] py-24 text-center max-md:h-auto max-md:min-h-[560px] max-md:px-6 max-sm:hidden">
                        <div class="flex h-[62px] w-[30px] flex-col items-start pb-[22px]" aria-hidden="true">
                            <svg class="block h-10 w-[30px] shrink-0" width="30" height="40" viewBox="0 0 30 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.04 18.44L12.88 12.6C12.6133 11.9067 12.48 11.1867 12.48 10.44C12.48 8.78666 13.3333 7.96 15.04 7.96C16.6933 7.96 17.52 8.76 17.52 10.36C17.52 11.24 17.3867 11.9867 17.12 12.6L15.04 18.44ZM11.6 20.28L5.44 19.16C4.66667 19.0267 3.90667 18.72 3.16 18.24C2.41333 17.76 2.04 17.1733 2.04 16.48C2.04 16.08 2.14667 15.6667 2.36 15.24C2.57333 14.7867 2.86667 14.4133 3.24 14.12C3.61333 13.8 4.01333 13.64 4.44 13.64C5.45333 13.64 6.50667 14.28 7.6 15.56L11.6 20.28ZM18.48 20.28L22.48 15.56C22.9333 15.0267 23.44 14.5733 24 14.2C24.5867 13.8267 25.12 13.64 25.6 13.64C26.0267 13.64 26.4133 13.8 26.76 14.12C27.1333 14.4133 27.4267 14.7867 27.64 15.24C27.8533 15.6667 27.96 16.08 27.96 16.48C27.96 16.9333 27.7867 17.36 27.44 17.76C27.0933 18.1333 26.6533 18.44 26.12 18.68C25.6133 18.92 25.0933 19.08 24.56 19.16L18.48 20.28ZM15.04 25.32C14.2133 25.32 13.5067 25.04 12.92 24.48C12.36 23.92 12.08 23.2133 12.08 22.36C12.08 21.5333 12.36 20.84 12.92 20.28C13.5067 19.6933 14.2133 19.4 15.04 19.4C15.8667 19.4 16.56 19.6933 17.12 20.28C17.7067 20.84 18 21.5333 18 22.36C18 23.1867 17.7067 23.8933 17.12 24.48C16.56 25.04 15.8667 25.32 15.04 25.32ZM4.4 31.08C3.94667 31.08 3.53333 30.9333 3.16 30.64C2.81333 30.32 2.53333 29.9333 2.32 29.48C2.13333 29.0267 2.04 28.6133 2.04 28.24C2.04 27.5467 2.41333 26.96 3.16 26.48C3.90667 26 4.66667 25.6933 5.44 25.56L11.6 24.44L7.6 29.16C6.53333 30.44 5.46667 31.08 4.4 31.08ZM25.56 31.08C25.08 31.08 24.56 30.8933 24 30.52C23.44 30.12 22.9333 29.6667 22.48 29.16L18.48 24.44L24.56 25.56C25.3333 25.6933 26.0933 26 26.84 26.48C27.5867 26.96 27.96 27.5467 27.96 28.24C27.96 28.8533 27.7333 29.48 27.28 30.12C26.8533 30.76 26.28 31.08 25.56 31.08ZM15.04 36.76C13.3333 36.76 12.48 35.8533 12.48 34.04C12.48 33.72 12.52 33.4 12.6 33.08C12.68 32.76 12.7733 32.44 12.88 32.12L15.04 26.28L17.12 32.12C17.2533 32.4667 17.3467 32.8267 17.4 33.2C17.48 33.5467 17.52 33.9067 17.52 34.28C17.52 35.9333 16.6933 36.76 15.04 36.76Z" fill="#E7CDCA"/>
                            </svg>
                        </div>
                        <div class="flex h-[31px] w-[170px] flex-col items-start pb-3.5">
                            <p class="m-0 flex h-[17px] w-[170px] items-center justify-center text-center text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088]">Нічого не знайдено</p>
                        </div>
                        <div class="flex h-[122px] w-full max-w-[524px] flex-col items-start pb-[18px]">
                            <h2 class="m-0 flex h-[104px] w-full items-center justify-center text-center font-cormorant text-[46px] font-medium leading-[52px] tracking-normal text-[#5B2730] max-sm:text-[34px] max-sm:leading-10">
                                За вибраними фільтрами поки нічого немає.
                            </h2>
                        </div>
                        <div class="flex h-[74px] w-full max-w-[460px] flex-col items-start pb-[26px]">
                            <p class="m-0 flex h-12 w-full items-center justify-center px-[31px] text-center font-sans text-[15px] font-normal leading-6 tracking-normal text-[#A98088] max-sm:px-0">
                                Можливо, варто прибрати частину фільтрів або перевірити написання. Ось що шукають найчастіше:
                            </p>
                        </div>
                        <div class="flex h-[70px] w-full max-w-[479px] flex-col items-start pb-[34px]">
                            <div class="flex h-[36px] w-full flex-wrap justify-center gap-x-2 gap-y-2 overflow-hidden">
                                @foreach (['Жіночі', 'Унісекс', 'Деревні', 'Свіжі', 'Musk'] as $hint)
                                    <a class="inline-flex h-[36px] items-center justify-center border border-[#E8DAD0] bg-[#F8EDE7] px-3.5 text-[13px] font-medium leading-5 text-[#5B2730]" href="{{ route('catalog.index') }}">{{ $hint }}</a>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex h-[50px] w-full max-w-[418px] flex-wrap justify-center gap-x-3.5 gap-y-3 overflow-hidden">
                            <a class="inline-flex h-[50px] items-center justify-center bg-[#5B2730] px-7 text-[13px] font-medium uppercase leading-5 tracking-[1.56px] text-[#FDFBF8]" href="{{ url()->current() }}">Скинути фільтри</a>
                            <a class="inline-flex h-[50px] items-center justify-center px-0.5 text-[13px] font-medium uppercase leading-5 tracking-[2.34px] text-[#5B2730]" href="{{ route('catalog.index') }}">До всіх парфумів</a>
                        </div>
                    </section>
                @else
                    <div class="grid grid-cols-3 gap-x-8 gap-y-[58px] max-md:grid-cols-2 max-sm:grid-cols-2 max-sm:gap-x-2 max-sm:gap-y-3 sm:max-lg:grid-cols-3 sm:max-lg:gap-x-[30px] sm:max-lg:gap-y-11">
                        @foreach ($products as $product)
                            @php
                                $volumes = collect($product['volumes'] ?? []);
                                $defaultVolume = $selectedVolume > 0
                                    ? $volumes->first(fn ($volume) => (int) preg_replace('/\D+/', '', (string) ($volume['label'] ?? '')) === $selectedVolume)
                                    : null;
                                $defaultVolume ??= $volumes->first();
                                $defaultVolumeAmount = (int) preg_replace('/\D+/', '', (string) ($defaultVolume['label'] ?? ''));
                            @endphp
                            <article class="group flex min-h-[430px] flex-col max-sm:min-h-[314px] max-sm:border max-sm:border-[#E8DAD0] max-sm:bg-white sm:max-lg:min-h-[330px]" data-product-card>
                                <a class="relative flex h-[286px] items-center justify-center bg-white p-6 max-sm:h-[182px] max-sm:bg-[#FDFBF8] max-sm:p-6 sm:max-lg:h-[170px] sm:max-lg:p-3" href="{{ $product['url'] }}">
                                    @if (($saleOnly ?? false) || $product['badge'])
                                        <span class="absolute left-0 top-0 border border-[rgba(91,39,48,0.16)] bg-[#F3E3DF] px-2.5 py-1 text-[9px] font-semibold uppercase tracking-[1.3px] text-[#5B2730] max-sm:left-2 max-sm:top-2 max-sm:border-0 max-sm:bg-[#B08C57] max-sm:px-[7px] max-sm:py-1 max-sm:text-[8px] max-sm:leading-[10px] max-sm:tracking-[0.4px] max-sm:text-white">{{ ($saleOnly ?? false) ? '-'.$product['discount_percent'].'%' : $product['badge'] }}</span>
                                    @endif
                                    <button class="absolute right-0 top-0 grid size-9 place-items-center rounded-full border border-[#E8DAD0] bg-white text-[#7A4751] max-sm:right-2 max-sm:top-2 max-sm:size-7 max-sm:text-[13px] max-sm:text-[#5B2730]" type="button" aria-label="Додати в обране" aria-pressed="{{ in_array((int) $product['id'], $favoriteIds ?? [], true) ? 'true' : 'false' }}" data-favorite-toggle data-favorite-url="{{ route('favorites.toggle') }}" data-product-id="{{ $product['id'] }}">{{ in_array((int) $product['id'], $favoriteIds ?? [], true) ? '♥' : '♡' }}</button>
                                    <img class="max-h-full max-w-full object-contain transition duration-300 group-hover:scale-[1.03]" src="{{ $product['image'] }}" alt="{{ $product['brand'] }} {{ $product['name'] }}">
                                </a>

                                <div class="flex flex-1 flex-col pt-4 max-sm:h-[132px] max-sm:p-3 max-sm:pt-[11px] sm:max-lg:pt-3">
                                    <p class="m-0 text-[10px] font-medium uppercase leading-4 tracking-[1.8px] text-[#A98088] max-sm:hidden sm:max-lg:text-[7.9px] sm:max-lg:leading-[13px] sm:max-lg:tracking-[1.42px]">{{ $product['meta'] }}</p>
                                    <p class="m-0 mt-1 text-[12px] font-semibold uppercase leading-[19px] tracking-[0.5px] text-[#5B2730] max-sm:mt-0 max-sm:text-[9px] max-sm:font-medium max-sm:leading-[11px] max-sm:tracking-[0.6px] max-sm:text-[#7A4751]">{{ $product['brand'] }}</p>
                                    <h2 class="m-0 min-h-[46px] font-cormorant text-[21px] font-medium leading-[24px] text-[#5B2730] max-sm:min-h-[40px] max-sm:text-[17px] max-sm:leading-5 sm:max-lg:min-h-[34px] sm:max-lg:text-[15.1px] sm:max-lg:leading-[17px]">{{ $product['name'] }}</h2>
                                    @if (! empty($product['sku']))
                                        <p class="m-0 -mt-1 text-[10px] uppercase leading-4 tracking-[1.4px] text-[#A98088] max-sm:hidden">Арт. {{ $product['sku'] }}</p>
                                    @endif
                                     <p class="m-0 min-h-[38px] text-[12px] font-light leading-[18px] text-[#7A4751] max-sm:min-h-0 max-sm:truncate max-sm:text-[9px] max-sm:font-normal max-sm:leading-[11px] max-sm:text-[#A98088]">{{ $product['notes'] }}</p>

                                    @if ($defaultVolume)
                                        <div class="mt-3 flex flex-wrap gap-1 max-sm:mt-2 max-sm:grid max-sm:w-full max-sm:grid-cols-3 max-sm:gap-1" role="group" aria-label="Volume">
                                            @foreach ($volumes as $volume)
                                                @php
                                                    $volumeAmount = (int) preg_replace('/\D+/', '', (string) ($volume['label'] ?? ''));
                                                    $isSelectedVolume = $volumeAmount === $defaultVolumeAmount;
                                                @endphp
                                                <button class="inline-flex h-10 min-w-[46px] flex-col items-center justify-center gap-0 border border-[#D8C4BE] px-0.5 text-[#7A4751] transition-[border-color] hover:border-[#5B2730] hover:text-[#5B2730] {{ $isSelectedVolume ? 'sevia-volume-option--selected border-[#5B2730] text-[#5B2730]' : '' }} max-sm:h-10 max-sm:w-full max-sm:min-w-0 max-sm:px-0" type="button" data-volume-option data-volume-id="{{ $volume['id'] }}" data-volume-label="{{ $volume['label'] }}" data-price-label="{{ $volume['price_label'] }}" data-product-id="{{ $volume['id'] }}" data-product-price="{{ $volume['price'] }}" data-cart-product-label="{{ $product['brand'] }} {{ $product['name'] }} / {{ $volume['label'] }}"><span class="text-[9px] font-medium leading-3 max-sm:text-[8px]">{{ $volume['label'] }}</span><span class="text-[9px] font-medium leading-3 max-sm:text-[8px]">{{ $volume['price_label'] }}</span></button>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="mt-auto flex h-[52px] items-center justify-between border-t border-[#EFE4D9] pt-3 max-sm:h-[47px] max-sm:border-0 max-sm:p-0 max-sm:pt-3 sm:max-lg:h-[42px] sm:max-lg:pt-[9px]" data-cart-action-row>
                                        <p class="m-0 flex items-end gap-1.5 max-sm:items-baseline max-sm:gap-[5px]">
                                            <span class="text-[15px] font-semibold leading-5 text-[#5B2730] max-sm:text-[15px] max-sm:leading-[23px]" data-product-price-display>{{ $defaultVolume['price_label'] ?? $product['price_label'] }}</span>
                                            @if ($product['old_price_label'])
                                                <span class="text-[12px] leading-5 text-[#A98088] line-through max-sm:text-[9px] max-sm:leading-[11px] max-sm:text-[#C9A9B0]">{{ $product['old_price_label'] }}</span>
                                            @endif
                                            <span class="text-[10.5px] leading-4 text-[#A98088]" data-product-volume-label>/ {{ $defaultVolume['label'] ?? $product['unit'] }}</span>
                                        </p>
                                        <div class="hidden items-center justify-center gap-2 rounded-full border border-[#5B2730] bg-[#FDFBF8] px-3 h-[34px]" data-cart-quantity-controls>
                                            <button class="text-[19px] leading-[19px] text-[#959595]" type="button" aria-label="Decrease quantity" data-cart-quantity-minus>-</button>
                                            <span class="min-w-[7px] text-center text-[19px] leading-[19px] text-[#5B2730]" data-cart-quantity>0</span>
                                            <button class="text-[19px] leading-[19px] text-[#5B2730]" type="button" aria-label="Increase quantity" data-cart-quantity-plus>+</button>
                                        </div>
                                        <button class="grid size-[34px] place-items-center rounded-full border border-[#A85D66] text-[20px] leading-none text-[#A85D66] max-sm:size-[34px] max-sm:border-[#5B2730] max-sm:text-[20px] max-sm:text-[#5B2730]" type="button" aria-label="Add to cart" data-cart-add data-cart-add-url="{{ route('cart.add') }}" data-product-id="{{ $defaultVolume['id'] ?? $product['cart_product_id'] }}" data-product-price="{{ $defaultVolume['price'] ?? $product['cart_price'] }}" data-volume-label="{{ $defaultVolume['label'] ?? $product['unit'] }}" data-cart-product-label="{{ $product['brand'] }} {{ $product['name'] }} / {{ $defaultVolume['label'] ?? $product['unit'] }}">+</button>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                @if ($productsTotal > 0 && $lastPage > 1)
                    <nav class="mt-[72px] flex items-center justify-between border-t border-[#EFE4D9] pt-9 text-[12px] font-semibold uppercase tracking-[2px] text-[#7A4751] max-sm:mt-2 max-sm:h-[78px] max-sm:justify-center max-sm:gap-1.5 max-sm:border-0 max-sm:pb-9 max-sm:pt-2 max-sm:text-[13px] max-sm:font-normal max-sm:normal-case max-sm:tracking-normal" aria-label="Pagination">
                        <a class="{{ $page <= 1 ? 'pointer-events-none opacity-40' : '' }} max-sm:px-1 max-sm:text-[#A98088]" href="{{ $pageUrl(max(1, $page - 1)) }}">← <span class="max-sm:hidden">Назад</span></a>
                        <div class="flex items-center gap-4 max-sm:gap-1.5">
                            @for ($i = 1; $i <= $lastPage; $i++)
                                @if ($i <= 3 || $i === $lastPage || abs($i - $page) <= 1)
                                    <a class="grid size-8 place-items-center {{ $page === $i ? 'bg-[#7A2535] text-white' : '' }} max-sm:size-[34px] max-sm:rounded-full max-sm:border max-sm:border-[#E8DAD0] max-sm:text-[12px] max-sm:font-medium max-sm:leading-[15px] max-sm:text-[#7A4751] {{ $page === $i ? 'max-sm:border-[#5B2730] max-sm:bg-[#5B2730] max-sm:text-white' : '' }}" href="{{ $pageUrl($i) }}">{{ $i }}</a>
                                @elseif ($i === 4)
                                    <span class="max-sm:px-1 max-sm:text-[#A98088]">…</span>
                                @endif
                            @endfor
                        </div>
                        <a class="{{ $page >= $lastPage ? 'pointer-events-none opacity-40' : '' }} max-sm:px-1 max-sm:text-[#5B2730]" href="{{ $pageUrl(min($lastPage, $page + 1)) }}"><span class="max-sm:hidden">Далі</span> →</a>
                    </nav>
                @endif
            </div>
        </div>
    </section>
@endsection
