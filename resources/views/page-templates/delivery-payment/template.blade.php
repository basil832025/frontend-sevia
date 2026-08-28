@extends('front.sevia::layouts.app')

@php
    $locale = app()->getLocale();
    $fallback = config('translatable.fallback_locale', 'uk');
    $content = is_array($content ?? null) ? $content : [];
    $t = fn (string $key, mixed $default = '') => data_get($content, "{$key}.{$locale}")
        ?? data_get($content, "{$key}.{$fallback}")
        ?? $default;
    $isHidden = fn (string $key): bool => (bool) data_get($content, "visibility.{$key}", false);
    $heading = $t('heading', $page->getTitleForLocale($locale) ?? 'Доставка та оплата');
    $delivery = collect(data_get($content, 'delivery_methods', []))
        ->reject(fn (array $item): bool => (bool) data_get($item, 'is_hidden', false))
        ->values();
    $payments = collect(data_get($content, 'payment_methods', []))
        ->reject(fn (array $item): bool => (bool) data_get($item, 'is_hidden', false))
        ->values();
@endphp

@section('title', ($page->meta_title[$locale] ?? $page->meta_title[$fallback] ?? $heading) . ' | Sevia')
@section('meta_description', $page->meta_description[$locale] ?? $page->meta_description[$fallback] ?? $t('intro', ''))

@section('content')
    <section class="bg-white px-[68px] py-20 max-lg:px-6 max-sm:px-5 max-sm:py-8">
        <header class="mx-auto max-w-[760px] text-center max-sm:text-left">
            <p class="m-0 text-[11px] font-medium uppercase tracking-[3px] text-[#A98088]">{{ $t('eyebrow', 'Сервіс') }}</p>
            <h1 class="m-0 pt-3 font-cormorant text-[72px] font-medium leading-none text-[#5B2730] max-sm:text-[38px]">{{ $heading }}</h1>
            @if ($intro = $t('intro'))
                <p class="m-0 pt-4 text-[16px] leading-[25px] text-[#7A4751]">{{ $intro }}</p>
            @endif
        </header>

        <div class="mx-auto mt-16 flex max-w-[980px] flex-col gap-14 max-sm:mt-8">
            @if (! $isHidden('delivery') && $delivery->isNotEmpty())
                <section>
                    <h2 class="m-0 font-cormorant text-[48px] font-medium text-[#5B2730] max-sm:text-[28px]">Доставка</h2>
                    <ol class="m-0 mt-5 list-none border-t border-[#EFE4D9] p-0">
                        @foreach ($delivery as $item)
                            @php
                                $method = data_get($item, 'method', []);
                                $title = data_get($method, "title.{$locale}") ?? data_get($method, "title.{$fallback}");
                                $text = data_get($method, "text.{$locale}") ?? data_get($method, "text.{$fallback}");
                            @endphp
                            <li class="grid grid-cols-[44px_minmax(0,1fr)] gap-4 border-b border-[#EFE4D9] py-5">
                                <span class="font-cormorant text-[22px] text-[#CD9B97]">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span>
                                    <strong class="block text-[15px] text-[#5B2730]">{{ $title }}</strong>
                                    <span class="block pt-1 text-[13px] leading-5 text-[#7A4751]">{{ $text }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endif

            @if (! $isHidden('payment') && $payments->isNotEmpty())
                <section>
                    <h2 class="m-0 font-cormorant text-[48px] font-medium text-[#5B2730] max-sm:text-[28px]">Оплата</h2>
                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        @foreach ($payments as $item)
                            @php
                                $method = data_get($item, 'method', []);
                                $title = data_get($method, "title.{$locale}") ?? data_get($method, "title.{$fallback}");
                                $text = data_get($method, "text.{$locale}") ?? data_get($method, "text.{$fallback}");
                            @endphp
                            <article class="border border-[#E8DAD0] bg-[#FDFBF8] p-5">
                                <h3 class="m-0 text-[15px] font-semibold text-[#5B2730]">{{ $title }}</h3>
                                <p class="m-0 pt-2 text-[13px] leading-5 text-[#7A4751]">{{ $text }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @foreach (['return_policy' => 'Обмін і повернення', 'original_guarantee' => 'Гарантія оригіналу', 'showroom' => 'Шоу-рум'] as $key => $label)
                @if (! $isHidden($key) && ($html = $t($key)))
                    <section>
                        <h2 class="m-0 font-cormorant text-[42px] font-medium text-[#5B2730] max-sm:text-[28px]">{{ $label }}</h2>
                        <div class="prose mt-4 max-w-none text-[#7A4751]">{!! $html !!}</div>
                    </section>
                @endif
            @endforeach

            @if (! $isHidden('cta') && ($ctaLabel = $t('cta.label')))
                <a class="inline-flex h-12 w-fit items-center justify-center bg-[#5B2730] px-8 text-[12px] font-medium uppercase tracking-[1.4px] text-white" href="{{ data_get($content, 'cta.url', route('contacts')) }}">
                    {{ $ctaLabel }}
                </a>
            @endif
        </div>
    </section>
@endsection
