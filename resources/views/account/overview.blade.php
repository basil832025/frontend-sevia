@extends('front.sevia::layouts.app')

@section('title', 'Особистий кабінет | Sevia')
@section('meta_description', 'Особистий кабінет Sevia')

@section('content')
    <div class="bg-white text-[#5B2730]">
        <nav class="mx-auto flex h-[50px] max-w-[1440px] items-center gap-2.5 px-[68px] pb-2 pt-[22px] text-[13px] leading-5 text-[#7A4751] max-lg:px-6 max-sm:hidden" aria-label="Breadcrumb">
            <a class="hover:text-[#5B2730]" href="{{ route('home') }}">Sevia</a>
            <span class="text-[#E8DAD0]">/</span>
            <strong class="font-normal text-[#5B2730]">Особистий кабінет</strong>
        </nav>

        <header class="mx-auto grid max-w-[1440px] grid-cols-[minmax(0,1fr)_180px] items-center gap-8 px-[68px] pb-[35px] pt-[30px] max-lg:grid-cols-1 max-lg:px-6 max-lg:pb-6 max-lg:pt-8 max-sm:flex max-sm:flex-col max-sm:items-stretch max-sm:gap-3.5 max-sm:px-5 max-sm:pb-3.5 max-sm:pt-[18px]">
            <div>
                <div class="text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:text-[10px] max-sm:leading-3 max-sm:tracking-[1.6px]">Особистий кабінет</div>
                <h1 class="m-0 mt-2 font-cormorant text-[64px] font-medium leading-none tracking-[-0.96px] text-[#5B2730] max-lg:text-[42px] max-sm:mt-1 max-sm:text-[32px] max-sm:leading-[39px] max-sm:tracking-normal">Доброго дня, {{ $firstName }}</h1>
            </div>

            <aside class="flex flex-col items-end gap-1 text-right max-lg:items-start max-lg:text-left max-sm:grid max-sm:min-h-[82px] max-sm:w-full max-sm:grid-cols-[minmax(0,1fr)_auto] max-sm:items-center max-sm:bg-[#5B2730] max-sm:px-[18px] max-sm:py-4 max-sm:text-left" aria-label="Бонусний рахунок">
                <span class="text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:text-[9.5px] max-sm:leading-[11px] max-sm:tracking-[1.2px] max-sm:text-[#D9B8BD]">Бонусний рахунок</span>
                <strong class="font-cormorant text-[36px] font-medium leading-none tracking-[-0.36px] text-[#5B2730] max-sm:col-start-1 max-sm:text-[30px] max-sm:leading-9 max-sm:text-white">{{ number_format((int) $bonusBalance, 0, '.', ' ') }} ₴</strong>
                <a class="text-[12px] font-medium uppercase leading-[19px] tracking-[2.16px] text-[#5B2730] no-underline max-sm:col-start-2 max-sm:row-span-2 max-sm:row-start-1 max-sm:text-[10.5px] max-sm:leading-[13px] max-sm:tracking-[0.6px] max-sm:text-white" href="#">Як використати →</a>
            </aside>
        </header>

        <section class="mx-auto grid max-w-[1440px] grid-cols-[240px_minmax(0,1fr)] gap-[60px] px-[68px] pb-[88px] pt-14 max-lg:grid-cols-1 max-lg:gap-7 max-lg:px-6 max-lg:pt-0 max-sm:block max-sm:px-5 max-sm:pb-0">
            <aside class="self-start max-sm:hidden" aria-label="Account navigation">
                <ul class="m-0 list-none divide-y divide-[#EFE4D9] border-y border-[#EFE4D9] p-0">
                    <li>
                        <a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)_auto] items-center gap-2.5 py-3 text-[#5B2730] no-underline" href="{{ route('account.overview') }}">
                            <span class="font-cormorant text-[16px] font-medium leading-[25px] tracking-[0.14px] text-[#5B2730]">01</span>
                            <span class="text-[14px] font-semibold leading-[22px] tracking-[0.14px]">Огляд</span>
                        </a>
                    </li>
                    <li>
                        <a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)_auto] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.orders') }}">
                            <span class="font-cormorant text-[16px] font-medium leading-[25px] tracking-[0.14px] text-[#A85D66]">02</span>
                            <span class="text-[14px] leading-[22px] tracking-[0.14px]">Замовлення</span>
                            <span class="text-[11px] uppercase leading-[17px] tracking-[1.98px] text-[#A98088]">{{ $ordersCount }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)_auto] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.favorites') }}">
                            <span class="font-cormorant text-[16px] font-medium leading-[25px] tracking-[0.14px] text-[#A85D66]">03</span>
                            <span class="text-[14px] leading-[22px] tracking-[0.14px]">Обране</span>
                            <span class="text-[11px] uppercase leading-[17px] tracking-[1.98px] text-[#A98088]">{{ $favoritesCount }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)_auto] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.profile') }}">
                            <span class="font-cormorant text-[16px] font-medium leading-[25px] tracking-[0.14px] text-[#A85D66]">04</span>
                            <span class="text-[14px] leading-[22px] tracking-[0.14px]">Особисті дані</span>
                        </a>
                    </li>
                </ul>
                <form method="POST" action="{{ route('account.logout') }}">
                    @csrf
                    <button class="mt-4 border-0 bg-transparent p-0 text-left text-[12px] uppercase leading-[19px] tracking-[2.16px] text-[#7A4751]" type="submit">Вийти</button>
                </form>
            </aside>

            <div class="flex min-w-0 flex-col gap-9 max-lg:gap-7 max-sm:gap-3.5">
                <article class="grid min-h-[200px] grid-cols-[minmax(0,412px)_minmax(260px,1fr)] items-center gap-9 bg-[#F8EDE7] p-9 max-lg:grid-cols-1 max-lg:p-6 max-sm:hidden">
                    <div>
                        <div class="text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088]">{{ $trackingOrder['status_line'] }}</div>
                        <h2 class="m-0 mt-0.5 font-cormorant text-[26px] font-medium leading-10 tracking-[-0.26px] text-[#5B2730]">Замовлення {{ $trackingOrder['code'] }}</h2>
                        <p class="m-0 text-[13px] leading-5 text-[#7A4751]">{{ $trackingOrder['items'] }}</p>
                        @if ($trackingOrder['ttn'] !== '')
                            <a class="mt-[18px] inline-flex text-[13px] font-medium uppercase leading-5 tracking-[2.34px] text-[#5B2730] no-underline" href="https://novaposhta.ua/tracking/{{ preg_replace('/\D+/', '', $trackingOrder['ttn']) }}" target="_blank" rel="noopener noreferrer">ТТН {{ $trackingOrder['ttn'] }}</a>
                        @endif
                    </div>

                    <div class="flex flex-col items-end gap-3">
                        @foreach ($trackingOrder['steps'] as $step)
                            <span class="inline-flex items-center gap-1.5 text-[12px] leading-[19px] tracking-[0.48px] text-[#7A4751]">
                                <span class="size-2 rounded-full {{ $step['done'] ? 'bg-[#A85D66]' : 'bg-[#E8DAD0]' }}"></span>
                                {{ $step['label'] }}
                            </span>
                        @endforeach
                    </div>
                </article>

                <section class="grid grid-cols-4 border-y border-[#E8DAD0] max-lg:grid-cols-2 max-sm:grid-cols-2 max-sm:gap-3 max-sm:border-0 max-sm:pb-2.5">
                    <div class="border-r border-[#E8DAD0] px-6 py-[22px] max-sm:min-h-[79px] max-sm:border max-sm:p-4">
                        <div class="font-cormorant text-[36px] font-medium leading-none tracking-[-0.36px] text-[#5B2730] max-sm:text-[26px] max-sm:leading-[31px]">{{ $ordersCount }}</div>
                        <div class="mt-1.5 text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088] max-sm:text-[10.5px] max-sm:font-normal max-sm:leading-[13px] max-sm:tracking-[0.6px]">Замовлень</div>
                    </div>
                    <div class="border-r border-[#E8DAD0] px-6 py-[22px] max-lg:border-r-0 max-sm:min-h-[79px] max-sm:border max-sm:p-4">
                        <div class="font-cormorant text-[36px] font-medium leading-none tracking-[-0.36px] text-[#5B2730] max-sm:text-[26px] max-sm:leading-[31px]">{{ $favoritesCount }}</div>
                        <div class="mt-1.5 text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088] max-sm:text-[10.5px] max-sm:font-normal max-sm:leading-[13px] max-sm:tracking-[0.6px]">У обраному</div>
                    </div>
                    <div class="border-r border-[#E8DAD0] px-6 py-[22px] max-sm:min-h-[79px] max-sm:border max-sm:p-4">
                        <div class="font-cormorant text-[36px] font-medium leading-none tracking-[-0.36px] text-[#5B2730] max-sm:text-[26px] max-sm:leading-[31px]">{{ number_format((int) $bonusBalance, 0, '.', ' ') }} ₴</div>
                        <div class="mt-1.5 text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088] max-sm:text-[10.5px] max-sm:font-normal max-sm:leading-[13px] max-sm:tracking-[0.6px]">Бонусів</div>
                    </div>
                    <div class="px-6 py-[22px] max-sm:min-h-[79px] max-sm:border max-sm:border-[#E8DAD0] max-sm:p-4">
                        <div class="font-cormorant text-[36px] font-medium leading-none tracking-[-0.36px] text-[#5B2730] max-sm:text-[26px] max-sm:leading-[31px]">{{ $discoveryCount }}</div>
                        <div class="mt-1.5 text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088] max-sm:text-[10.5px] max-sm:font-normal max-sm:leading-[13px] max-sm:tracking-[0.6px]">Discovery 5×3</div>
                    </div>
                </section>

                <section class="flex flex-col gap-5 max-sm:gap-3 max-sm:py-[14px] max-sm:pt-2.5">
                    <div class="flex items-end justify-between border-b border-[#EFE4D9] pb-3 max-sm:items-baseline max-sm:border-0 max-sm:pb-0">
                        <h2 class="m-0 font-cormorant text-[28px] font-medium leading-[43px] tracking-[-0.28px] text-[#5B2730] max-sm:text-[21px] max-sm:leading-[25px] max-sm:tracking-normal">Останні замовлення</h2>
                        <a class="text-[12px] font-medium uppercase leading-[19px] tracking-[2.16px] text-[#5B2730] no-underline max-sm:text-[11px] max-sm:leading-[13px] max-sm:tracking-[0.6px] max-sm:text-[#A98088]" href="#">Усі →</a>
                    </div>

                    <div class="flex flex-col">
                        @foreach ($recentOrders as $order)
                            <article class="grid min-h-[79px] grid-cols-[180px_minmax(0,1fr)_auto_76px_70px] items-center gap-6 border-b border-[#EFE4D9] max-lg:grid-cols-[1fr_auto] max-lg:gap-x-3.5 max-lg:gap-y-2 max-lg:py-3.5 max-sm:mb-3 max-sm:min-h-[119px] max-sm:grid-cols-[minmax(0,1fr)_auto] max-sm:gap-x-3 max-sm:gap-y-1.5 max-sm:border max-sm:border-[#E8DAD0] max-sm:px-4 max-sm:py-3.5 {{ $loop->iteration > 2 ? 'max-sm:hidden' : '' }}">
                                <div class="max-sm:row-span-2">
                                    <div class="text-[14px] font-semibold leading-[22px] tracking-[0.28px] text-[#5B2730] max-sm:text-[13px] max-sm:font-medium max-sm:leading-4 max-sm:tracking-normal">{{ $order['code'] }}</div>
                                    <div class="text-[12px] leading-[19px] tracking-[0.48px] text-[#A98088] max-sm:mt-1.5 max-sm:text-[10.5px] max-sm:leading-[13px] max-sm:tracking-normal">{{ $order['date'] }}</div>
                                </div>
                                <div class="min-w-0 truncate text-[13px] leading-5 text-[#7A4751] max-lg:col-span-2 max-sm:col-span-2 max-sm:text-[12px] max-sm:leading-[15px]">{{ $order['items'] }}</div>
                                <span class="inline-flex justify-center bg-[#F8EDE7] px-2.5 py-[5px] text-[11px] font-semibold uppercase leading-[17px] tracking-[1.98px] text-[#A98088] max-sm:col-start-2 max-sm:row-start-1 max-sm:justify-self-end max-sm:px-[9px] max-sm:py-1 max-sm:text-[9px] max-sm:font-medium max-sm:leading-[11px] max-sm:tracking-[0.5px] max-sm:text-[#7A4751] {{ $loop->first ? 'bg-[#E7CDCA] text-[#5B2730] max-sm:bg-[#F8EDE7] max-sm:text-[#7A4751]' : '' }}">{{ $order['status'] }}</span>
                                <div class="font-cormorant text-[20px] font-medium leading-[31px] text-[#5B2730] max-sm:col-start-1 max-sm:mt-1 max-sm:text-[18px] max-sm:leading-[22px]">{{ $order['total'] }}</div>
                                <a class="text-[12px] font-medium uppercase leading-[19px] tracking-[2.16px] text-[#5B2730] no-underline max-sm:col-start-2 max-sm:justify-self-end max-sm:text-[11px] max-sm:leading-[13px] max-sm:tracking-[0.6px] max-sm:text-[#7A4751]" href="#">Деталі →</a>
                            </article>
                        @endforeach
                    </div>
                </section>

                <nav class="-mx-5 mt-2 hidden flex-col py-2 max-sm:flex" aria-label="Account mobile navigation">
                    <a class="flex min-h-[49px] items-center justify-between border-t border-[#E8DAD0] px-5 py-4 text-[13px] font-medium leading-4 text-[#5B2730] no-underline" href="{{ route('account.profile') }}"><span>Особисті дані</span><span class="text-[14px] font-normal leading-[17px] text-[#A98088]">›</span></a>
                    <a class="flex min-h-[49px] items-center justify-between border-t border-[#E8DAD0] px-5 py-4 text-[13px] font-medium leading-4 text-[#5B2730] no-underline" href="#">
                        <span class="inline-flex items-center gap-2">Обране <b class="bg-[#F8EDE7] px-[7px] py-0.5 text-[9px] font-medium leading-[11px] text-[#7A4751]">{{ $favoritesCount }}</b></span>
                        <span class="text-[14px] font-normal leading-[17px] text-[#A98088]">›</span>
                    </a>
                    <a class="flex min-h-[49px] items-center justify-between border-t border-[#E8DAD0] px-5 py-4 text-[13px] font-medium leading-4 text-[#5B2730] no-underline" href="#"><span>Бонуси та подарункові карти</span><span class="text-[14px] font-normal leading-[17px] text-[#A98088]">›</span></a>
                    <a class="flex min-h-[49px] items-center justify-between border-t border-[#E8DAD0] px-5 py-4 text-[13px] font-medium leading-4 text-[#5B2730] no-underline" href="#"><span>Підписка на дропи</span><span class="text-[14px] font-normal leading-[17px] text-[#A98088]">›</span></a>
                    <a class="flex min-h-[49px] items-center justify-between border-t border-[#E8DAD0] px-5 py-4 text-[13px] font-medium leading-4 text-[#5B2730] no-underline" href="#"><span>Адреси доставки</span><span class="text-[14px] font-normal leading-[17px] text-[#A98088]">›</span></a>
                    <form class="border-y border-[#E8DAD0]" method="POST" action="{{ route('account.logout') }}">
                        @csrf
                        <button class="flex min-h-[49px] w-full items-center justify-between border-0 bg-transparent px-5 py-4 text-left text-[13px] font-normal leading-4 text-[#A98088]" type="submit"><span>Вийти</span><span class="text-[14px] leading-[17px]">→</span></button>
                    </form>
                </nav>

                <section class="flex flex-col gap-5 max-sm:hidden">
                    <div class="flex items-end justify-between border-b border-[#EFE4D9] pb-3">
                        <h2 class="m-0 font-cormorant text-[28px] font-medium leading-[43px] tracking-[-0.28px] text-[#5B2730]">Обране</h2>
                        <a class="text-[12px] font-medium uppercase leading-[19px] tracking-[2.16px] text-[#5B2730] no-underline" href="{{ route('account.favorites') }}">Усе обране · {{ $favoritesCount }} →</a>
                    </div>

                    <div class="grid grid-cols-3 gap-9 max-lg:grid-cols-2">
                        @foreach ($wishlistProducts as $product)
                            @php
                                $volume = collect($product['volumes'] ?? [])->first();
                            @endphp
                            <article class="min-w-0">
                                <a class="relative flex aspect-[310/414] items-center justify-center overflow-hidden bg-[#FDFBF8] bg-[linear-gradient(143deg,transparent_49.8%,rgba(176,127,110,0.16)_50%,transparent_50.2%),linear-gradient(37deg,transparent_49.8%,rgba(176,127,110,0.16)_50%,transparent_50.2%)]" href="{{ $product['url'] ?? '#' }}">
                                    <button class="absolute left-3.5 top-3.5 h-[25px] w-[30px] border border-[rgba(91,39,48,0.16)] bg-white/95 text-[12px] text-[#5B2730]" type="button" aria-label="Обране">♡</button>
                                    <img class="max-h-[86%] max-w-[86%] object-contain" src="{{ $product['image'] }}" alt="{{ $product['brand'] }} {{ $product['name'] }}">
                                </a>
                                <p class="m-0 mt-3.5 text-[12px] uppercase leading-[19px] tracking-[2.64px] text-[#A98088]">{{ $product['meta'] ?: 'Нішеві' }} · {{ $volume['label'] ?? $product['unit'] ?? '5 мл' }}</p>
                                <p class="m-0 mt-2.5 text-[13px] font-medium leading-5 tracking-[0.52px] text-[#7A4751]">{{ $product['brand'] }}</p>
                                <h3 class="m-0 mt-1 font-cormorant text-[22px] font-medium leading-[25px] text-[#5B2730]">{{ $product['name'] }}</h3>
                                <p class="m-0 mt-3 font-cormorant text-[22px] font-medium leading-[34px] text-[#5B2730]">{{ $volume['price_label'] ?? $product['price_label'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="flex flex-col gap-5 max-sm:hidden">
                    <div class="flex items-end justify-between border-b border-[#EFE4D9] pb-3">
                        <h2 class="m-0 font-cormorant text-[28px] font-medium leading-[43px] tracking-[-0.28px] text-[#5B2730]">Особисті дані</h2>
                        <a class="text-[12px] font-medium uppercase leading-[19px] tracking-[2.16px] text-[#5B2730] no-underline" href="{{ route('account.profile.edit') }}">Редагувати →</a>
                    </div>

                    <dl class="m-0 grid grid-cols-2 gap-x-7 gap-y-3.5 pt-[19px]">
                        @foreach ($profileRows as $row)
                            <div class="flex justify-between gap-6 border-b border-[#EFE4D9] pb-2">
                                <dt class="text-[11px] uppercase leading-[17px] tracking-[2.42px] text-[#A98088]">{{ $row['label'] }}</dt>
                                <dd class="m-0 text-right text-[13px] font-medium leading-5 text-[#5B2730]">{{ $row['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </section>
            </div>
        </section>
    </div>
@endsection
