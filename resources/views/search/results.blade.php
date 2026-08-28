@extends('front.sevia::layouts.app')

@section('title', 'Пошук | Sevia')
@section('meta_description', 'Пошук парфумів Sevia за назвою або артикулом.')

@php
    $sortLabels = [
        'relevance' => 'За релевантністю',
        'price_asc' => 'Ціна ↑',
        'price_desc' => 'Ціна ↓',
        'new' => 'Новинки',
    ];

    $pageUrl = fn (int $targetPage) => request()->fullUrlWithQuery(['page' => $targetPage]);
@endphp

@section('content')
    <section class="bg-white px-[68px] pb-10 pt-[88px] max-lg:px-6 max-sm:px-5 max-sm:pb-8 max-sm:pt-10">
        <div class="mx-auto flex w-full max-w-[1304px] flex-col items-center">
            <p class="m-0 pb-5 text-center text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088]">
                Пошук по Sevia
            </p>

            <form class="flex h-20 w-full max-w-[720px] items-center gap-4 border-b border-[#E8DAD0] px-1 pb-4 pt-2.5" action="{{ route('search.index') }}" method="GET" role="search">
                <span class="flex h-[37px] w-[15px] items-center justify-center text-[24px] leading-[37px] text-[#A98088]" aria-hidden="true">⌕</span>
                <input class="min-w-0 flex-1 bg-transparent font-cormorant text-[44px] leading-[53px] tracking-[0.44px] text-[#5B2730] outline-none placeholder:text-[#A98088] max-sm:text-[34px] max-sm:leading-10" type="search" name="q" value="{{ $query }}" placeholder="Пошук" autocomplete="off">
                @if ($query !== '')
                    <a class="grid size-9 place-items-center text-[24px] leading-6 text-[#A98088]" href="{{ route('search.index') }}" aria-label="Очистити пошук">×</a>
                @else
                    <button class="grid size-9 place-items-center text-[24px] leading-6 text-[#A98088]" type="submit" aria-label="Шукати">→</button>
                @endif
            </form>

            <p class="m-0 pt-[22px] text-center text-[14px] leading-[22px] text-[#A98088]">
                @if ($query !== '')
                    Знайдено {{ $productsTotal }} ароматів за запитом {{ $query }}
                @else
                    Введіть назву або артикул аромату
                @endif
            </p>
        </div>
    </section>

    <section class="bg-white px-[68px] pb-[44px] pt-[55px] max-lg:px-6 max-sm:px-5 max-sm:pt-8">
        <div class="mx-auto flex w-full max-w-[1304px] items-end justify-between gap-8 max-md:flex-col max-md:items-start">
            <h1 class="m-0 font-cormorant text-[76px] font-medium leading-none tracking-[-1.134px] text-[#5B2730] max-sm:text-[46px]">
                Товари
            </h1>

            <form class="flex items-center gap-8 pb-2 text-[12px] uppercase leading-[19px] tracking-[2.64px] text-[#A98088] max-sm:w-full max-sm:flex-wrap max-sm:gap-4" method="GET" action="{{ route('search.index') }}">
                <input type="hidden" name="q" value="{{ $query }}">
                <span>{{ $productsTotal }} позицій</span>
                <label class="flex items-center gap-3">
                    <span>Сортування</span>
                    <select class="h-[46px] w-[180px] border border-[#E8DAD0] bg-[#FDFBF8] px-[18px] text-[14px] font-medium normal-case leading-[22px] tracking-[0.28px] text-[#5B2730]" name="sort" onchange="this.form.submit()">
                        @foreach ($sortLabels as $value => $label)
                            <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </form>
        </div>
    </section>

    <section class="bg-white px-[68px] pb-[120px] pt-0 max-lg:px-6 max-sm:px-5 max-sm:pb-16">
        <div class="mx-auto w-full max-w-[1304px]">
            @if ($productsTotal === 0)
                <section class="box-border flex min-h-[520px] w-full flex-col items-center justify-center border border-[#EFE4D9] bg-white px-[72px] py-24 text-center max-md:px-6">
                    <p class="m-0 pb-3.5 text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088]">Нічого не знайдено</p>
                    <h2 class="m-0 max-w-[560px] font-cormorant text-[46px] font-medium leading-[52px] text-[#5B2730] max-sm:text-[34px] max-sm:leading-10">
                        За запитом {{ $query !== '' ? $query : 'пошуку' }} поки нічого немає.
                    </h2>
                    <p class="m-0 max-w-[460px] pt-[18px] text-[15px] leading-6 text-[#A98088]">
                        Спробуйте перевірити написання або ввести артикул товару.
                    </p>
                    <a class="mt-8 inline-flex h-[50px] items-center justify-center bg-[#5B2730] px-7 text-[13px] font-medium uppercase leading-5 tracking-[1.56px] text-[#FDFBF8]" href="{{ route('catalog.index') }}">
                        До всіх парфумів
                    </a>
                </section>
            @else
                <div class="grid grid-cols-4 gap-x-[30px] gap-y-[58px] max-xl:grid-cols-3 max-md:grid-cols-2 max-sm:gap-x-3 max-sm:gap-y-7">
                    @foreach ($products as $product)
                        <article class="group flex min-h-[596px] flex-col max-sm:min-h-[360px]">
                            <a class="relative flex h-[404px] items-center justify-center bg-white p-8 max-sm:h-[210px] max-sm:bg-[#FDFBF8] max-sm:p-4" href="{{ $product['url'] }}">
                                <span class="absolute inset-0 bg-[linear-gradient(143.13deg,rgba(176,127,110,0)_49.92%,rgba(176,127,110,0.16)_49.92%,rgba(176,127,110,0.16)_50.08%,rgba(176,127,110,0)_50.08%),linear-gradient(36.87deg,rgba(176,127,110,0)_49.92%,rgba(176,127,110,0.16)_49.92%,rgba(176,127,110,0.16)_50.08%,rgba(176,127,110,0)_50.08%)] opacity-0 transition group-hover:opacity-100"></span>
                                @if ($product['badge'])
                                    <span class="absolute left-3.5 top-3.5 z-10 border border-[rgba(91,39,48,0.16)] bg-white/95 px-2.5 py-1 text-[9.5px] font-semibold uppercase leading-[15px] tracking-[1.33px] text-[#5B2730]">{{ $product['badge'] }}</span>
                                @endif
                                <img class="relative z-0 max-h-full max-w-full object-contain transition duration-300 group-hover:scale-[1.03]" src="{{ $product['image'] }}" alt="{{ $product['brand'] }} {{ $product['name'] }}">
                            </a>

                            <div class="flex flex-1 flex-col pt-3.5">
                                <p class="m-0 text-[12px] uppercase leading-[19px] tracking-[2.64px] text-[#A98088] max-sm:text-[9px] max-sm:leading-3">{{ $product['meta'] }} {{ $product['unit'] }}</p>
                                <p class="m-0 mt-3 text-[13px] font-medium leading-5 tracking-[0.52px] text-[#7A4751]">{{ $product['brand'] }}</p>
                                <h2 class="m-0 mt-2 font-cormorant text-[22px] font-medium leading-[25px] text-[#5B2730]">{{ $product['name'] }}</h2>
                                <p class="m-0 mt-2 text-[13px] leading-5 text-[#A98088]">{{ $product['notes'] }}</p>
                                <p class="m-0 mt-auto pt-5 font-cormorant text-[22px] font-medium leading-[34px] text-[#5B2730]">
                                    {{ $product['price_label'] }}
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($lastPage > 1)
                    <nav class="mt-[72px] flex items-center justify-between border-t border-[#EFE4D9] pt-9 text-[12px] font-semibold uppercase tracking-[2px] text-[#7A4751]" aria-label="Pagination">
                        <a class="{{ $page <= 1 ? 'pointer-events-none opacity-40' : '' }}" href="{{ $pageUrl(max(1, $page - 1)) }}">← Назад</a>
                        <div class="flex items-center gap-4">
                            @for ($i = 1; $i <= $lastPage; $i++)
                                @if ($i <= 3 || $i === $lastPage || abs($i - $page) <= 1)
                                    <a class="grid size-8 place-items-center {{ $page === $i ? 'bg-[#7A2535] text-white' : '' }}" href="{{ $pageUrl($i) }}">{{ $i }}</a>
                                @elseif ($i === 4)
                                    <span>…</span>
                                @endif
                            @endfor
                        </div>
                        <a class="{{ $page >= $lastPage ? 'pointer-events-none opacity-40' : '' }}" href="{{ $pageUrl(min($lastPage, $page + 1)) }}">Далі →</a>
                    </nav>
                @endif
            @endif
        </div>
    </section>
@endsection
