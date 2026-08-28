@extends('front.sevia::layouts.app')

@section('title', 'Замовлення | Sevia')

@section('content')
    <div class="bg-white text-[#5B2730]">
        <nav class="mx-auto flex h-[50px] max-w-[1440px] items-center gap-2.5 px-[68px] pb-2 pt-[22px] text-[13px] leading-5 text-[#7A4751] max-lg:px-6 max-sm:hidden" aria-label="Breadcrumb">
            <a class="no-underline hover:text-[#5B2730]" href="{{ route('home') }}">Sevia</a>
            <span class="text-[#E8DAD0]">/</span>
            <a class="no-underline hover:text-[#5B2730]" href="{{ route('account.overview') }}">Особистий кабінет</a>
            <span class="text-[#E8DAD0]">/</span>
            <strong class="font-normal text-[#5B2730]">Замовлення</strong>
        </nav>

        <header class="mx-auto max-w-[1440px] px-[68px] pb-[35px] pt-[30px] max-lg:px-6 max-sm:flex max-sm:flex-col max-sm:items-start max-sm:px-5 max-sm:pb-2 max-sm:pt-4">
            <div class="flex items-center gap-2 text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:text-[11px] max-sm:leading-[13px] max-sm:tracking-[0.8px]"><a class="hidden text-[18px] font-normal leading-none text-[#5B2730] no-underline max-sm:inline-flex" href="{{ url()->previous() !== url()->current() ? url()->previous() : route('account.overview') }}" aria-label="Назад">←</a><span>Особистий кабінет</span></div>
            <h1 class="m-0 mt-2 font-cormorant text-[64px] font-medium leading-none tracking-[-0.96px] text-[#5B2730] max-sm:mt-[12px] max-sm:self-center max-sm:text-[32px] max-sm:leading-[39px] max-sm:tracking-normal">Замовлення</h1>
        </header>

        <section class="mx-auto grid max-w-[1440px] grid-cols-[240px_minmax(0,1fr)] gap-[60px] px-[68px] pb-[91px] pt-14 max-lg:grid-cols-[220px_minmax(0,1fr)] max-lg:gap-8 max-lg:px-6 max-sm:block max-sm:px-5 max-sm:pb-12 max-sm:pt-2">
            <aside class="self-start max-sm:hidden" aria-label="Account navigation">
                <ul class="m-0 list-none divide-y divide-[#EFE4D9] border-y border-[#EFE4D9] p-0">
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.overview') }}"><span class="font-cormorant text-[16px] text-[#A85D66]">01</span><span class="text-[14px]">Огляд</span></a></li>
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)_auto] items-center gap-2.5 py-3 text-[#5B2730] no-underline" href="{{ route('account.orders') }}"><span class="font-cormorant text-[16px] font-medium text-[#5B2730]">02</span><span class="text-[14px] font-semibold">Замовлення</span><span class="text-[11px] uppercase leading-[17px] tracking-[1.98px] text-[#A98088]">{{ $orders->count() }}</span></a></li>
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.favorites') }}"><span class="font-cormorant text-[16px] text-[#A85D66]">03</span><span class="text-[14px]">Обране</span></a></li>
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.profile') }}"><span class="font-cormorant text-[16px] text-[#A85D66]">05</span><span class="text-[14px]">Особисті дані</span></a></li>
                </ul>
                <form method="POST" action="{{ route('account.logout') }}">
                    @csrf
                    <button class="mt-4 border-0 bg-transparent p-0 text-left text-[12px] uppercase leading-[19px] tracking-[2.16px] text-[#7A4751]" type="submit">← Вийти</button>
                </form>
            </aside>

            <main class="min-w-0">
                <nav class="flex flex-wrap gap-2 pb-5 max-sm:gap-2 max-sm:pb-2" aria-label="Фільтр замовлень">
                    @foreach (['all' => 'Усі', 'transit' => 'У дорозі', 'delivered' => 'Доставлені'] as $value => $label)
                        <a class="inline-flex h-9 items-center border px-3.5 text-[13px] font-medium leading-5 no-underline max-sm:h-[29px] max-sm:px-[14px] max-sm:text-[11px] max-sm:leading-[13px] {{ $filter === $value ? 'border-[#5B2730] bg-[#5B2730] text-white' : 'border-[#E8DAD0] bg-white text-[#7A4751]' }}" href="{{ route('account.orders', ['status' => $value]) }}">{{ $label }}</a>
                    @endforeach
                </nav>

                <div class="flex flex-col gap-0 max-sm:gap-3">
                    @forelse ($orders as $order)
                        <article class="grid min-h-[79px] grid-cols-[180px_minmax(0,1fr)_auto_76px_70px] items-center gap-6 border-b border-[#EFE4D9] px-0 py-0 max-lg:grid-cols-[1fr_auto] max-lg:gap-x-3.5 max-lg:gap-y-2 max-lg:py-3.5 max-sm:min-h-[119px] max-sm:grid-cols-[minmax(0,1fr)_auto] max-sm:gap-x-3 max-sm:gap-y-1.5 max-sm:border max-sm:border-[#E8DAD0] max-sm:px-4 max-sm:py-3.5">
                            <div class="max-sm:row-span-2">
                                <div class="text-[14px] font-semibold leading-[22px] tracking-[0.28px] text-[#5B2730] max-sm:text-[13px] max-sm:font-medium max-sm:leading-4 max-sm:tracking-normal">{{ $order['code'] }}</div>
                                <div class="mt-1.5 text-[12px] leading-[19px] tracking-[0.48px] text-[#A98088] max-sm:text-[10.5px] max-sm:leading-[13px] max-sm:tracking-normal">{{ $order['date'] }}</div>
                            </div>
                            <div class="min-w-0 truncate text-[13px] leading-5 text-[#7A4751] max-lg:col-span-2 max-sm:col-span-2 max-sm:text-[12px] max-sm:leading-[15px]">{{ $order['items'] }}</div>
                            <span class="inline-flex justify-center bg-[#F8EDE7] px-2.5 py-1 text-[9px] font-medium uppercase leading-[11px] tracking-[0.5px] text-[#7A4751] max-sm:col-start-2 max-sm:row-start-1 max-sm:justify-self-end">{{ $order['status'] }}</span>
                            <div class="font-cormorant text-[20px] font-medium leading-[31px] text-[#5B2730] max-sm:col-start-1 max-sm:mt-1 max-sm:text-[18px] max-sm:leading-[22px]">{{ $order['total'] }}</div>
                            <a class="text-[12px] font-medium uppercase leading-[19px] tracking-[2.16px] text-[#5B2730] no-underline max-sm:col-start-2 max-sm:justify-self-end max-sm:text-[11px] max-sm:leading-[13px] max-sm:tracking-[0.6px]" href="{{ route('account.orders.show', ['order' => $order['id']]) }}">Деталі →</a>
                        </article>
                    @empty
                        <div class="border border-[#E8DAD0] px-5 py-12 text-center text-[13px] leading-5 text-[#A98088]">Замовлень із таким статусом немає.</div>
                    @endforelse
                </div>
            </main>
        </section>
    </div>
@endsection
