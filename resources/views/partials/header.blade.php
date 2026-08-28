@php
    $cartInfo = app(\App\Services\CartService::class)->info();
    $cartQty = (int) ($cartInfo['qty'] ?? 0);
    $headerClientUrl = auth()->check() ? route('account.overview') : route('auth.phone');
    $headerClientLabel = auth()->check() ? 'Особистий кабінет' : 'Увійти';
    $favoriteIds = auth()->check()
        ? auth()->user()->favorites()->pluck('bs_products.id')->map(fn ($id) => (int) $id)->all()
        : \App\Support\GuestFavoritesStore::idsFromRequest();
    $favoritesQty = count($favoriteIds);
    $favoritesUrl = route('account.favorites');
    $freeShippingFrom = max(0, (float) \App\Models\Setting::admin('cart.free_shipping_from', 0));
    $freeShippingLabel = number_format($freeShippingFrom, 0, '.', ' ') . ' ₴';
@endphp

<header class="sticky top-0 z-40 m-0 bg-white sm:max-lg:border-b sm:max-lg:border-[#7A4751]/10 sm:max-lg:bg-[#FDFBF8] lg:border-b lg:border-[#7A4751]/10 lg:bg-[#FDFBF8]" data-site-header>
    <div class="hidden bg-[#F8EDE7] sm:block">
        <div class="mx-auto grid min-h-[41px] w-full max-w-[1440px] grid-cols-[1fr_auto_1fr] items-center gap-7 px-[68px] text-[12px] uppercase leading-[19px] tracking-[2.16px] text-[#A98088] sm:max-lg:min-h-[28px] sm:max-lg:gap-5 sm:max-lg:px-[49px] sm:max-lg:text-[8.6px] sm:max-lg:leading-[13px] sm:max-lg:tracking-[1.55px]">
            <span>UA · UAH</span>
            @if ($freeShippingFrom > 0)
                <strong class="text-[13px] font-medium normal-case leading-[20px] tracking-[0.78px] text-[#7A4751] sm:max-lg:text-[9.4px] sm:max-lg:leading-[14px] sm:max-lg:tracking-[0.56px]">Безкоштовна доставка від {{ $freeShippingLabel }}</strong>
            @else
                <span></span>
            @endif
            <a class="justify-self-end" href="tel:+380630000000">+38 (063) 000 00 00</a>
        </div>
    </div>

    @if ($freeShippingFrom > 0)
        <div class="flex h-[33px] items-center justify-center bg-[#F8EDE7] px-4 py-2.5 sm:hidden">
            <strong class="text-[11px] font-medium leading-[13px] tracking-[0.3px] text-[#7A4751]">Безкоштовна доставка від {{ $freeShippingLabel }}</strong>
        </div>
    @endif

    <div class="hidden sm:mx-auto sm:grid sm:min-h-[99px] sm:w-full sm:max-w-[1440px] sm:grid-cols-[102px_minmax(0,1fr)_auto] sm:items-center sm:gap-x-[50px] sm:px-[68px] sm:max-lg:min-h-[71px] sm:max-lg:grid-cols-[73px_minmax(0,1fr)_auto] sm:max-lg:gap-x-7 sm:max-lg:px-[49px] xl:gap-x-[140px] min-[1400px]:gap-x-[260px]">
        <a class="block h-[98px] w-[102px] sm:max-lg:h-[70px] sm:max-lg:w-[73px]" href="{{ route('home') }}" aria-label="Sevia">
            <img class="block h-[98px] w-[102px] object-contain sm:max-lg:h-[70px] sm:max-lg:w-[73px]" src="{{ asset('vendor/frontend-sevia/images/logo.png') }}" alt="Sevia" width="102" height="98">
        </a>

        <nav class="flex items-center justify-start gap-[38px] text-[14px] leading-[22px] tracking-[0.56px] text-[#7A4751] sm:max-lg:gap-[22px] sm:max-lg:text-[10.1px] sm:max-lg:leading-4 sm:max-lg:tracking-[0.4px] [&>a:nth-child(1)]:order-1 [&>a:nth-child(n+3)]:order-2" aria-label="Primary navigation">
            <a class="order-1 hover:text-[#5B2730]" href="{{ route('sale.index') }}">Sale</a>
            <a class="hover:text-[#5B2730]" href="{{ route('catalog.index') }}">Каталог</a>
            <a class="whitespace-nowrap font-semibold hover:text-[#5B2730]" href="#discovery">Discovery 5×3</a>
            <a class="hover:text-[#5B2730]" href="#collections">Колекції</a>
        </nav>

        <div class="flex items-center justify-end gap-[22px] text-[14px] leading-[22px] tracking-[0.56px] text-[#7A4751] sm:max-lg:gap-3 sm:max-lg:text-[10.1px] sm:max-lg:leading-4 sm:max-lg:tracking-[0.4px]">
            <button class="whitespace-nowrap hover:text-[#5B2730]" type="button" data-search-open>Пошук</button>
            <a class="whitespace-nowrap hover:text-[#5B2730]" href="{{ $headerClientUrl }}">{{ $headerClientLabel }}</a>
            <a class="inline-flex items-center gap-1.5 whitespace-nowrap hover:text-[#5B2730]" href="{{ $favoritesUrl }}">
                <span>Обране</span>
                <b class="{{ $favoritesQty > 0 ? 'grid' : 'hidden' }} h-[18px] min-w-[18px] place-items-center rounded-full bg-[#A85D66] px-[5px] text-[10px] font-medium leading-none text-[#FDFBF8] sm:max-lg:h-[13px] sm:max-lg:min-w-[13px] sm:max-lg:px-[3px] sm:max-lg:text-[7.2px]" data-favorites-count>{{ $favoritesQty }}</b>
            </a>
            <a class="inline-flex items-center gap-1.5 whitespace-nowrap hover:text-[#5B2730]" href="{{ route('cart.page') }}" data-cart-link>
                <span>Кошик</span>
                <b class="{{ $cartQty > 0 ? 'grid' : 'hidden' }} h-[18px] min-w-[18px] place-items-center rounded-full bg-[#A85D66] px-[5px] text-[10px] font-medium leading-none text-[#FDFBF8] sm:max-lg:h-[13px] sm:max-lg:min-w-[13px] sm:max-lg:px-[3px] sm:max-lg:text-[7.2px]" data-cart-count>{{ $cartQty }}</b>
            </a>
        </div>
    </div>

    <div class="absolute right-14 top-24 z-[80] hidden h-[126px] w-[420px] border border-[#ECD4CD] bg-white shadow-[0_24px_60px_-34px_rgba(42,31,25,0.5)] max-lg:hidden" hidden data-cart-status>
        <div class="absolute left-[21px] top-[27px] grid size-[30px] place-items-center rounded-full bg-[#A85D66] text-[14px] leading-[22px] text-[#FDFBF8]" aria-hidden="true">✓</div>

        <div class="absolute left-[67px] right-[21px] top-[18px] grid gap-[3px]">
            <strong class="text-[15px] font-semibold leading-[23px] text-[#5B2730]">Додано в кошик</strong>
            <p class="m-0 truncate text-[13px] leading-5 text-[#A98088]" data-cart-status-line></p>
        </div>

        <a class="absolute left-[67px] top-[73px] inline-flex h-[34px] items-center justify-center text-[13px] font-medium uppercase leading-5 tracking-[2.34px] text-[#5B2730]" href="{{ route('cart.page') }}">
            Перейти в кошик →
        </a>

        <button class="absolute right-[11px] top-[9px] grid size-[26px] place-items-center text-[18px] leading-[18px] text-[#A98088]" type="button" aria-label="Закрити" data-cart-status-close>×</button>
    </div>

    <div class="hidden h-[57px] items-center justify-between bg-white px-4 py-[14px] max-sm:flex">
        <div class="flex h-6 w-16 items-center">
            <button class="block h-[22px] w-[22px]" type="button" aria-label="Open menu" aria-expanded="false" data-menu-toggle>
                <img class="block h-[22px] w-[22px]" src="{{ asset('vendor/frontend-sevia/images/burger.svg') }}" alt="">
            </button>
        </div>

        <a class="min-w-0 flex-1 text-center font-cormorant text-[24px] font-medium leading-[29px] tracking-[4px] text-[#5B2730]" href="{{ route('home') }}" aria-label="Sevia">
            SÉVIA
        </a>

        <div class="flex h-6 w-16 items-center justify-end gap-4">
            <a class="block size-5" href="{{ route('search.index') }}" aria-label="Пошук">
                <img class="block size-5" src="{{ asset('vendor/frontend-sevia/images/search.svg') }}" alt="">
            </a>
            <a class="relative block size-5" href="{{ route('cart.page') }}" data-cart-link aria-label="Кошик">
                <img class="block size-5" src="{{ asset('vendor/frontend-sevia/images/cart.svg') }}" alt="">
                <b class="{{ $cartQty > 0 ? 'grid' : 'hidden' }} absolute -right-2 -top-2 h-[16px] min-w-[16px] place-items-center rounded-full bg-[#A85D66] px-[4px] text-[9px] font-medium leading-none text-[#FDFBF8]" data-cart-count>{{ $cartQty }}</b>
            </a>
        </div>
    </div>

    <div class="hidden h-[42px] w-full items-center justify-between bg-[#5B2730] px-5 py-[13px] lg:hidden" hidden data-cart-status>
        <div class="flex h-4 items-center gap-[9px]">
            <svg class="size-4 shrink-0" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                <circle cx="8" cy="8" r="7" stroke="#FFFFFF" stroke-width="1.2"/>
                <path d="M5 8.1L7.1 10.2L11 6.2" stroke="#FFFFFF" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="text-[12px] font-medium uppercase leading-[15px] tracking-[0.8px] text-white">Додано в кошик</span>
        </div>

        <a class="text-[11px] font-medium uppercase leading-[13px] tracking-[0.8px] text-white" href="{{ route('cart.page') }}">
            Переглянути
        </a>
    </div>

    <div class="fixed inset-0 z-[70] hidden bg-[rgba(42,31,25,0.28)] lg:hidden" hidden data-mobile-menu>
        <div class="absolute inset-0" data-menu-close></div>

        <aside class="relative flex w-full max-w-[393px] flex-col items-start bg-white shadow-[18px_0_60px_-40px_rgba(42,31,25,0.45)]" aria-label="Mobile navigation">
            <div class="flex h-[63px] w-full items-center justify-between px-5 pb-4 pt-[18px]">
                <a class="font-cormorant text-[24px] font-medium leading-[29px] tracking-[4px] text-[#5B2730]" href="{{ route('home') }}" aria-label="Sevia">SÉVIA</a>
                <button class="w-4 text-[18px] leading-[22px] text-[#7A4751]" type="button" aria-label="Закрити меню" data-menu-close>×</button>
            </div>

            <form class="w-full px-5 pb-3" action="{{ route('search.index') }}" method="GET" role="search">
                <label class="flex h-10 w-full items-center gap-[9px] rounded-full border border-[#E8DAD0] bg-[#FDFBF8] px-3.5">
                    <img class="size-[15px]" src="{{ asset('vendor/frontend-sevia/images/search.svg') }}" alt="">
                    <input class="min-w-0 flex-1 bg-transparent text-[13px] leading-4 text-[#5B2730] outline-none placeholder:text-[#C9A9B0]" type="search" name="q" placeholder="Шукати аромат, бренд" autocomplete="off">
                </label>
            </form>

            <nav class="w-full" aria-label="Mobile menu links">
                <a class="flex h-[62px] w-full items-center justify-between border-t border-[#E8DAD0] px-5 py-[17px]" href="{{ route('catalog.index') }}">
                    <span class="font-cormorant text-[23px] font-medium leading-7 text-[#5B2730]">Каталог</span>
                    <span class="text-[16px] leading-[19px] text-[#A98088]">›</span>
                </a>
                <a class="flex h-[62px] w-full items-center justify-between border-t border-[#E8DAD0] px-5 py-[17px]" href="#discovery">
                    <span class="flex items-center gap-[9px]">
                        <span class="font-cormorant text-[23px] font-medium leading-7 text-[#5B2730]">Discovery 5×3</span>
                        <span class="bg-[#B08C57] px-1.5 py-0.5 text-[8px] font-medium uppercase leading-[10px] tracking-[0.4px] text-white">New</span>
                    </span>
                    <span class="text-[16px] leading-[19px] text-[#A98088]">›</span>
                </a>
                <a class="flex h-[62px] w-full items-center justify-between border-t border-[#E8DAD0] px-5 py-[17px]" href="#collections">
                    <span class="font-cormorant text-[23px] font-medium leading-7 text-[#5B2730]">Колекції</span>
                    <span class="text-[16px] leading-[19px] text-[#A98088]">›</span>
                </a>
                <a class="flex h-[62px] w-full items-center justify-between border-t border-[#E8DAD0] px-5 py-[17px]" href="{{ route('sale.index') }}">
                    <span class="flex items-center gap-[9px]">
                        <span class="font-cormorant text-[23px] font-medium leading-7 text-[#5B2730]">Sale</span>
                        <span class="bg-[#B03B45] px-1.5 py-0.5 text-[8px] font-medium uppercase leading-[10px] tracking-[0.4px] text-white">50%</span>
                    </span>
                    <span class="text-[16px] leading-[19px] text-[#A98088]">›</span>
                </a>
                <a class="flex h-[62px] w-full items-center justify-between border-y border-[#E8DAD0] px-5 py-[17px]" href="#about">
                    <span class="font-cormorant text-[23px] font-medium leading-7 text-[#5B2730]">Про нас</span>
                    <span class="text-[16px] leading-[19px] text-[#A98088]">›</span>
                </a>
            </nav>

            <div class="flex h-[46px] w-full items-start gap-5 px-5 pb-3.5 pt-[18px] text-[11.5px] font-medium uppercase leading-[14px] tracking-[0.6px] text-[#7A4751]">
                <a href="{{ $headerClientUrl }}">{{ $headerClientLabel }}</a>
                <a class="inline-flex items-center gap-1.5" href="{{ $favoritesUrl }}">Обране <b class="{{ $favoritesQty > 0 ? 'inline-flex' : 'hidden' }} min-w-4 items-center justify-center rounded-full bg-[#A85D66] px-1 text-[9px] leading-4 text-white" data-favorites-count>{{ $favoritesQty }}</b></a>
                <a href="{{ route('cart.page') }}" data-cart-link>Кошик</a>
            </div>

            <div class="flex h-[68px] w-full flex-col items-start gap-1.5 px-5 pb-7 pt-2 text-[11px] leading-[13px] text-[#A98088]">
                <p class="m-0">UA · UAH · +38 (063) 000 00 00</p>
                <p class="m-0">Instagram · Telegram · hellosevia.ua</p>
            </div>
        </aside>
    </div>

    <div class="fixed inset-0 z-[80] hidden" hidden data-search-overlay>
        <div class="absolute inset-0 bg-[rgba(42,31,25,0.28)] backdrop-blur-[0.75px]" data-search-close></div>

        <section class="absolute left-0 right-0 top-[140px] bg-[#FDFBF8] px-[68px] pb-16 pt-[52px] shadow-[0_32px_80px_-52px_rgba(42,31,25,0.5)]">
            <div class="mx-auto w-full max-w-[1304px]">
                <form class="flex h-[83px] items-center gap-4 border-b border-[#5B2730] px-0.5 pb-[18px] pt-1.5" action="{{ route('search.index') }}" method="GET" role="search">
                    <span class="flex h-10 w-4 items-center text-[26px] leading-10 text-[#5B2730]" aria-hidden="true">⌕</span>
                    <input class="min-w-0 flex-1 bg-transparent font-cormorant text-[48px] leading-[58px] tracking-[0.48px] text-[#5B2730] outline-none placeholder:text-[#5B2730]" type="search" name="q" value="{{ request('q') }}" placeholder="Пошук" autocomplete="off" data-search-input>
                    <span class="mx-2 h-11 w-px bg-[#A85D66]" aria-hidden="true"></span>
                    <button class="h-[19px] w-[86px] text-center text-[12px] uppercase leading-[19px] tracking-[2.4px] text-[#A98088]" type="button" data-search-close>Закрити ×</button>
                </form>

                <div class="grid min-h-[386px] grid-cols-[522px_minmax(0,1fr)] gap-[75px] pt-12">
                    <div class="flex flex-col gap-9">
                        <section class="flex flex-col gap-[18px]">
                            <h2 class="m-0 text-[12px] font-bold uppercase leading-[19px] tracking-[2.64px] text-[#A98088]">Нещодавні</h2>
                            <div class="grid">
                                @foreach (['Lady Million', 'decant 3 мл', 'Paco Rabanne', 'подарунковий сет'] as $recent)
                                    <a class="flex h-12 items-center gap-3 border-b border-[#EFE4D9] py-[11px] text-[16px] leading-[25px] text-[#7A4751]" href="{{ route('search.index', ['q' => $recent]) }}">
                                        <span class="w-3 text-[14px] leading-[22px] tracking-[-0.154px] text-[#A98088]">↺</span>
                                        <span>{{ $recent }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </section>

                        <section class="flex flex-col gap-[18px]">
                            <h2 class="m-0 text-[12px] font-bold uppercase leading-[19px] tracking-[2.64px] text-[#A98088]">Популярне</h2>
                            <div class="flex max-w-[522px] flex-wrap gap-x-2 gap-y-2">
                                @foreach (['Бестселери', 'Discovery 5×3', 'Miss Dior', 'Жіночі квіткові', 'Sale', 'Чоловічі'] as $tag)
                                    <a class="inline-flex h-[36px] items-center border border-[#E8DAD0] bg-[#F8EDE7] px-3.5 text-[13px] font-medium leading-5 text-[#5B2730]" href="{{ route('search.index', ['q' => $tag]) }}">{{ $tag }}</a>
                                @endforeach
                            </div>
                        </section>
                    </div>

                    <section class="flex flex-col gap-[18px]">
                        <h2 class="m-0 flex items-center gap-1.5 text-[12px] font-bold uppercase leading-[19px] tracking-[2.64px] text-[#A98088]">
                            <span>Збіги</span>
                            <span class="text-[#A85D66]">3</span>
                        </h2>
                        <div class="grid">
                            @foreach ([
                                ['brand' => 'Paco Rabanne', 'name' => 'Lady Million', 'notes' => 'Неролі · Малина · Мед', 'price' => '580 ₴', 'image' => 'bestseller-1.png'],
                                ['brand' => 'Paco Rabanne', 'name' => '1 Million', 'notes' => 'Грейпфрут · Кориця · Шкіра', 'price' => '560 ₴', 'image' => 'bestseller-4.png'],
                                ['brand' => 'Paco Rabanne', 'name' => 'Invictus', 'notes' => 'Грейпфрут · Морська нота · Гваяк', 'price' => '540 ₴', 'image' => 'bestseller-5.png'],
                            ] as $match)
                                <a class="grid h-[101px] grid-cols-[56px_minmax(0,1fr)_auto] items-center gap-[18px] border-b border-[#EFE4D9] py-[15px]" href="{{ route('search.index', ['q' => $match['brand'] . ' ' . $match['name']]) }}">
                                    <span class="flex h-[70px] w-14 items-center justify-center border border-[#EFE4D9] bg-gradient-to-br from-[#F8EDE7] to-[#F3E3DF]">
                                        <img class="h-[68px] w-[54px] object-contain" src="{{ asset('vendor/frontend-sevia/images/' . $match['image']) }}" alt="{{ $match['brand'] }} {{ $match['name'] }}">
                                    </span>
                                    <span class="grid min-w-0 gap-0.5">
                                        <span class="truncate text-[12px] leading-[19px] tracking-[0.48px] text-[#A98088]">{{ $match['brand'] }}</span>
                                        <span class="truncate font-cormorant text-[20px] font-medium leading-[31px] text-[#5B2730]">{{ $match['name'] }}</span>
                                        <span class="truncate text-[12px] leading-[19px] text-[#A98088]">{{ $match['notes'] }}</span>
                                    </span>
                                    <span class="font-cormorant text-[19px] leading-[29px] text-[#5B2730]">{{ $match['price'] }}</span>
                                </a>
                            @endforeach
                        </div>
                        <a class="w-fit border-b border-[#5B2730] pb-1 text-[13px] leading-5 tracking-[0.52px] text-[#5B2730]" href="{{ route('search.index', ['q' => 'paco rabanne']) }}">Показати всі результати за «paco rabanne» →</a>
                    </section>
                </div>
            </div>
        </section>
    </div>
</header>
