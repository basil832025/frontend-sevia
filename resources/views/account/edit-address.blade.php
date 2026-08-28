@extends('front.sevia::layouts.app')

@section('title', 'Редагувати адресу доставки | Sevia')

@section('content')
    <div class="bg-white text-[#5B2730]">
        <nav class="mx-auto flex h-[50px] max-w-[1440px] items-center gap-2.5 px-[68px] pb-2 pt-[22px] text-[13px] leading-5 text-[#7A4751] max-lg:px-6 max-sm:hidden" aria-label="Breadcrumb">
            <a class="no-underline hover:text-[#5B2730]" href="{{ route('home') }}">Sevia</a>
            <span class="text-[#E8DAD0]">/</span>
            <a class="no-underline hover:text-[#5B2730]" href="{{ route('account.overview') }}">Особистий кабінет</a>
            <span class="text-[#E8DAD0]">/</span>
            <a class="no-underline hover:text-[#5B2730]" href="{{ route('account.profile') }}">Особисті дані</a>
            <span class="text-[#E8DAD0]">/</span>
            <strong class="font-normal text-[#5B2730]">Адреса доставки</strong>
        </nav>

        <header class="mx-auto max-w-[1440px] px-[68px] pb-[35px] pt-[30px] max-lg:px-6 max-sm:px-5 max-sm:pb-5 max-sm:pt-4">
            <div class="text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:text-[11px] max-sm:leading-[13px] max-sm:tracking-[0.8px]">Особистий кабінет</div>
            <div class="mt-2 flex items-baseline justify-between">
                <h1 class="m-0 font-cormorant text-[64px] font-medium leading-none tracking-[-0.96px] text-[#5B2730] max-sm:text-[32px] max-sm:leading-[39px] max-sm:tracking-normal">Адреса доставки</h1>
            </div>
        </header>

        <section class="mx-auto grid max-w-[1440px] grid-cols-[240px_minmax(0,1fr)] gap-[60px] px-[68px] pb-[91px] pt-14 max-lg:grid-cols-[220px_minmax(0,1fr)] max-lg:gap-8 max-lg:px-6 max-sm:block max-sm:px-5 max-sm:pb-12 max-sm:pt-2">
            <aside class="self-start max-sm:hidden" aria-label="Account navigation">
                <ul class="m-0 list-none divide-y divide-[#EFE4D9] border-y border-[#EFE4D9] p-0">
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.overview') }}"><span class="font-cormorant text-[16px] text-[#A85D66]">01</span><span class="text-[14px]">Огляд</span></a></li>
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="#"><span class="font-cormorant text-[16px] text-[#A85D66]">02</span><span class="text-[14px]">Замовлення</span></a></li>
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.favorites') }}"><span class="font-cormorant text-[16px] text-[#A85D66]">03</span><span class="text-[14px]">Обране</span></a></li>
                    <li><a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)] items-center gap-2.5 py-3 text-[#5B2730] no-underline" href="{{ route('account.profile') }}"><span class="font-cormorant text-[16px] text-[#5B2730]">05</span><span class="text-[14px] font-semibold">Особисті дані</span></a></li>
                </ul>
            </aside>

            <form class="flex w-full max-w-[680px] flex-col gap-12 max-sm:max-w-none max-sm:gap-8" method="POST" action="{{ route('account.address.update') }}">
                @csrf
                @method('PATCH')

                <section class="flex flex-col gap-[22px] max-sm:gap-[14px]">
                    <h2 class="m-0 border-b border-[#EFE4D9] pb-3 font-cormorant text-[28px] font-medium leading-[43px] tracking-[-0.28px] text-[#5B2730] max-sm:border-0 max-sm:pb-0 max-sm:text-[20px] max-sm:leading-6 max-sm:tracking-normal">Адреса доставки</h2>
                    <div class="grid grid-cols-2 gap-x-7 gap-y-[22px] max-sm:grid-cols-1 max-sm:gap-y-[6px] max-sm:[&_label]:gap-[7px] max-sm:[&_label>span]:text-[10px] max-sm:[&_label>span]:leading-3 max-sm:[&_label>span]:tracking-[1.2px] max-sm:[&_label>input]:h-[42px] max-sm:[&_label>input]:px-[14px] max-sm:[&_label>input]:text-[13px] max-sm:[&_label>input]:leading-4">
                        <label class="relative flex flex-col gap-1.5" data-account-nova-city-wrap data-cities-url="{{ route('checkout.nova-post.cities') }}">
                            <span class="text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088]">Місто</span>
                            <input class="h-11 w-full border border-[#E8DAD0] bg-[#FDFBF8] px-3.5 text-[15px] text-[#5B2730] outline-none focus:border-[#5B2730]" type="text" name="city" value="{{ old('city', $address?->city ?: $latestOrder?->nova_city) }}" autocomplete="off" data-account-city-input>
                            <input type="hidden" name="city_ref" value="{{ old('city_ref', $address?->nova_city_ref ?: $latestOrder?->nova_city_ref) }}" data-account-city-ref>
                            <div class="absolute left-0 right-0 top-full z-30 mt-1 hidden max-h-64 overflow-y-auto border border-[#E8DAD0] bg-white shadow-[0_10px_30px_rgba(91,39,48,0.12)]" data-account-city-menu></div>
                        </label>
                        <label class="relative col-span-2 flex flex-col gap-1.5 max-sm:col-span-1" data-account-nova-warehouse-wrap data-warehouses-url="{{ route('checkout.nova-post.warehouses') }}">
                            <span class="text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088]">Відділення Нової Пошти</span>
                            <input class="h-11 w-full border border-[#E8DAD0] bg-[#FDFBF8] px-3.5 text-[15px] text-[#5B2730] outline-none focus:border-[#5B2730] disabled:cursor-not-allowed disabled:bg-[#F8EDE7]" type="text" name="formatted_address" value="{{ old('formatted_address', $address?->formatted_address ?: $latestOrder?->nova_warehouse) }}" autocomplete="off" data-account-warehouse-input>
                            <input type="hidden" name="warehouse_ref" value="{{ old('warehouse_ref', $address?->nova_warehouse_ref ?: $latestOrder?->nova_warehouse_ref) }}" data-account-warehouse-ref>
                            <div class="absolute left-0 right-0 top-full z-30 mt-1 hidden max-h-64 overflow-y-auto border border-[#E8DAD0] bg-white shadow-[0_10px_30px_rgba(91,39,48,0.12)]" data-account-warehouse-menu></div>
                        </label>
                        @if ($errors->any())
                            <div class="col-span-2 border border-[#E7CDCA] bg-[#F8EDE7] px-4 py-3 text-[13px] leading-5 text-[#5B2730] max-sm:col-span-1">{{ $errors->first() }}</div>
                        @endif
                    </div>
                </section>

                <div class="flex justify-end gap-3.5 border-t border-[#EFE4D9] pt-7 max-sm:gap-[10px] max-sm:border-0 max-sm:pt-0">
                    <a class="inline-flex h-[50px] items-center justify-center border border-[#5B2730] px-7 text-[13px] font-medium uppercase tracking-[1.56px] text-[#5B2730] no-underline max-sm:h-9 max-sm:flex-1 max-sm:px-2 max-sm:text-[10px]" href="{{ route('account.profile') }}">Скасувати</a>
                    <button class="inline-flex h-[50px] items-center justify-center border border-[#5B2730] bg-[#5B2730] px-7 text-[13px] font-medium uppercase tracking-[1.56px] text-[#FDFBF8] max-sm:h-9 max-sm:flex-1 max-sm:px-2 max-sm:text-[10px]" type="submit">Зберегти зміни</button>
                </div>
            </form>
        </section>
    </div>
@endsection
