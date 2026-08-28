@extends('front.sevia::layouts.app')

@section('title', $detail['title'] . ' | Sevia')
@section('meta_description', $detail['description'])

@php
    $reviewsTotal = (int) ($stats->total ?? 0);
    $averageRating = $reviewsTotal > 0 ? round((float) ($stats->avg_rating ?? 0), 1) : 0.0;
    $reviewBuckets = [
        5 => (int) ($stats->r5 ?? 0),
        4 => (int) ($stats->r4 ?? 0),
        3 => (int) ($stats->r3 ?? 0),
        2 => (int) ($stats->r2 ?? 0),
        1 => (int) ($stats->r1 ?? 0),
    ];
    $reviewPercent = fn (int $stars): int => $reviewsTotal > 0 ? (int) round(($reviewBuckets[$stars] ?? 0) * 100 / $reviewsTotal) : 0;
    $starsText = fn (int $rating): string => str_repeat('★', max(0, min(5, $rating))) . str_repeat('☆', max(0, 5 - max(0, min(5, $rating))));
    $selectedVolume = $detail['volumes']->firstWhere('selected', true) ?? $detail['volumes']->first();
    $selectedCartProductId = (int) ($selectedVolume['id'] ?? $detail['selected_variant_id']);
    $selectedCartPrice = (float) ($selectedVolume['price'] ?? $detail['price']);
    $selectedCartLabel = collect([$detail['brand'], $detail['name'], $selectedVolume['label'] ?? null, $detail['price_label']])->filter()->implode(' · ');
@endphp

@section('content')
    <nav class="flex h-[50px] items-center gap-2.5 px-[68px] pb-2 pt-[22px] text-[13px] leading-5 max-lg:px-6 max-sm:hidden" aria-label="Breadcrumb">
        <a class="text-[#7A4751] hover:text-[#5B2730]" href="{{ route('home') }}">Sevia</a>
        <span class="text-[#E8DAD0]">/</span>
        <a class="text-[#7A4751] hover:text-[#5B2730]" href="{{ route('catalog.index') }}">Парфуми</a>
        <span class="text-[#E8DAD0]">/</span>
        <span class="text-[#5B2730]">{{ $detail['name'] }}</span>
    </nav>

    <nav class="flex h-[35px] items-start gap-1.5 px-5 pb-2.5 pt-3 text-[10.5px] leading-[13px] sm:hidden" aria-label="Breadcrumb">
        <a class="text-[#A98088]" href="{{ route('catalog.index') }}">Парфуми</a>
        <span class="text-[#A98088]">/</span>
        @if($detail['meta'] !== '')
            <span class="max-w-[82px] truncate text-[#A98088]">{{ \Illuminate\Support\Str::before($detail['meta'], ' · ') }}</span>
            <span class="text-[#A98088]">/</span>
        @endif
        <span class="truncate font-medium text-[#7A4751]">{{ $detail['name'] }}</span>
    </nav>

    <section class="sm:hidden">
        <div class="relative flex h-[408px] items-center justify-center bg-[#FBF1ED] px-[71px] py-[29px]">
            <img class="max-h-[350px] max-w-[250px] object-contain" src="{{ $detail['image'] }}" alt="{{ $detail['title'] }}">
            <button class="absolute right-4 top-4 flex size-10 items-center justify-center rounded-full bg-white text-[24px] leading-none text-[#5B2730]" type="button" aria-label="Додати в обране">♡</button>
            @if ($detail['gallery']->isNotEmpty())
                <div class="absolute bottom-4 left-1/2 flex -translate-x-1/2 items-center gap-[7px]">
                    @foreach ($detail['gallery'] as $image)
                        <span class="{{ $loop->first ? 'size-2 bg-[#5B2730]' : 'size-[7px] bg-[#C9A9B0]' }} rounded-full"></span>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="px-5 pb-2 pt-5">
            <p class="m-0 text-[9px] font-medium uppercase leading-[11px] tracking-[0.6px] text-[#A98088]">{{ $detail['meta'] }}</p>
            <p class="m-0 mt-2.5 text-[11px] font-medium uppercase leading-[13px] tracking-[0.8px] text-[#7A4751]">{{ $detail['brand'] }}</p>
            <h1 class="m-0 mt-2 font-cormorant text-[34px] font-medium leading-[41px] text-[#5B2730]">{{ $detail['name'] }}</h1>
            @if ($detail['sku'] !== '')
                <p class="m-0 mt-1 text-[9px] uppercase leading-[11px] tracking-[1.2px] text-[#A98088]">Арт. {{ $detail['sku'] }}</p>
            @endif

            <div class="mt-2.5 flex items-center gap-1.5">
                <span class="text-[13px] leading-4 tracking-[1px] text-[#5B2730]">{{ $starsText((int) round($averageRating)) }}</span>
                <span class="text-[12px] font-medium leading-[15px] text-[#5B2730]">{{ number_format($averageRating, 1) }}</span>
                <span class="text-[12px] leading-[15px] text-[#A98088]">{{ $reviewsTotal }} відгуків</span>
            </div>

            <p class="m-0 mt-2.5 text-[13px] leading-[148%] text-[#7A4751]">{{ $detail['short'] }}</p>

            <div class="mt-3 flex items-baseline gap-2.5">
                <p class="m-0 font-cormorant text-[32px] font-medium leading-[39px] text-[#5B2730]" data-product-price-display>{{ $detail['price_label'] }}</p>
                <p class="m-0 text-[11px] leading-[13px] text-[#A98088]">
                    <span data-product-volume-line>{{ $detail['unit'] }}</span>@if($detail['unit_price']) · <span data-product-unit-price>{{ $detail['unit_price'] }}</span>@endif
                </p>
            </div>

            <p class="m-0 mt-1.5 flex items-center gap-1.5 text-[11px] font-medium uppercase leading-[13px] tracking-[0.6px] text-[#7A4751]">
                <span class="size-[7px] rounded-full bg-[#6B9973]"></span>
                В наявності
            </p>
        </div>

        <div class="px-5 pb-2 pt-[18px]">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="m-0 text-[10px] font-medium uppercase leading-3 tracking-[1.6px] text-[#A98088]">Обʼєм розпиву</h2>
            </div>
            <div class="grid grid-cols-6 gap-1">
                @foreach ($detail['volumes'] as $volume)
                    <button
                        class="h-9 min-w-0 border px-0 text-[11px] font-medium leading-[13px] tracking-[0.1px] {{ $volume['selected'] ? 'border-[#5B2730] bg-[#5B2730] text-[#FDFBF8]' : 'border-[#E8DAD0] bg-white text-[#7A4751]' }}"
                        type="button"
                        data-volume-option
                        data-price-label="{{ $volume['price_label'] }}"
                        data-volume-label="{{ $volume['label'] }}"
                        data-unit-price-label="{{ $volume['unit_price_label'] }}"
                        data-product-id="{{ $volume['id'] ?? $detail['selected_variant_id'] }}"
                        data-product-price="{{ $volume['price'] }}"
                        data-cart-product-label="{{ collect([$detail['brand'], $detail['name'], $volume['label'], $volume['price_label']])->filter()->implode(' · ') }}"
                        data-selected="{{ $volume['selected'] ? 'true' : 'false' }}"
                    >
                        {{ $volume['label'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-3 px-5 pb-2.5 pt-3.5">
            <button class="h-[49px] flex-1 bg-[#5B2730] text-[12.5px] font-medium uppercase leading-[15px] tracking-[1.4px] text-white" type="button" data-cart-add data-cart-add-url="{{ route('cart.add') }}" data-product-id="{{ $selectedCartProductId }}" data-product-price="{{ $selectedCartPrice }}" data-cart-product-label="{{ $selectedCartLabel }}">Додати в кошик</button>
            <button class="flex size-[52px] items-center justify-center border border-[#5B2730] text-[24px] leading-none text-[#5B2730]" type="button" aria-label="Додати в обране">♡</button>
        </div>

        <ol class="m-0 list-none px-5 pb-5 pt-2.5">
            @foreach ($detail['benefits'] as $benefit)
                <li class="flex min-h-[45px] items-center gap-3 border-[#E8DAD0] py-[13px] {{ $loop->first ? '' : 'border-t' }}">
                    <span class="w-4 font-cormorant text-[16px] font-medium leading-[19px] text-[#CD9B97]">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="flex-1 text-[12px] leading-[140%] text-[#7A4751]">{{ $benefit }}</span>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="px-[68px] pb-[88px] pt-9 max-lg:px-6 max-sm:hidden">
        <div class="mx-auto grid w-full max-w-[1304px] grid-cols-[minmax(360px,518px)_minmax(0,560px)] gap-[60px] max-lg:grid-cols-1">
            <div class="relative flex min-h-[690px] items-center justify-center border border-[#E8DAD0] bg-[#F8EDE7] p-12 max-sm:min-h-[380px] max-sm:p-8">
                <img class="relative z-10 max-h-[560px] max-w-full object-contain drop-shadow-[0_30px_40px_rgba(42,31,25,0.16)] max-sm:max-h-[300px]" src="{{ $detail['image'] }}" alt="{{ $detail['title'] }}">
            </div>

            <div class="flex max-w-[560px] flex-col items-start">
                <p class="m-0 pb-2.5 text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088]">{{ $detail['meta'] }}</p>
                <p class="m-0 pb-0.5 text-[15px] font-medium leading-[23px] tracking-[0.6px] text-[#7A4751]">{{ $detail['brand'] }}</p>
                <h1 class="m-0 pb-3 font-cormorant text-[48px] font-medium leading-none tracking-[-0.96px] text-[#5B2730] max-sm:text-[38px]">{{ $detail['name'] }}</h1>
                @if ($detail['sku'] !== '')
                    <p class="m-0 pb-4 text-[11px] uppercase leading-[17px] tracking-[2.2px] text-[#A98088]">Арт. {{ $detail['sku'] }}</p>
                @endif

                <div class="flex items-center gap-2 pb-4">
                    <span class="text-[15px] tracking-[0.6px] text-[#A85D66]">{{ $starsText((int) round($averageRating)) }}</span>
                    <span class="text-[14px] font-semibold leading-[22px] text-[#5B2730]">{{ number_format($averageRating, 1) }}</span>
                    <span class="text-[14px] leading-[22px] text-[#A98088]">{{ $reviewsTotal }} відгуків</span>
                </div>

                <p class="m-0 max-w-[505px] pb-[22px] font-cormorant text-[19px] font-medium leading-[27px] text-[#5B2730]">{{ $detail['short'] }}</p>

                <div class="w-full border-y border-[#EFE4D9] py-[22px]">
                    <div class="flex items-end justify-between gap-8">
                        <div>
                            <p class="m-0 font-cormorant text-[36px] font-medium leading-none tracking-[-0.36px] text-[#5B2730]" data-product-price-display>{{ $detail['price_label'] }}</p>
                            <p class="m-0 mt-1.5 text-[12px] uppercase leading-[19px] tracking-[2.16px] text-[#A98088]">
                                <span data-product-volume-line>{{ $detail['unit'] }}</span>@if($detail['unit_price']) · <span data-product-unit-price>{{ $detail['unit_price'] }}</span>@endif
                            </p>
                        </div>
                        <p class="m-0 flex items-center gap-2 text-[13px] leading-5 text-[#7A4751]">
                            <span class="size-2 rounded-full bg-[#6BA070] shadow-[0_0_0_3px_rgba(107,160,112,0.18)]"></span>
                            В наявності
                        </p>
                    </div>

                    <div class="pt-6">
                        <div class="mb-3 flex items-center justify-between">
                            <h2 class="m-0 text-[11px] font-semibold uppercase leading-[17px] tracking-[3.08px] text-[#5B2730]">Обʼєм розпиву</h2>
                            <a class="text-[12px] leading-[19px] tracking-[0.48px] text-[#7A4751]" href="{{ route('faq') }}">Як обрати?</a>
                        </div>

                        <div class="grid grid-cols-6 gap-1.5 max-sm:grid-cols-3">
                            @foreach ($detail['volumes'] as $volume)
                                <button
                                    class="h-11 border px-3 text-[13px] font-medium leading-5 tracking-[0.52px] {{ $volume['selected'] ? 'border-[#5B2730] bg-[#5B2730] text-[#FDFBF8]' : 'border-[#E8DAD0] bg-[#FDFBF8] text-[#7A4751]' }}"
                                    type="button"
                                    data-volume-option
                                    data-price-label="{{ $volume['price_label'] }}"
                                    data-volume-label="{{ $volume['label'] }}"
                                    data-unit-price-label="{{ $volume['unit_price_label'] }}"
                                    data-product-id="{{ $volume['id'] ?? $detail['selected_variant_id'] }}"
                                    data-product-price="{{ $volume['price'] }}"
                                    data-cart-product-label="{{ collect([$detail['brand'], $detail['name'], $volume['label'], $volume['price_label']])->filter()->implode(' · ') }}"
                                    data-selected="{{ $volume['selected'] ? 'true' : 'false' }}"
                                >
                                    {{ $volume['label'] }}
                                </button>
                            @endforeach
                        </div>

                        {{-- <label class="mt-2.5 flex h-[52px] items-center justify-between border border-[#E8DAD0] bg-[#FDFBF8] px-4">
                            <span class="flex items-center gap-3 text-[13px] leading-5 text-[#7A4751]"><span class="size-3.5 border border-[#E8DAD0]"></span> Повний флакон 100 мл</span>
                            <span class="font-cormorant text-[18px] font-medium leading-7 text-[#5B2730]">7 800 ₴</span>
                        </label> --}}
                    </div>

                    <div class="mt-4 grid grid-cols-[1fr_56px] gap-2">
                        <button class="h-[61px] bg-[#5B2730] text-[13px] font-medium uppercase leading-5 tracking-[1.56px] text-[#FDFBF8]" type="button" data-cart-add data-cart-add-url="{{ route('cart.add') }}" data-product-id="{{ $selectedCartProductId }}" data-product-price="{{ $selectedCartPrice }}" data-cart-product-label="{{ $selectedCartLabel }}">Додати в кошик</button>
                        <button class="h-[61px] border border-[#5B2730] text-[22px] leading-none text-[#5B2730]" type="button" aria-label="Додати в обране">♡</button>
                    </div>
                </div>

                <p class="m-0 max-w-[518px] py-7 text-[14px] leading-[22px] text-[#7A4751]">{{ $detail['description'] }}</p>

                <ol class="m-0 w-full list-none border-t border-[#EFE4D9] p-0">
                    @foreach ($detail['benefits'] as $benefit)
                        <li class="grid min-h-[51px] grid-cols-[32px_1fr] items-center gap-3">
                            <span class="font-cormorant text-[18px] italic leading-7 text-[#A85D66]">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-[13px] leading-5 text-[#7A4751]">{{ $benefit }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    <section class="bg-[#F8EDE7] px-[68px] py-[90px] max-lg:px-6 max-sm:px-5 max-sm:py-11">
        <div class="mx-auto max-w-[1304px]">
            <div class="mb-11 flex items-end justify-between gap-8 max-sm:mb-5 max-sm:block">
                <h2 class="m-0 font-cormorant text-[60px] font-medium leading-[63px] tracking-[-0.6px] text-[#5B2730] max-sm:text-[27px] max-sm:leading-[33px]">Ноти аромату</h2>
                <p class="m-0 pb-1 text-[12px] uppercase leading-[19px] tracking-[2.16px] text-[#5B2730] max-sm:mt-3 max-sm:text-[13px] max-sm:normal-case max-sm:leading-[148%] max-sm:tracking-normal max-sm:text-[#7A4751]">{{ $detail['description'] }}</p>
            </div>

            <div class="grid border-t border-[#E8DAD0] max-sm:border-t-0 max-sm:gap-5 md:grid-cols-3">
                @foreach ($detail['notes'] as $group)
                    <div class="min-h-[105px] border-[#E8DAD0] py-7 max-sm:min-h-0 max-sm:py-0 md:border-r md:px-8 md:first:pl-0 md:last:border-r-0 md:last:pr-0">
                        <h3 class="m-0 mb-3.5 text-[11px] font-semibold uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:mb-2.5 max-sm:text-[10px] max-sm:font-medium max-sm:leading-3 max-sm:tracking-[1.6px]">{{ $group['title'] }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($group['items'] as $note)
                                <span class="inline-flex h-[38px] items-center border border-[#E8DAD0] bg-[#FDFBF8] px-3.5 text-[13px] font-medium leading-5 tracking-[0.26px] text-[#5B2730] max-sm:h-[31px] max-sm:bg-white max-sm:px-[13px] max-sm:text-[12px] max-sm:font-normal max-sm:leading-[15px] max-sm:tracking-normal max-sm:text-[#7A4751]">{{ $note }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="px-[68px] py-[120px] max-lg:px-6 max-sm:bg-[#FDFBF8] max-sm:px-0 max-sm:py-12">
            <div class="mx-auto max-w-[1304px]">
                <div class="mb-11 flex items-end justify-between gap-8 max-sm:mb-[18px] max-sm:justify-center">
                    <h2 class="m-0 font-cormorant text-[60px] font-medium leading-[63px] tracking-[-0.6px] text-[#5B2730] max-sm:text-center max-sm:text-[27px] max-sm:leading-[33px]">Схожі аромати</h2>
                    <a class="hidden pb-1 text-[12px] uppercase leading-[19px] tracking-[2.16px] text-[#5B2730] sm:block" href="{{ route('catalog.index') }}">Усі аромати</a>
                </div>

                <div class="grid grid-cols-5 gap-[21px] max-xl:grid-cols-4 max-lg:grid-cols-3 max-sm:grid-cols-2 max-sm:gap-2">
                    @foreach ($relatedProducts as $item)
                        <article class="group flex min-h-[542px] flex-col max-sm:min-h-[285px] max-sm:border max-sm:border-[#E8DAD0] max-sm:bg-white">
                            <a class="relative flex h-[325px] items-center justify-center bg-white p-6 max-sm:h-[172px] max-sm:bg-[#FDFBF8] max-sm:px-[46px] max-sm:py-[22px]" href="{{ $item['url'] }}">
                                <span class="absolute inset-0 bg-[linear-gradient(143.13deg,rgba(176,127,110,0)_49.9%,rgba(176,127,110,0.16)_49.9%,rgba(176,127,110,0.16)_50.1%,rgba(176,127,110,0)_50.1%),linear-gradient(36.87deg,rgba(176,127,110,0)_49.9%,rgba(176,127,110,0.16)_49.9%,rgba(176,127,110,0.16)_50.1%,rgba(176,127,110,0)_50.1%)] opacity-0 transition group-hover:opacity-100"></span>
                                <img class="relative max-h-full max-w-full object-contain transition duration-300 group-hover:scale-[1.03] max-sm:max-h-32" src="{{ $item['image'] }}" alt="{{ $item['brand'] }} {{ $item['name'] }}">
                            </a>
                            <div class="flex flex-1 flex-col pt-3.5 max-sm:p-3 max-sm:pb-[13px]">
                                <p class="m-0 text-[12px] uppercase leading-[19px] tracking-[2.64px] text-[#A98088] max-sm:hidden">{{ $item['meta'] }}</p>
                                <p class="m-0 mt-3 text-[13px] font-medium leading-5 tracking-[0.52px] text-[#7A4751] max-sm:mt-1">{{ $item['brand'] }}</p>
                                <h3 class="m-0 mt-2 font-cormorant text-[22px] font-medium leading-[25px] text-[#5B2730] max-sm:mt-1 max-sm:min-h-10 max-sm:text-[16px] max-sm:leading-5">{{ $item['name'] }}</h3>
                                <p class="m-0 mt-2 text-[13px] leading-5 text-[#A98088] max-sm:hidden">{{ $item['notes'] }}</p>
                                <div class="mt-auto flex items-center justify-between pt-5 max-sm:pt-1">
                                    <p class="m-0 font-cormorant text-[22px] font-medium leading-[34px] text-[#5B2730] max-sm:font-sans max-sm:text-[13px] max-sm:leading-4">{{ $item['price_label'] }}</p>
                                    <button class="hidden size-[26px] items-center justify-center rounded-full border border-[#5B2730] text-[15px] leading-[18px] text-[#5B2730] max-sm:flex" type="button" aria-label="Додати в кошик" data-cart-add data-cart-add-url="{{ route('cart.add') }}" data-product-id="{{ $item['cart_product_id'] }}" data-product-price="{{ $item['cart_price'] }}" data-cart-product-label="{{ $item['cart_label'] }}">+</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="border-t border-[#EFE4D9] px-[68px] py-[105px] max-lg:px-6 max-sm:border-t-0 max-sm:px-5 max-sm:py-11">
        <div class="mx-auto grid max-w-[1304px] grid-cols-[320px_1fr] gap-[76px] max-lg:grid-cols-1 max-sm:gap-[18px]">
            <aside>
                <p class="m-0 mb-[18px] text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:font-cormorant max-sm:text-[27px] max-sm:normal-case max-sm:leading-[33px] max-sm:tracking-normal max-sm:text-[#5B2730]">Відгуки</p>
                <div class="flex items-center gap-[18px] max-sm:gap-4">
                    <div class="max-sm:flex max-sm:w-[65px] max-sm:flex-col max-sm:items-center max-sm:gap-0.5">
                        <span class="font-cormorant text-[64px] font-medium leading-none tracking-[-1.28px] text-[#5B2730] max-sm:text-[52px] max-sm:leading-[63px] max-sm:tracking-normal">{{ number_format($averageRating, 1) }}</span>
                        <p class="m-0 hidden text-[12px] leading-[15px] tracking-[0.5px] text-[#5B2730] max-sm:block">{{ $starsText((int) round($averageRating)) }}</p>
                        <p class="m-0 hidden text-[10px] leading-3 text-[#A98088] max-sm:block">{{ $reviewsTotal }} відгуків</p>
                    </div>
                    <div class="max-sm:hidden">
                        <p class="m-0 text-[16px] leading-[25px] tracking-[0.96px] text-[#A85D66]">{{ $starsText((int) round($averageRating)) }}</p>
                        <p class="m-0 text-[13px] leading-5 text-[#A98088]">На основі {{ $reviewsTotal }} відгуків</p>
                    </div>
                </div>
                <div class="my-5 grid gap-2 max-sm:ml-[81px] max-sm:-mt-[94px] max-sm:mb-0 max-sm:gap-1.5">
                    @foreach ([5, 4, 3, 2, 1] as $stars)
                        <div class="grid grid-cols-[36px_1fr_28px] items-center gap-3 text-[12px] leading-[19px] max-sm:grid-cols-[20px_1fr_14px] max-sm:gap-2 max-sm:text-[10px] max-sm:leading-3">
                            <span class="font-medium tracking-[1.2px] text-[#7A4751] max-sm:font-normal max-sm:tracking-normal max-sm:text-[#A98088]">{{ $stars }} ★</span>
                            <span class="h-1 bg-[#EFE4D9] max-sm:h-[5px] max-sm:rounded-[3px] max-sm:bg-[#FBF1ED]"><span class="block h-1 bg-[#A85D66] max-sm:h-[5px] max-sm:rounded-[3px] max-sm:bg-[#5B2730]" style="width: {{ $reviewPercent($stars) }}%"></span></span>
                            <span class="text-right text-[#A98088]">{{ $reviewBuckets[$stars] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
                <button class="h-[50px] w-full bg-[#5B2730] text-[13px] font-medium uppercase leading-5 tracking-[1.56px] text-[#FDFBF8] max-sm:hidden" type="button" data-review-open>Залишити відгук</button>
            </aside>

            <div class="border-t border-[#EFE4D9] max-sm:border-t-0">
                @forelse ($reviews as $review)
                    <article class="border-b border-[#EFE4D9] py-[22px] max-sm:mb-3 max-sm:border max-sm:border-[#E8DAD0] max-sm:p-4">
                        <p class="m-0 text-[14px] leading-[22px] tracking-[0.56px] text-[#A85D66] max-sm:text-[11px] max-sm:leading-[13px] max-sm:tracking-[0.5px] max-sm:text-[#5B2730]">{{ $starsText((int) $review->rating) }}</p>
                        <h3 class="m-0 mt-2 max-w-[572px] font-cormorant text-[20px] font-medium leading-7 text-[#5B2730] max-sm:text-[15px] max-sm:italic max-sm:leading-[140%] max-sm:text-[#7A4751]">{{ $review->content }}</h3>
                        <p class="m-0 mt-2 text-[12px] uppercase leading-[19px] tracking-[2.16px] text-[#A98088] max-sm:text-[10.5px] max-sm:normal-case max-sm:leading-[13px] max-sm:tracking-normal">
                            {{ $review->name }} · {{ $review->created_at?->locale(app()->getLocale())->isoFormat('D MMM') }}
                        </p>
                    </article>
                @empty
                    <div class="py-8">
                        <h3 class="m-0 font-cormorant text-[24px] font-medium leading-8 text-[#5B2730]">Поки що немає відгуків</h3>
                        <p class="m-0 mt-2 max-w-[520px] text-[14px] leading-[22px] text-[#7A4751]">Станьте першим, хто поділиться враженням про цей аромат.</p>
                    </div>
                @endforelse

                <div class="mt-2 flex items-center justify-between sm:hidden">
                    <button class="h-10 border border-[#5B2730] px-[22px] text-[11.5px] font-medium uppercase leading-[14px] tracking-[1.2px] text-[#5B2730]" type="button" data-review-open>Залишити відгук</button>
                    @if ($reviewsTotal > 0)
                        <span class="text-[11px] font-medium uppercase leading-[13px] tracking-[0.8px] text-[#7A4751]">Усі {{ $reviewsTotal }}</span>
                    @endif
                </div>

                @if ($reviews->hasPages())
                    <nav class="mt-6 flex items-center gap-2 text-[13px] font-medium uppercase leading-5 tracking-[1.4px] text-[#5B2730] max-sm:hidden" aria-label="Reviews pagination">
                        @if ($reviews->previousPageUrl())
                            <a class="border border-[#E8DAD0] px-4 py-2" href="{{ $reviews->previousPageUrl() }}">Назад</a>
                        @endif
                        @if ($reviews->nextPageUrl())
                            <a class="border border-[#5B2730] px-4 py-2" href="{{ $reviews->nextPageUrl() }}">Ще відгуки</a>
                        @endif
                    </nav>
                @endif
            </div>
        </div>
    </section>

    <div class="fixed inset-0 z-[90] hidden items-center justify-center bg-[rgba(42,31,25,0.34)] px-5" hidden data-review-modal>
        <button class="absolute inset-0 h-full w-full" type="button" aria-label="Закрити форму відгуку" data-review-close></button>

        <section class="relative w-full max-w-[540px] bg-white p-0 shadow-[0_24px_80px_-40px_rgba(42,31,25,0.7)]" aria-label="Форма відгуку">
            <div class="flex h-[61px] items-center justify-between border-b border-[#E8DAD0] px-5">
                <h2 class="m-0 font-cormorant text-[24px] font-medium leading-[29px] text-[#5B2730]">Залишити відгук</h2>
                <button class="text-[22px] leading-none text-[#7A4751]" type="button" aria-label="Закрити" data-review-close>×</button>
            </div>

            <form class="p-5" action="{{ route('sevia.product.reviews.store', ['product' => $product->slug]) }}" method="POST" data-review-form>
                @csrf
                <input type="text" name="hp" class="hidden" tabindex="-1" autocomplete="off">
                <input type="hidden" name="rating" value="5" data-review-rating>

                <div class="mb-5">
                    <p class="m-0 mb-2 text-[10px] font-medium uppercase leading-3 tracking-[1.6px] text-[#A98088]">Оцінка</p>
                    <div class="flex gap-2 text-[28px] leading-none text-[#A85D66]" data-review-stars>
                        @for ($i = 1; $i <= 5; $i++)
                            <button class="text-[#A85D66]" type="button" data-rating-value="{{ $i }}" aria-label="{{ $i }} з 5">★</button>
                        @endfor
                    </div>
                </div>

                <div class="grid gap-3">
                    <label>
                        <span class="mb-1.5 block text-[10px] font-medium uppercase leading-3 tracking-[1.6px] text-[#A98088]">Імʼя</span>
                        <input class="h-11 w-full border border-[#E8DAD0] bg-[#FDFBF8] px-3.5 text-[14px] leading-5 text-[#5B2730] outline-none placeholder:text-[#C9A9B0] focus:border-[#5B2730]" type="text" name="name" autocomplete="name" placeholder="Ваше імʼя" required>
                        <span class="mt-1 hidden text-[12px] leading-4 text-[#B03B45]" data-error-for="name"></span>
                    </label>

                    <label>
                        <span class="mb-1.5 block text-[10px] font-medium uppercase leading-3 tracking-[1.6px] text-[#A98088]">Email</span>
                        <input class="h-11 w-full border border-[#E8DAD0] bg-[#FDFBF8] px-3.5 text-[14px] leading-5 text-[#5B2730] outline-none placeholder:text-[#C9A9B0] focus:border-[#5B2730]" type="email" name="email" autocomplete="email" placeholder="email@example.com">
                        <span class="mt-1 hidden text-[12px] leading-4 text-[#B03B45]" data-error-for="email"></span>
                    </label>

                    <label>
                        <span class="mb-1.5 block text-[10px] font-medium uppercase leading-3 tracking-[1.6px] text-[#A98088]">Відгук</span>
                        <textarea class="min-h-[132px] w-full resize-none border border-[#E8DAD0] bg-[#FDFBF8] px-3.5 py-3 text-[14px] leading-[22px] text-[#5B2730] outline-none placeholder:text-[#C9A9B0] focus:border-[#5B2730]" name="content" placeholder="Поділіться враженням про аромат" required></textarea>
                        <span class="mt-1 hidden text-[12px] leading-4 text-[#B03B45]" data-error-for="content"></span>
                    </label>
                </div>

                <p class="m-0 mt-4 text-[12px] leading-[18px] text-[#A98088]">Відгук зʼявиться після модерації. Зазвичай це займає до 48 годин.</p>

                <div class="mt-5 grid grid-cols-[1fr_auto] items-center gap-4">
                    <p class="m-0 hidden text-[13px] leading-5 text-[#6BA070]" data-review-success>Дякуємо. Відгук відправлено на модерацію.</p>
                    <button class="h-[50px] bg-[#5B2730] px-8 text-[12px] font-medium uppercase leading-[15px] tracking-[1.2px] text-white disabled:opacity-60" type="submit" data-review-submit>Надіслати</button>
                </div>
            </form>
        </section>
    </div>
@endsection
