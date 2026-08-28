@extends('front.sevia::layouts.app')

@section('title', 'Обране | Sevia')

@section('content')
    <div class="bg-white text-[#5B2730]">
        <nav class="mx-auto flex h-[50px] max-w-[1440px] items-center gap-2.5 px-[68px] pb-2 pt-[22px] text-[13px] leading-5 text-[#7A4751] max-lg:px-6 max-sm:hidden" aria-label="Breadcrumb">
            <a class="no-underline hover:text-[#5B2730]" href="{{ route('home') }}">Sevia</a>
            <span class="text-[#E8DAD0]">/</span>
            <a class="no-underline hover:text-[#5B2730]" href="{{ route('account.overview') }}">Особистий кабінет</a>
            <span class="text-[#E8DAD0]">/</span>
            <strong class="font-normal text-[#5B2730]">Обране</strong>
        </nav>

        <header class="mx-auto max-w-[1440px] px-[68px] pb-[35px] pt-[30px] max-lg:px-6 max-sm:flex-col max-sm:items-start max-sm:px-5 max-sm:pb-2 max-sm:pt-4 {{ $favoriteProducts->isEmpty() ? 'hidden max-sm:flex' : '' }}">
            <div class="flex items-center gap-2 text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:text-[11px] max-sm:leading-[13px] max-sm:tracking-[0.8px]"><a class="hidden text-[18px] font-normal leading-none text-[#5B2730] no-underline max-sm:inline-flex" href="{{ url()->previous() !== url()->current() ? url()->previous() : route('catalog.index') }}" aria-label="Назад">←</a><span>Особистий кабінет</span></div>
            <h1 class="m-0 mt-2 self-start font-cormorant text-[64px] font-medium leading-none tracking-[-0.96px] text-[#5B2730] max-sm:mt-[12px] max-sm:self-center max-sm:text-[32px] max-sm:leading-[39px] max-sm:tracking-normal">Обране</h1>
        </header>

        <section class="mx-auto max-w-[1440px] px-[68px] pb-[91px] {{ $favoriteProducts->isEmpty() ? 'pt-0' : 'pt-14' }} max-lg:px-6 max-sm:px-5 max-sm:pb-12 max-sm:pt-2 {{ $client ? 'grid grid-cols-[240px_minmax(0,1fr)] gap-[60px] max-lg:grid-cols-[220px_minmax(0,1fr)] max-lg:gap-8 max-sm:block' : '' }}">
            <aside class="self-start max-sm:hidden {{ $client ? '' : 'hidden' }} {{ $favoriteProducts->isEmpty() ? 'pt-14' : '' }}" aria-label="Account navigation">
                <ul class="m-0 list-none divide-y divide-[#EFE4D9] border-y border-[#EFE4D9] p-0">
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.overview') }}"><span class="font-cormorant text-[16px] text-[#A85D66]">01</span><span class="text-[14px]">Огляд</span></a></li>
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.orders') }}"><span class="font-cormorant text-[16px] text-[#A85D66]">02</span><span class="text-[14px]">Замовлення</span></a></li>
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#5B2730] no-underline" href="{{ route('account.favorites') }}"><span class="font-cormorant text-[16px] font-medium text-[#5B2730]">03</span><span class="text-[14px] font-semibold">Обране</span></a></li>
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.profile') }}"><span class="font-cormorant text-[16px] text-[#A85D66]">05</span><span class="text-[14px]">Особисті дані</span></a></li>
                </ul>
                <form method="POST" action="{{ route('account.logout') }}">
                    @csrf
                    <button class="mt-4 border-0 bg-transparent p-0 text-left text-[12px] uppercase leading-[19px] tracking-[2.16px] text-[#7A4751]" type="submit">← Вийти</button>
                </form>
            </aside>

            <div class="min-w-0">
                @if ($favoriteProducts->isNotEmpty())
                    <div class="mb-5 flex items-end justify-between border-b border-[#EFE4D9] pb-3">
                        <h2 class="m-0 font-cormorant text-[28px] font-medium leading-[43px] tracking-[-0.28px] text-[#5B2730] max-sm:text-[22px] max-sm:leading-7">{{ $favoritesCount }} товарів</h2>
                    </div>
                @endif

                @if ($favoriteProducts->isEmpty())
                    <section class="mx-auto flex min-h-[746px] w-full max-w-[900px] flex-col items-center px-0 py-[56px] text-center max-sm:min-h-[370px] max-sm:h-[370px] max-sm:max-w-none max-sm:gap-4 max-sm:px-9 max-sm:py-14">
                        <div class="contents max-sm:hidden">
                        <img class="h-[161px] w-[166px] object-contain" src="{{ asset('vendor/frontend-sevia/images/logo.png') }}" alt="Sevia" width="166" height="161">
                        <p class="m-0 mt-0 border-b border-transparent pb-3.5 text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088]">Обране порожнє</p>
                        <h2 class="m-0 max-w-[860px] pb-[18px] font-cormorant text-[64px] font-medium leading-[64px] tracking-[-0.96px] text-[#5B2730] max-sm:text-[34px] max-sm:leading-9 max-sm:tracking-[-0.3px]">Тут зберігаються аромати, до яких<br class="max-sm:hidden"> хочеться повернутись.</h2>
                        <p class="m-0 max-w-[483px] pb-10 text-[16px] leading-[25px] text-[#7A4751] max-sm:text-[14px] max-sm:leading-5">Натискай ♥ на картці товару — і він зʼявиться тут. Зручно тримати поруч те, що ще обмірковуєш, поки не визрів вибір.</p>
                        <div class="flex flex-wrap items-start justify-center gap-3">
                            <a class="inline-flex h-[50px] w-[160px] items-center justify-center bg-[#5B2730] px-7 text-[13px] font-medium uppercase leading-5 tracking-[1.56px] text-[#FDFBF8] no-underline" href="{{ route('catalog.index') }}">До каталогу</a>
                            <a class="inline-flex h-[50px] w-[221px] items-center justify-center px-0 text-[13px] font-medium uppercase leading-5 tracking-[2.34px] text-[#5B2730] no-underline" href="{{ route('catalog.index') }}">Зібрати Discovery 53 →</a>
                        </div>
                        </div>
                        <div class="hidden w-full flex-col items-center gap-4 max-sm:flex">
                            <div class="flex size-20 items-center justify-center" aria-hidden="true">
                                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="80" height="80" rx="40" fill="#FBF1ED"/>
                                    <path d="M40 50C40 50 28 44 28 35.5C28 31.9 30.8 29 34.2 29C36.6 29 38.8 30.4 40 32.6C41.2 30.4 43.4 29 45.8 29C49.2 29 52 31.9 52 35.5C52 44 40 50 40 50Z" stroke="#5B2730" stroke-width="1.5" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <h2 class="m-0 text-center font-cormorant text-[24px] font-medium leading-[29px] text-[#5B2730]">Тут зберігаються аромати</h2>
                            <p class="m-0 w-full max-w-[321px] text-center text-[13px] leading-5 text-[#7A4751]">Додавай у обране те, до чого хочеться повертатися серцем на картці товару.</p>
                            <a class="inline-flex h-[45px] w-[159px] items-center justify-center bg-[#5B2730] px-[30px] py-[15px] font-[Inter] text-[12px] font-medium uppercase leading-[15px] tracking-[1.4px] text-[#FFFFFF] no-underline" href="{{ route('catalog.index') }}">До каталогу</a>
                        </div>
                    </section>
                @else
                    <div class="w-full max-w-[1032px] grid grid-cols-3 gap-x-8 gap-y-[58px] max-md:grid-cols-2 max-sm:gap-x-2 max-sm:gap-y-3">
                        @foreach ($favoriteProducts as $product)
                            @php($volumes = collect($product['volumes'] ?? []))
                            @php($volume = $volumes->first())
                            <article class="group flex min-h-[430px] min-w-0 flex-col max-sm:min-h-[314px] max-sm:border max-sm:border-[#E8DAD0] max-sm:bg-white {{ $product['available'] ? '' : 'opacity-70' }}" data-product-card>
                                <div class="relative">
                                    <a class="relative flex h-[286px] items-center justify-center overflow-hidden bg-white p-6 max-sm:h-[182px] max-sm:bg-[#FDFBF8] max-sm:p-6" href="{{ $product['url'] }}">
                                        @if (! $product['available'])
                                            <span class="absolute left-3.5 top-3.5 z-10 border border-[rgba(91,39,48,0.16)] bg-white/95 px-[9px] py-1 text-[9.5px] font-semibold uppercase leading-[15px] tracking-[1.33px] text-[#5B2730]">Немає</span>
                                        @endif
                                        @if ($product['image'])
                                            <img class="h-full w-full object-contain" src="{{ $product['image'] }}" alt="{{ $product['brand'] }} {{ $product['name'] }}" loading="lazy">
                                        @endif
                                    </a>
                                    <button class="absolute right-3 top-3 grid size-7 place-items-center rounded-full border border-[#E8DAD0] bg-[#FDFBF8] text-[14px] leading-[22px] text-[#5B2730]" type="button" aria-label="Видалити з обраного" data-favorite-toggle data-favorite-remove data-favorite-url="{{ route('favorites.toggle') }}" data-product-id="{{ $product['id'] }}">×</button>
                                </div>
                                <p class="m-0 mt-1.5 truncate text-[12px] uppercase leading-[19px] tracking-[2.64px] text-[#A98088]">{{ $product['meta'] ?: 'Нішеві' }} · {{ $volume['label'] ?? $product['unit'] ?? '5 мл' }}</p>
                                <p class="m-0 mt-1 text-[12px] font-semibold uppercase leading-[19px] tracking-[0.5px] text-[#5B2730]">{{ $product['brand'] }}</p>
                                <h3 class="m-0 min-h-[46px] font-cormorant text-[21px] font-medium leading-6 text-[#5B2730]">{{ $product['name'] }}</h3>
                                <p class="m-0 min-h-[38px] text-[12px] font-light leading-[18px] text-[#7A4751]">{{ $product['ingredients'] ?? '' }}</p>
                                @if ($volumes->isNotEmpty())
                                    <div class="mt-3 flex flex-wrap gap-1" role="group" aria-label="Volume">
                                        @foreach ($volumes as $option)
                                            <button class="inline-flex h-10 min-w-[46px] flex-col items-center justify-center border border-[#D8C4BE] px-1 text-[#7A4751] transition-colors hover:border-[#5B2730] hover:text-[#5B2730] {{ $loop->first ? 'border-[#5B2730] text-[#5B2730]' : '' }}" type="button" data-volume-option data-volume-id="{{ $option['id'] }}" data-volume-label="{{ $option['label'] }}" data-price-label="{{ $option['price_label'] }}" data-product-id="{{ $option['id'] }}" data-product-price="{{ $option['price'] }}" data-cart-product-label="{{ $product['brand'] }} {{ $product['name'] }} / {{ $option['label'] }}"><span class="text-[9px] font-medium leading-3">{{ $option['label'] }}</span><span class="text-[9px] font-medium leading-3">{{ $option['price_label'] }}</span></button>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="mt-auto flex h-[52px] items-center justify-between border-t border-[#EFE4D9] pt-3">
                                    <span class="text-[15px] font-semibold leading-5 text-[#5B2730]" data-product-price-display>{{ $volume['price_label'] ?? $product['price_label'] }}</span>
                                    @if ($product['available'])
                                        <button class="border-0 bg-transparent px-0 text-[11px] font-medium uppercase leading-[17px] tracking-[1.98px] text-[#5B2730]" type="button" data-cart-add data-cart-add-url="{{ route('cart.add') }}" data-product-id="{{ $volume['id'] ?? $product['cart_product_id'] ?? '' }}" data-product-price="{{ $volume['price'] ?? $product['price'] ?? 0 }}" data-volume-label="{{ $volume['label'] ?? $product['unit'] }}" data-cart-product-label="{{ $product['brand'] }} {{ $product['name'] }} / {{ $volume['label'] ?? $product['unit'] }}">+ В кошик</button>
                                    @else
                                        <button class="border-0 bg-transparent px-0 text-[11px] font-medium uppercase leading-[17px] tracking-[1.98px] text-[#5B2730]" type="button">Сповістити</button>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection
