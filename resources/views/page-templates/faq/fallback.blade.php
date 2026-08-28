@extends('front.sevia::layouts.app')

@section('title', 'Часті запитання | Sevia')
@section('meta_description', 'Відповіді на часті запитання Sevia про доставку, парфуми, білизну, оплату та повернення.')

@php
    $telegramUrl = 'https://t.me/sevia';

    $sections = [
        [
            'id' => 'orders',
            'title' => 'Замовлення та доставка',
            'items' => [
                ['question' => 'Скільки часу йде доставка?', 'answer' => '1–2 дні Новою Поштою. Замовлення до 16:00 · відправляємо того ж дня.', 'open' => true],
                ['question' => 'Чи можна забрати самовивозом?', 'answer' => 'Так, самовивіз доступний із шоу-руму за попереднім записом у Telegram.'],
                ['question' => 'Як відстежити посилку?', 'answer' => 'Після відправлення ми надішлемо номер ТТН у месенджер або на контакт, який ти вказала в замовленні.'],
            ],
        ],
        [
            'id' => 'perfumes',
            'title' => 'Парфуми',
            'items' => [
                ['question' => 'Чим розпив відрізняється від повного флакона?', 'answer' => 'Розпив — це оригінальний аромат, перелитий у маленький флакон. Так можна спробувати парфум до покупки повного обʼєму.'],
                ['question' => 'Як обрати обʼєм деканту?', 'answer' => '3 мл підходить для знайомства, 5–10 мл — для тесту протягом кількох днів, більші обʼєми — якщо аромат уже подобається.'],
                ['question' => 'Що таке Discovery 5×3?', 'answer' => 'Це сет із пʼяти ароматів по 3 мл, який допомагає спокійно протестувати кілька напрямів і знайти свій.'],
            ],
        ],
        [
            'id' => 'lingerie',
            'title' => 'Білизна',
            'items' => [
                ['question' => 'Як обрати розмір?', 'answer' => 'Напиши нам параметри або звичний розмір — допоможемо підібрати посадку перед оформленням замовлення.'],
                ['question' => 'Чи можна повернути приміряну білизну?', 'answer' => 'Ні, білизна після примірки не повертається з гігієнічних причин. Перевіряй розмір і комплектацію під час отримання.'],
            ],
        ],
        [
            'id' => 'payment-returns',
            'title' => 'Оплата та повернення',
            'items' => [
                ['question' => 'Чи безпечно платити карткою?', 'answer' => 'Так, оплата проходить через захищений платіжний шлюз. Ми не зберігаємо дані картки.'],
                ['question' => 'Як повернути товар?', 'answer' => 'Обмін або повернення можна оформити під час отримання, якщо товар не підійшов або має пошкодження.'],
            ],
        ],
    ];
@endphp

@section('content')
    <nav class="flex h-[50.15px] items-center gap-2.5 px-[68.04px] pb-2 pt-[22px] text-[13px] font-normal leading-5 max-lg:px-6 max-sm:h-[33px] max-sm:items-start max-sm:gap-1.5 max-sm:px-5 max-sm:pb-1 max-sm:pt-4 max-sm:text-[11px] max-sm:leading-[13px]" aria-label="Breadcrumb">
        <a class="text-[#7A4751] hover:text-[#5B2730] max-sm:text-[#A98088]" href="{{ route('home') }}">Sevia</a>
        <span class="text-[#E8DAD0] max-sm:text-[#A98088]">/</span>
        <span class="text-[#5B2730] max-sm:font-medium max-sm:text-[#7A4751]">Часті запитання</span>
    </nav>

    <section class="bg-white px-[68.04px] pb-[100px] pt-[75.6px] max-lg:px-6 max-sm:bg-[#FDFBF8] max-sm:px-0 max-sm:py-0">
        <div class="mx-auto flex w-full max-w-[1303.92px] flex-col">
            <header class="flex min-h-[193.1px] flex-col items-center gap-3 pb-[45.36px] text-center max-sm:h-[163px] max-sm:min-h-0 max-sm:items-start max-sm:px-5 max-sm:pb-6 max-sm:pt-3 max-sm:text-left">
                <p class="m-0 text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:font-sans max-sm:leading-[13px]">
                    Допомога
                </p>
                <h1 class="m-0 font-cormorant text-[78.6px] font-medium leading-[79px] tracking-[-1.179px] text-[#5B2730] max-sm:text-[38px] max-sm:leading-[46px] max-sm:tracking-[-1.18px]">
                    Часті запитання
                </h1>
                <p class="m-0 max-w-[592px] pt-[1.74px] text-[16px] font-normal leading-[25px] text-[#7A4751] max-sm:w-full max-sm:pt-0 max-sm:font-sans max-sm:text-[14px] max-sm:leading-[22px]">
                    Не знайшла відповідь? Пиши в Telegram <a class="text-[#5B2730] hover:underline" href="{{ $telegramUrl }}" target="_blank" rel="noopener">@sevia</a> — відповімо за годину.
                </p>
            </header>

            <div class="grid min-h-[1203.83px] grid-cols-[240px_minmax(0,1003.44px)] gap-[60.48px] border-t border-[#E8DAD0] pt-[60.47px] max-lg:min-h-0 max-lg:grid-cols-1 max-lg:gap-12 max-sm:border-t-0 max-sm:pt-0">
                <aside class="flex h-[426.75px] w-60 flex-col items-start gap-3.5 p-0 max-lg:hidden">
                    <p class="m-0 h-[17px] w-60 text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088]">
                        Розділи
                    </p>
                    <ol class="m-0 flex h-[181.6px] w-60 list-none flex-col border-t border-[#E8DAD0] p-0">
                        @foreach ($sections as $section)
                            <li class="h-[45.15px] w-60">
                                <a class="flex h-[44.15px] w-60 items-center py-[11.25px] text-[13px] font-normal leading-5 tracking-[0.52px] text-[#7A4751] hover:text-[#5B2730]" href="#{{ $section['id'] }}">
                                    {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} — {{ $section['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ol>

                    <div class="flex h-[200.15px] w-60 flex-col items-start gap-1.5 bg-[#F8EDE7] px-5 pb-5 pt-[34px]">
                        <h2 class="m-0 h-[35px] w-[200px] font-cormorant text-[22px] font-medium leading-[34px] text-[#5B2730]">
                            Не знайшла?
                        </h2>
                        <div class="h-[65px] w-[200px] pb-1.5">
                            <p class="m-0 text-[13px] font-normal leading-5 text-[#7A4751]">
                                Пиши у Telegram · у середньому відповідаємо за 18 хвилин у робочий час.
                            </p>
                        </div>
                        <a class="inline-flex h-[34.15px] w-28 items-center justify-center px-0.5 py-[5.25px] text-center text-[13px] font-medium uppercase leading-5 tracking-[2.34px] text-[#5B2730]" href="{{ $telegramUrl }}" target="_blank" rel="noopener">
                            Написати →
                        </a>
                    </div>
                </aside>

                <div class="flex flex-col gap-[60.5px] max-sm:w-full max-sm:gap-8 max-sm:px-5 max-sm:pb-8">
                    @foreach ($sections as $section)
                        <section id="{{ $section['id'] }}" class="scroll-mt-36 max-sm:flex max-sm:w-full max-sm:flex-col max-sm:gap-3">
                            <h2 class="m-0 pb-5 font-cormorant text-[60px] font-medium leading-[63px] tracking-[-0.6px] text-[#5B2730] max-sm:pb-0 max-sm:text-[24px] max-sm:leading-[29px] max-sm:tracking-normal">
                                {{ $section['title'] }}
                            </h2>
                            <div class="border-t border-[#EFE4D9] max-sm:w-full max-sm:border-t-0">
                                @foreach ($section['items'] as $item)
                                    <details class="group border-b border-[#EFE4D9] @if (! empty($item['open'])) bg-[#F8EDE7] px-[18px] max-sm:bg-transparent max-sm:px-0 @endif" @if (! empty($item['open'])) open @endif>
                                        <summary class="flex min-h-[60px] cursor-pointer list-none items-center justify-between gap-6 py-[18px] text-[15px] font-semibold leading-[23px] text-[#5B2730] group-open:max-sm:min-h-[55px] group-open:max-sm:pb-2 group-open:max-sm:pt-[18px] max-sm:min-h-[65px] max-sm:gap-0 max-sm:py-[18px] max-sm:font-medium max-sm:leading-[22px] [&::-webkit-details-marker]:hidden">
                                            <span class="max-sm:w-[329px]">{{ $item['question'] }}</span>
                                            <span class="font-cormorant text-[24px] font-normal leading-none text-[#A85D66] group-open:hidden max-sm:w-6 max-sm:text-right max-sm:leading-[29px] max-sm:text-[#CD9B97]">+</span>
                                            <span class="hidden font-cormorant text-[24px] font-normal leading-none text-[#A85D66] group-open:block max-sm:w-6 max-sm:text-right max-sm:leading-[29px] max-sm:text-[#CD9B97]">−</span>
                                        </summary>
                                        <p class="m-0 max-w-[592px] pb-[18px] text-[16px] font-normal leading-[26px] text-[#7A4751] max-sm:w-full max-sm:text-[13.5px] max-sm:leading-5">
                                            {{ $item['answer'] }}
                                        </p>
                                    </details>
                                @endforeach
                            </div>
                        </section>
                    @endforeach

                    <section class="hidden max-sm:flex max-sm:h-[208px] max-sm:w-full max-sm:flex-col max-sm:gap-3 max-sm:rounded-lg max-sm:border max-sm:border-[#E8DAD0] max-sm:bg-white max-sm:p-6">
                        <p class="m-0 text-[11px] font-medium uppercase leading-[13px] tracking-[3.08px] text-[#A98088]">
                            Не знайшла?
                        </p>
                        <p class="m-0 font-cormorant text-[22px] font-medium leading-7 text-[#5B2730]">
                            Пиши у Telegram — у середньому відповідаємо за 18 хвилин у робочий час.
                        </p>
                        <a class="inline-flex h-[39px] w-full items-center justify-center bg-[#F8EDE7] px-4 py-3 text-[12px] font-semibold uppercase leading-[15px] tracking-[2px] text-[#5B2730]" href="{{ $telegramUrl }}" target="_blank" rel="noopener">
                            Написати →
                        </a>
                    </section>
                </div>
            </div>
        </div>
    </section>
@endsection
