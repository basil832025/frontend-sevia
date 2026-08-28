@extends('front.sevia::layouts.app')

@php
    $locale = app()->getLocale();
    $fallback = config('translatable.fallback_locale', 'uk');
    $content = is_array($content ?? null) ? $content : [];
    $t = fn (string $key, mixed $default = '') => data_get($content, "{$key}.{$locale}")
        ?? data_get($content, "{$key}.{$fallback}")
        ?? $default;
    $heading = $t('heading', $page->getTitleForLocale($locale) ?? 'FAQ');
    $sections = data_get($content, 'sections', []);
@endphp

@section('title', ($page->meta_title[$locale] ?? $page->meta_title[$fallback] ?? $heading) . ' | Sevia')
@section('meta_description', $page->meta_description[$locale] ?? $page->meta_description[$fallback] ?? $t('intro', ''))

@section('content')
    <nav class="flex h-[50px] items-center gap-2.5 px-[68px] pb-2 pt-[22px] text-[13px] max-lg:px-6 max-sm:h-[33px] max-sm:items-start max-sm:gap-1.5 max-sm:px-5 max-sm:pb-1 max-sm:pt-4 max-sm:text-[11px]" aria-label="Breadcrumb">
        <a class="text-[#7A4751] hover:text-[#5B2730]" href="{{ route('home') }}">Sevia</a>
        <span class="text-[#E8DAD0]">/</span>
        <span class="text-[#5B2730]">{{ $heading }}</span>
    </nav>

    <section class="bg-white px-[68px] pb-28 pt-16 max-lg:px-6 max-sm:px-5 max-sm:pb-10 max-sm:pt-6">
        <header class="mx-auto max-w-[760px] text-center max-sm:text-left">
            <p class="m-0 text-[11px] font-medium uppercase tracking-[3px] text-[#A98088]">{{ $t('eyebrow', 'Допомога') }}</p>
            <h1 class="m-0 pt-3 font-cormorant text-[72px] font-medium leading-none text-[#5B2730] max-sm:text-[38px]">{{ $heading }}</h1>
            @if ($intro = $t('intro'))
                <p class="m-0 pt-4 text-[16px] leading-[25px] text-[#7A4751] max-sm:text-[14px] max-sm:leading-[22px]">{{ $intro }}</p>
            @endif
        </header>

        <div class="mx-auto mt-16 grid max-w-[1180px] grid-cols-[240px_minmax(0,1fr)] gap-14 max-lg:grid-cols-1 max-sm:mt-8 max-sm:gap-8">
            <aside class="max-lg:hidden">
                <p class="m-0 text-[11px] font-medium uppercase tracking-[3px] text-[#A98088]">Розділи</p>
                <ol class="m-0 mt-4 list-none border-t border-[#E8DAD0] p-0">
                    @foreach ($sections as $section)
                        @php $sectionTitle = data_get($section, "title.{$locale}") ?? data_get($section, "title.{$fallback}"); @endphp
                        <li class="border-b border-[#E8DAD0]">
                            <a class="block py-3 text-[13px] text-[#7A4751] hover:text-[#5B2730]" href="#faq-{{ $loop->iteration }}">
                                {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} - {{ $sectionTitle }}
                            </a>
                        </li>
                    @endforeach
                </ol>
            </aside>

            <div class="flex flex-col gap-12">
                @foreach ($sections as $section)
                    @php
                        $sectionTitle = data_get($section, "title.{$locale}") ?? data_get($section, "title.{$fallback}");
                        $items = data_get($section, 'items', []);
                    @endphp
                    <section id="faq-{{ $loop->iteration }}" class="scroll-mt-32">
                        <h2 class="m-0 font-cormorant text-[44px] font-medium leading-tight text-[#5B2730] max-sm:text-[28px]">{{ $sectionTitle }}</h2>
                        <div class="mt-5 border-t border-[#EFE4D9]">
                            @foreach ($items as $item)
                                @php
                                    $question = data_get($item, "question.{$locale}") ?? data_get($item, "question.{$fallback}");
                                    $answer = data_get($item, "answer.{$locale}") ?? data_get($item, "answer.{$fallback}");
                                @endphp
                                <details class="group border-b border-[#EFE4D9]">
                                    <summary class="flex cursor-pointer list-none items-center justify-between gap-6 py-5 text-[15px] font-semibold text-[#5B2730] [&::-webkit-details-marker]:hidden">
                                        <span>{{ $question }}</span>
                                        <span class="text-xl group-open:hidden">+</span>
                                        <span class="hidden text-xl group-open:block">-</span>
                                    </summary>
                                    <div class="prose max-w-none pb-5 text-[15px] leading-6 text-[#7A4751]">{!! $answer !!}</div>
                                </details>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </div>
    </section>
@endsection
