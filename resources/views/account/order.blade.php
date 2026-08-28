@extends('front.sevia::layouts.app')

@section('title', 'Замовлення | Sevia')

@section('content')
    <div class="bg-white text-[#5B2730]">
        <nav class="mx-auto flex h-[50px] max-w-[1440px] items-center gap-2.5 px-[68px] pb-2 pt-[22px] text-[13px] leading-5 text-[#7A4751] max-lg:px-6 max-sm:hidden" aria-label="Breadcrumb">
            <a class="no-underline hover:text-[#5B2730]" href="{{ route('home') }}">Sevia</a>
            <span class="text-[#E8DAD0]">/</span>
            <a class="no-underline hover:text-[#5B2730]" href="{{ route('account.overview') }}">Особистий кабінет</a>
            <span class="text-[#E8DAD0]">/</span>
            <a class="no-underline hover:text-[#5B2730]" href="{{ route('account.orders') }}">Замовлення</a>
            <span class="text-[#E8DAD0]">/</span>
            <strong class="font-normal text-[#5B2730]">{{ $tracking['code'] }}</strong>
        </nav>

        <header class="mx-auto grid max-w-[1440px] grid-cols-[240px_minmax(0,1fr)] gap-[60px] px-[68px] pb-8 pt-8 max-lg:grid-cols-[220px_minmax(0,1fr)] max-lg:gap-8 max-lg:px-6 max-sm:block max-sm:px-5 max-sm:pb-3.5 max-sm:pt-4">
            <div class="col-start-2 min-w-0 max-sm:col-start-auto">
            <div class="flex items-center gap-2 text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:gap-1.5 max-sm:text-[11px] max-sm:leading-[13px] max-sm:tracking-[0.8px]">
                <a class="hidden text-[18px] font-normal leading-none text-[#5B2730] no-underline max-sm:inline-flex" href="{{ route('account.orders') }}" aria-label="Назад">←</a>
                <span>Замовлення</span>
            </div>
            <h1 class="m-0 mt-2 font-cormorant text-[48px] font-medium leading-[1] tracking-[-0.5px] text-[#5B2730] max-sm:mt-1.5 max-sm:text-[30px] max-sm:leading-9 max-sm:tracking-normal">{{ $tracking['code'] }}</h1>
            <p class="m-0 mt-2 text-[13px] leading-5 text-[#A98088] max-sm:mt-1.5 max-sm:text-[11.5px] max-sm:leading-[14px]">Оформлено {{ $orderedAt }} · Сплачено</p>
            </div>
        </header>

        <section class="mx-auto grid max-w-[1440px] grid-cols-[240px_minmax(0,1fr)] gap-[60px] px-[68px] pb-[90px] pt-0 max-lg:grid-cols-[220px_minmax(0,1fr)] max-lg:gap-8 max-sm:block max-sm:px-5 max-sm:pb-12 max-sm:pt-1">
            <aside class="self-start max-sm:hidden" aria-label="Account navigation">
                <ul class="m-0 list-none divide-y divide-[#EFE4D9] border-y border-[#EFE4D9] p-0">
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.overview') }}"><span class="font-cormorant text-[16px] text-[#A85D66]">01</span><span class="text-[14px]">Огляд</span></a></li>
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#5B2730] no-underline" href="{{ route('account.orders') }}"><span class="font-cormorant text-[16px] font-medium">02</span><span class="text-[14px] font-semibold">Замовлення</span></a></li>
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.favorites') }}"><span class="font-cormorant text-[16px] text-[#A85D66]">03</span><span class="text-[14px]">Обране</span></a></li>
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.profile') }}"><span class="font-cormorant text-[16px] text-[#A85D66]">05</span><span class="text-[14px]">Особисті дані</span></a></li>
                </ul>
            </aside>

            <main class="min-w-0">
                <section class="px-0 pb-3.5 pt-1 max-sm:px-0" aria-labelledby="delivery-status">
                    <h2 id="delivery-status" class="m-0 font-cormorant text-[28px] font-medium leading-[34px] text-[#5B2730] max-sm:text-[20px] max-sm:leading-6">Статус доставки</h2>
                    <div class="mt-5 max-w-[680px] max-sm:mt-3">
                        @php
                            $steps = collect($tracking['steps'])->values();
                        @endphp
                        @foreach ($steps as $index => $step)
                            <div class="flex {{ $index === $steps->count() - 1 ? 'min-h-[30px]' : 'min-h-[54px]' }} gap-3">
                                <div class="flex w-4 shrink-0 flex-col items-center">
                                    <span class="mt-0.5 h-3.5 w-3.5 rounded-full {{ $step['done'] ? 'bg-[#5B2730]' : ($index === $steps->count() - 2 ? 'border-2 border-[#5B2730] bg-white' : 'border-[1.4px] border-[#E8DAD0] bg-white') }}"></span>
                                    @if ($index < $steps->count() - 1)
                                        <span class="h-10 w-[1.5px] {{ $step['done'] ? 'bg-[#5B2730]' : 'bg-[#E8DAD0]' }}"></span>
                                    @endif
                                </div>
                                <div class="flex min-w-0 flex-1 flex-col gap-0.5 {{ $index === $steps->count() - 1 ? 'pb-0' : 'pb-[18px]' }}">
                                    <span class="text-[13px] font-medium leading-[15px] {{ $step['done'] || $index === $steps->count() - 2 ? 'text-[#5B2730]' : 'text-[#A98088]' }}">{{ $step['label'] }}</span>
                                    @if ($step['date'] !== '')
                                        <span class="text-[10.5px] leading-[13px] text-[#A98088]">{{ $step['date'] }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if ($tracking['ttn'] !== '')
                        @php($trackingUrl = 'https://novaposhta.ua/tracking/' . preg_replace('/\D+/', '', $tracking['ttn']))
                        <div class="mt-1 flex min-h-[59px] items-center justify-between gap-4 bg-[#F8EDE7] px-4 py-3.5 max-sm:mt-0">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[10px] uppercase leading-3 tracking-[0.6px] text-[#A98088]">Номер ТТН</span>
                                <span class="text-[14px] font-medium leading-[17px]">{{ $tracking['ttn'] }}</span>
                            </div>
                            <a class="text-[10.5px] font-medium uppercase leading-[13px] tracking-[0.6px] text-[#7A4751] no-underline" href="{{ $trackingUrl }}" target="_blank" rel="noreferrer">Відстежити →</a>
                        </div>
                    @endif
                </section>

                <section class="border-t border-[#E8DAD0] pt-5 max-sm:pt-2" aria-labelledby="order-items">
                    <div class="flex items-baseline justify-between gap-4">
                        <h2 id="order-items" class="m-0 font-cormorant text-[28px] font-medium leading-[34px] text-[#5B2730] max-sm:text-[20px] max-sm:leading-6">Склад замовлення</h2>
                        <span class="text-[12px] leading-4 text-[#A98088]">{{ $items->count() }} {{ $items->count() === 1 ? 'позиція' : 'позиції' }}</span>
                    </div>
                    <div class="mt-3 max-w-[760px]">
                        @forelse ($items as $item)
                            <article class="flex min-h-[84px] items-center gap-3 border-t border-[#E8DAD0] py-3">
                                <div class="flex h-[60px] w-12 shrink-0 items-center justify-center bg-[#FBF1ED] p-1">
                                    @if ($item['image'])
                                        <img class="h-full w-full object-contain" src="{{ $item['image'] }}" alt="{{ $item['name'] }}" loading="lazy">
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-[9.5px] uppercase leading-[11px] tracking-[0.4px] text-[#A98088]">{{ $item['meta'] }}</div>
                                    <h3 class="m-0 mt-0.5 font-cormorant text-[22px] font-medium leading-[26px] text-[#5B2730] max-sm:text-[17px] max-sm:leading-[21px]">{{ $item['name'] }}</h3>
                                    <a class="mt-1 inline-block text-[10px] font-medium uppercase leading-3 tracking-[0.5px] text-[#7A4751] no-underline" href="{{ $item['product_slug'] !== '' ? route('product.show', ['product' => $item['product_slug']]) : route('catalog.index') }}">Купити ще</a>
                                </div>
                                <span class="shrink-0 text-[14px] font-medium leading-[17px] text-[#5B2730]">{{ $item['price'] }}</span>
                            </article>
                        @empty
                            <p class="border-t border-[#E8DAD0] py-5 text-[13px] text-[#A98088]">Склад замовлення недоступний.</p>
                        @endforelse
                    </div>
                    <div class="mt-4 flex items-center justify-between border-t border-[#E8DAD0] pt-4 max-w-[760px]">
                        <span class="text-[12px] uppercase tracking-[1px] text-[#A98088]">Разом</span>
                        <strong class="font-cormorant text-[24px] font-medium">{{ $orderTotal }}</strong>
                    </div>
                </section>

                <div class="mt-6 flex max-w-[760px] flex-col gap-2.5 pb-2">
                    <a class="flex h-[47px] items-center justify-center bg-[#5B2730] text-[12px] font-medium uppercase leading-[15px] tracking-[1.2px] text-white no-underline" href="{{ route('account.orders') }}">Повторити замовлення</a>
                    <button class="flex h-[45px] items-center justify-center border border-[#5B2730] bg-white text-[12px] font-medium uppercase leading-[15px] tracking-[1.2px] text-[#5B2730]" type="button">Завантажити чек</button>
                </div>
            </main>
        </section>
    </div>
@endsection
