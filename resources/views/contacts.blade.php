@extends('front.sevia::layouts.app')

@section('title', 'Контакти | Sevia')
@section('meta_description', 'Контакти Sevia: Telegram, Instagram, email, телефон та шоу-рум у Києві.')

@php
    $showroomMapUrl = 'https://www.google.com/maps/place/SEVIA/@50.3918344,30.4632471,669m/data=!3m1!1e3!4m14!1m7!3m6!1s0x40d4c9fa8bb1624d:0x8a357052ef35108d!2sSEVIA!8m2!3d50.3918311!4d30.4681126!16s%2Fg%2F11zgyrvm_2!3m5!1s0x40d4c9fa8bb1624d:0x8a357052ef35108d!8m2!3d50.3918311!4d30.4681126!16s%2Fg%2F11zgyrvm_2?entry=ttu';
    $showroomMapEmbedUrl = 'https://www.google.com/maps?q=50.3918311,30.4681126&z=16&output=embed';

    $channels = [
        ['label' => 'Telegram', 'title' => '@sevia', 'meta' => 'Відповідаємо за ~18 хв · 10:00–20:00', 'action' => 'Написати у Telegram', 'href' => '#'],
        ['label' => 'Instagram · парфуми', 'title' => '@sevia.parfume', 'meta' => 'Новинки, дропи, огляди ароматів', 'action' => 'Відкрити Instagram', 'href' => '#'],
        ['label' => 'Instagram · білизна', 'title' => '@sevia.lingerie', 'meta' => 'Образи, нові колекції, розмірний гайд', 'action' => 'Відкрити Instagram', 'href' => '#'],
        ['label' => 'Email', 'title' => 'hello@sevia.ua', 'meta' => 'Для співпраці та оптових запитів', 'action' => 'Написати листа', 'href' => 'mailto:hello@sevia.ua'],
        ['label' => 'Телефон', 'title' => '+38 (063) 000 00 00', 'meta' => 'Дзвінки та Viber · робочі дні', 'action' => 'Подзвонити або Viber', 'href' => 'tel:+380630000000'],
    ];
@endphp

@section('content')
    <nav class="flex h-[50.15px] items-center gap-2.5 px-[68.04px] pb-2 pt-[22px] text-[13px] font-normal leading-5 max-lg:px-6 max-sm:hidden" aria-label="Breadcrumb">
        <a class="text-[#7A4751] hover:text-[#5B2730]" href="{{ route('home') }}">Sevia</a>
        <span class="text-[#E8DAD0]">/</span>
        <span class="text-[#5B2730]">Контакти</span>
    </nav>

    <section class="bg-white px-[68.04px] pb-28 pt-[64.49px] max-lg:px-6 max-lg:pb-20 max-sm:px-0 max-sm:py-0">
        <div class="mx-auto flex w-full max-w-[1303.92px] flex-col">
            <header class="flex min-h-[218.35px] flex-col items-center gap-3 pb-[45.36px] text-center max-sm:h-[163px] max-sm:min-h-0 max-sm:items-start max-sm:px-5 max-sm:pb-6 max-sm:pt-3 max-sm:text-left">
                <p class="m-0 text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:font-sans max-sm:leading-[13px]">
                    Звʼязок
                </p>
                <h1 class="m-0 font-cormorant text-[78.6px] font-medium leading-[79px] tracking-[-1.179px] text-[#5B2730] max-sm:text-[38px] max-sm:leading-[46px]">
                    Контакти
                </h1>
                <p class="m-0 max-w-[580px] pt-0.5 text-[16px] font-normal leading-[25px] text-[#7A4751] max-sm:w-full max-sm:pt-0 max-sm:font-sans max-sm:text-[14px] max-sm:font-semibold max-sm:leading-[22px]">
                    Найшвидше - у Instagram. Але обери будь-який зручний канал: ми відповідаємо скрізь і швидко.
                </p>
            </header>

            <div class="grid min-h-[1018.71px] grid-cols-[minmax(0,808.32px)_420px] gap-[75.6px] max-xl:grid-cols-[minmax(0,1fr)_420px] max-lg:min-h-0 max-lg:grid-cols-1 max-lg:gap-12 max-sm:gap-0">
                <div class="border-t border-[#EFE4D9] max-sm:border-t-0 max-sm:px-5 max-sm:pb-10">
                    @foreach ($channels as $channel)
                        <a class="grid min-h-[120.55px] grid-cols-[180px_minmax(0,1fr)_17px] items-center gap-6 border-b border-[#EFE4D9] px-1 text-left transition-colors hover:bg-[#FDFBF8] max-sm:flex max-sm:h-[129px] max-sm:min-h-0 max-sm:w-full max-sm:items-center max-sm:justify-between max-sm:gap-0 max-sm:px-0 max-sm:py-5" href="{{ $channel['href'] }}">
                            <span class="text-[12px] font-normal uppercase leading-[19px] tracking-[2.64px] text-[#A98088] max-sm:hidden">
                                {{ $channel['label'] }}
                            </span>
                            <span class="flex min-w-0 flex-col gap-[3.25px] max-sm:h-[89px] max-sm:flex-1 max-sm:gap-1.5">
                                <span class="hidden text-[11px] font-medium uppercase leading-[13px] tracking-[1.5px] text-[#A98088] max-sm:block">
                                    {{ $channel['label'] }}
                                </span>
                                <span class="truncate font-cormorant text-[28px] font-medium leading-[43px] text-[#5B2730] max-sm:text-[22px] max-sm:leading-[27px]">
                                    {{ $channel['title'] }}
                                </span>
                                <span class="text-[13px] font-normal leading-5 text-[#A98088] max-sm:truncate max-sm:font-sans max-sm:font-semibold max-sm:leading-4 max-sm:text-[#7A4751]">
                                    {{ $channel['meta'] }}
                                </span>
                                <span class="hidden text-[12px] font-medium leading-[15px] text-[#5B2730] underline max-sm:block">
                                    {{ $channel['action'] }}
                                </span>
                            </span>
                            <span class="text-[18px] font-normal leading-7 tracking-[-0.45px] text-[#A98088] max-sm:w-3 max-sm:leading-[21px]">
                                →
                            </span>
                        </a>
                    @endforeach
                </div>

                <aside class="flex w-[420px] flex-col gap-9 max-lg:w-full max-lg:max-w-[520px] max-lg:justify-self-center max-sm:max-w-none max-sm:px-5 max-sm:pb-10">
                    <section class="overflow-hidden border border-[#E8DAD0] bg-white max-sm:h-[289px] max-sm:w-full max-sm:p-6">
                        <div class="h-[180px] w-full bg-[#F8EDE7] max-sm:hidden">
                            <iframe
                                class="block h-full w-full border-0"
                                src="{{ $showroomMapEmbedUrl }}"
                                title="SEVIA на Google Maps"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allowfullscreen
                            ></iframe>
                        </div>
                        <div class="flex min-h-[335.44px] flex-col items-start px-8 py-8 max-sm:min-h-0 max-sm:p-0">
                            <p class="m-0 text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:font-sans max-sm:leading-[13px]">
                                Шоу-рум Sévia
                            </p>
                            <h2 class="m-0 pt-[6.7px] font-cormorant text-[20px] font-medium leading-[29px] text-[#5B2730] max-sm:pt-2 max-sm:text-[18px] max-sm:leading-7">
                                Київ, вул. Михайла Максимовича 9-Д
                            </h2>
                            <p class="m-0 text-[13px] font-normal leading-5 text-[#A98088] max-sm:font-sans max-sm:font-semibold max-sm:leading-4">
                                Шоу-рум нішевої парфумерії та білизни
                            </p>

                            <dl class="my-0 flex w-full flex-col gap-2 py-[14.7px] pb-[18.69px] text-[14px] font-normal leading-[22px] text-[#7A4751] max-sm:pb-5 max-sm:text-[13px] max-sm:font-semibold max-sm:leading-4">
                                <div class="flex justify-between border-b border-[#EFE4D9] pb-2 max-sm:pb-1.5">
                                    <dt>Пн–Пт</dt>
                                    <dd class="m-0">10:00 — 19:00</dd>
                                </div>
                                <div class="flex justify-between border-b border-[#EFE4D9] pb-2 max-sm:pb-1.5">
                                    <dt>Сб</dt>
                                    <dd class="m-0">10:00 — 17:00</dd>
                                </div>
                                <div class="flex justify-between pb-2 max-sm:border-b max-sm:border-[#EFE4D9] max-sm:pb-1.5">
                                    <dt>Нд</dt>
                                    <dd class="m-0 text-[#A98088]">Вихідний</dd>
                                </div>
                            </dl>

                            <a class="inline-flex h-[34.15px] items-center justify-center text-[13px] font-medium uppercase leading-5 tracking-[2.34px] text-[#5B2730] max-sm:h-[50px] max-sm:w-full max-sm:gap-2 max-sm:bg-[#F8EDE7] max-sm:font-sans max-sm:text-[12px] max-sm:font-semibold max-sm:leading-[15px] max-sm:tracking-[2px]" href="{{ $showroomMapUrl }}" target="_blank" rel="noopener">
                                <svg class="hidden h-[26px] w-[18px] max-sm:block" viewBox="0 0 18 26" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M9 0C4.029 0 0 4.029 0 9c0 6.75 9 17 9 17s9-10.25 9-17c0-4.971-4.029-9-9-9Z" fill="#EA4335"/>
                                    <circle cx="9" cy="9" r="3.3" fill="#FDFBF8"/>
                                </svg>
                                <span class="max-sm:hidden">більше у INSTAGRAM →</span>
                                <span class="hidden max-sm:inline">Відкрити у Google Maps</span>
                            </a>
                        </div>
                    </section>

                    <form class="flex flex-col gap-[18px] border border-[#E8DAD0] bg-[#FDFBF8] p-8 max-sm:hidden" action="{{ route('contacts') }}" method="get">
                        <h2 class="m-0 pb-1 font-cormorant text-[24px] font-medium leading-[37px] text-[#5B2730]">
                            Написати нам
                        </h2>

                        <label class="flex flex-col gap-[5.99px]">
                            <span class="text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088]">Імʼя</span>
                            <input class="h-11 border border-[#E8DAD0] bg-[#FDFBF8] px-3.5 py-3 text-[15px] leading-[18px] text-[#5B2730] placeholder:text-[#A98088] focus:border-[#5B2730] focus:outline-none" type="text" name="name" placeholder="Як до вас звертатися">
                        </label>

                        <label class="flex flex-col gap-1.5">
                            <span class="text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088]">Контакт для відповіді</span>
                            <input class="h-11 border border-[#E8DAD0] bg-[#FDFBF8] px-3.5 py-3 text-[15px] leading-[18px] text-[#5B2730] placeholder:text-[#A98088] focus:border-[#5B2730] focus:outline-none" type="text" name="contact" placeholder="Email або @telegram">
                        </label>

                        <label class="flex flex-col gap-1.5">
                            <span class="text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088]">Повідомлення</span>
                            <textarea class="h-20 resize-none border border-[#E8DAD0] bg-[#FDFBF8] px-3.5 py-3 text-[15px] leading-[18px] text-[#5B2730] placeholder:text-[#A98088] focus:border-[#5B2730] focus:outline-none" name="message" placeholder="Коротко опишіть запит"></textarea>
                        </label>

                        <button class="mt-0 inline-flex h-[50.15px] w-full items-center justify-center bg-[#5B2730] px-7 py-[13.25px] text-[13px] font-medium uppercase leading-5 tracking-[1.56px] text-[#FDFBF8]" type="submit">
                            Надіслати
                        </button>
                    </form>
                </aside>
            </div>
        </div>
    </section>
@endsection
