@extends('front.sevia::layouts.app')

@section('title', 'Доставка та оплата | Sevia')
@section('meta_description', 'Умови доставки, оплати, обміну, повернення та самовивозу в шоу-румі Sevia.')

@php
    $freeShippingFrom = max(0, (float) \App\Models\Setting::admin('cart.free_shipping_from', 0));
    $freeShippingMeta = $freeShippingFrom > 0 ? ' · безкоштовно від ' . number_format($freeShippingFrom, 0, '.', ' ') . ' ₴' : '';
    $contents = [
        ['id' => 'delivery', 'title' => 'Доставка'],
        ['id' => 'payment', 'title' => 'Оплата'],
        ['id' => 'returns', 'title' => 'Обмін і повернення'],
        ['id' => 'original', 'title' => 'Гарантія оригіналу'],
        ['id' => 'showroom', 'title' => 'Шоу-рум'],
    ];

    $deliveryItems = [
        ['number' => '01', 'title' => 'Нова Пошта · відділення', 'meta' => '1–2 дні · від 70 ₴' . $freeShippingMeta],
        ['number' => '02', 'title' => 'Нова Пошта · поштомат', 'meta' => '1–2 дні · від 70 ₴' . $freeShippingMeta],
        ['number' => '03', 'title' => 'Курʼєр Sevia · Київ', 'meta' => 'день у день для замовлень до 14:00 · 150 ₴'],
        ['number' => '04', 'title' => 'Самовивіз · шоу-рум', 'meta' => 'безкоштовно · попередньо записатись у Telegram'],
    ];
@endphp

@section('content')
    <nav class="flex h-[50.15px] items-center gap-2.5 px-[68.04px] pb-2 pt-[22px] text-[13px] font-normal leading-5 max-lg:px-6 max-sm:hidden" aria-label="Breadcrumb">
        <a class="text-[#7A4751] hover:text-[#5B2730]" href="{{ route('home') }}">Sevia</a>
        <span class="text-[#E8DAD0]">/</span>
        <span class="text-[#5B2730]">Доставка та оплата</span>
    </nav>

    <section class="bg-white px-[68.04px] pb-28 pt-[64.49px] max-lg:px-6 max-lg:pb-20 max-sm:px-0 max-sm:py-0">
        <div class="mx-auto flex w-full max-w-[1303.92px] flex-col">
            <header class="flex min-h-[193.35px] flex-col items-center gap-3 pb-[45.36px] text-center max-sm:h-[163px] max-sm:min-h-0 max-sm:items-start max-sm:px-5 max-sm:pb-6 max-sm:pt-3 max-sm:text-left">
                <p class="m-0 text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:font-sans max-sm:leading-[13px]">
                    Сервіс
                </p>
                <h1 class="m-0 font-cormorant text-[78.6px] font-medium leading-[79px] tracking-[-1.179px] text-[#5B2730] max-sm:whitespace-nowrap max-sm:text-[38px] max-sm:leading-[46px] max-sm:tracking-[-1.18px]">
                    Доставка та оплата
                </h1>
                <p class="m-0 max-w-[570px] pt-0.5 text-[16px] font-normal leading-[25px] text-[#7A4751] max-sm:w-full max-sm:pt-0 max-sm:font-sans max-sm:text-[14px] max-sm:font-medium max-sm:leading-[22px]">
                    Чесно і прозоро · без прихованих доплат, без передоплати на першу покупку.
                </p>
            </header>

            <div class="grid min-h-[1690.67px] grid-cols-[240px_minmax(0,1003.44px)] gap-[60.48px] max-lg:min-h-0 max-lg:grid-cols-1 max-lg:gap-12 max-sm:gap-0">
                <aside class="flex w-60 flex-col gap-3.5 max-lg:hidden">
                    <p class="m-0 text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088]">
                        Зміст
                    </p>
                    <ol class="m-0 flex w-full list-none flex-col border-t border-[#E8DAD0] p-0">
                        @foreach ($contents as $item)
                            <li class="h-[45.15px]">
                                <a class="flex h-[44.15px] items-center py-[11.25px] text-[13px] font-normal leading-5 tracking-[0.52px] text-[#7A4751] hover:text-[#5B2730]" href="#{{ $item['id'] }}">
                                    {{ $item['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </aside>

                <div class="flex flex-col gap-[60.5px] max-sm:gap-0">
                    <section id="delivery" class="scroll-mt-36 max-sm:flex max-sm:flex-col max-sm:gap-4 max-sm:px-5 max-sm:pb-6 max-sm:pt-4">
                        <h2 class="m-0 pb-4 font-cormorant text-[60px] font-medium leading-[63px] tracking-[-0.6px] text-[#5B2730] max-sm:pb-0 max-sm:text-[28px] max-sm:leading-[34px] max-sm:tracking-normal">
                            Доставка
                        </h2>
                        <p class="m-0 max-w-[592px] text-[16px] font-normal leading-[26px] text-[#7A4751] max-sm:text-[14px] max-sm:leading-[22px]">
                            Відправляємо щодня з 11:00 до 17:00 · замовлення, оформлені до 16:00, ідуть того ж дня. У вихідні підтверджуємо замовлення в понеділок.
                        </p>

                        <ol class="m-0 mt-6 flex list-none flex-col border-t border-[#EFE4D9] p-0 max-sm:mt-0 max-sm:w-full">
                            @foreach ($deliveryItems as $item)
                                <li class="grid min-h-[79.4px] grid-cols-[36px_minmax(0,1fr)] gap-3.5 border-b border-[#EFE4D9] py-4 max-sm:flex max-sm:h-[91px] max-sm:min-h-0 max-sm:flex-col max-sm:gap-2 max-sm:py-[18px]">
                                    <span class="text-[16px] font-normal leading-[25px] text-[#5B2730] max-sm:hidden">
                                        {{ $item['number'] }}
                                    </span>
                                    <span class="flex min-w-0 flex-col gap-[1.25px] max-sm:gap-2">
                                        <span class="hidden h-[29px] items-center gap-3 max-sm:flex">
                                            <span class="font-cormorant text-[24px] font-medium italic leading-[29px] text-[#5B2730]">
                                                {{ $item['number'] }}
                                            </span>
                                            <strong class="min-w-0 flex-1 truncate font-sans text-[15px] font-semibold leading-[18px] text-[#5B2730]">
                                                {{ $item['title'] }}
                                            </strong>
                                        </span>
                                        <strong class="text-[15px] font-semibold leading-[23px] text-[#5B2730] max-sm:hidden">
                                            {{ $item['title'] }}
                                        </strong>
                                        <span class="text-[13px] font-normal leading-5 text-[#7A4751] max-sm:truncate max-sm:font-sans max-sm:leading-[18px]">
                                            {{ $item['meta'] }}
                                        </span>
                                    </span>
                                </li>
                            @endforeach
                        </ol>
                    </section>

                    <section id="payment" class="scroll-mt-36 max-sm:flex max-sm:flex-col max-sm:gap-4 max-sm:px-5 max-sm:pb-6 max-sm:pt-4">
                        <h2 class="m-0 pb-4 font-cormorant text-[60px] font-medium leading-[63px] tracking-[-0.6px] text-[#5B2730] max-sm:pb-0 max-sm:text-[28px] max-sm:leading-[34px] max-sm:tracking-normal">
                            Оплата
                        </h2>
                        <p class="m-0 max-w-[592px] text-[16px] font-normal leading-[26px] text-[#7A4751] max-sm:hidden">
                            На першу покупку - знижка 10% за промокодом NEW2026
                        </p>
                        <div class="hidden rounded-xl border border-[#E8DAD0] bg-[#F8EDE7] p-4 max-sm:flex max-sm:h-[99px] max-sm:flex-col max-sm:gap-3">
                            <span class="text-[12px] font-semibold uppercase leading-[15px] tracking-[1px] text-[#5B2730]">
                                Пропозиція
                            </span>
                            <span class="text-[14px] font-medium leading-5 text-[#7A4751]">
                                На першу покупку - знижка 10% за промокодом NEW2026
                            </span>
                        </div>
                        <div class="mt-6 grid min-h-[79.4px] grid-cols-[36px_minmax(0,1fr)] gap-3.5 border-y border-[#EFE4D9] py-4 max-sm:mt-0 max-sm:flex max-sm:h-[92px] max-sm:flex-col max-sm:gap-1.5 max-sm:rounded-xl max-sm:border max-sm:border-[#EFE4D9] max-sm:bg-white max-sm:p-4">
                            <span class="text-[16px] font-normal leading-[25px] text-[#5B2730] max-sm:hidden">_</span>
                            <span class="flex min-w-0 flex-col gap-[1.25px] max-sm:gap-1.5">
                                <strong class="text-[15px] font-semibold leading-[23px] text-[#5B2730] max-sm:font-sans max-sm:leading-[18px]">Карта онлайн</strong>
                                <span class="text-[13px] font-normal leading-5 text-[#7A4751] max-sm:leading-[18px]">
                                    VISA, Mastercard, Apple Pay, Google Pay · через захищений шлюз LiqPay
                                </span>
                            </span>
                        </div>
                    </section>

                    <section id="returns" class="scroll-mt-36 max-sm:flex max-sm:h-36 max-sm:flex-col max-sm:gap-3 max-sm:px-5 max-sm:py-4">
                        <h2 class="m-0 pb-4 font-cormorant text-[60px] font-medium leading-[63px] tracking-[-0.6px] text-[#5B2730] max-sm:pb-0 max-sm:text-[28px] max-sm:leading-[34px] max-sm:tracking-normal">
                            Обмін і повернення
                        </h2>
                        <p class="m-0 max-w-[720px] text-[16px] font-normal leading-[26px] text-[#7A4751] max-sm:text-[14px] max-sm:leading-[22px]">
                            Обмін та повернення можна здійснити лише під час отримання парфумів на відділенні Нової Пошти.
                        </p>
                    </section>

                    <section id="original" class="scroll-mt-36 max-sm:flex max-sm:h-[166px] max-sm:flex-col max-sm:gap-3 max-sm:px-5 max-sm:py-4">
                        <h2 class="m-0 pb-4 font-cormorant text-[60px] font-medium leading-[63px] tracking-[-0.6px] text-[#5B2730] max-sm:pb-0 max-sm:text-[28px] max-sm:leading-[34px] max-sm:tracking-normal">
                            Гарантія оригіналу
                        </h2>
                        <p class="m-0 max-w-[592px] text-[16px] font-normal leading-[26px] text-[#7A4751] max-sm:text-[14px] max-sm:leading-[22px]">
                            Купуємо в офіційних дистрибʼюторів і європейських бутиків. До кожного парфуму декантовання · окремий чек і сертифікат походження.
                        </p>
                    </section>

                    <section id="showroom" class="scroll-mt-36 max-sm:flex max-sm:h-[278px] max-sm:flex-col max-sm:gap-4 max-sm:px-5 max-sm:pb-12 max-sm:pt-4">
                        <h2 class="m-0 pb-4 font-cormorant text-[60px] font-medium leading-[63px] tracking-[-0.6px] text-[#5B2730] max-sm:pb-0 max-sm:text-[28px] max-sm:leading-[34px] max-sm:tracking-normal">
                            Шоу-рум
                        </h2>
                        <div class="max-sm:flex max-sm:h-[164px] max-sm:w-full max-sm:flex-col max-sm:gap-3 max-sm:rounded-2xl max-sm:border max-sm:border-[#E8DAD0] max-sm:bg-white max-sm:p-5">
                            <p class="m-0 max-w-[592px] text-[16px] font-normal leading-[26px] text-[#7A4751] max-sm:font-cormorant max-sm:text-[20px] max-sm:font-medium max-sm:leading-[26px] max-sm:text-[#5B2730]">
                                Київ, вул. Велика Васильківська 36, поверх 2.
                            </p>
                            <p class="m-0 mt-[15.5px] max-w-[598px] text-[16px] font-normal leading-[26px] text-[#7A4751] max-sm:mt-0 max-sm:font-sans max-sm:text-[13px] max-sm:leading-5">
                                У шоу-румі можна спробувати будь-який аромат з каталогу, подивитися новинки і забрати замовлення без доставки.
                            </p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
@endsection
