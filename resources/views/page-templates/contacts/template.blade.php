@extends('front.sevia::layouts.app')

@php
    $locale = app()->getLocale();
    $fallback = config('translatable.fallback_locale', 'uk');
    $content = is_array($content ?? null) ? $content : [];
    $t = fn (string $key, mixed $default = '') => data_get($content, "{$key}.{$locale}")
        ?? data_get($content, "{$key}.{$fallback}")
        ?? data_get($content, $key)
        ?? $default;
    $heading = $t('heading', $page->getTitleForLocale($locale) ?? 'Контакти');
    $channels = data_get($content, 'channels', []);
@endphp

@section('title', ($page->meta_title[$locale] ?? $page->meta_title[$fallback] ?? $heading) . ' | Sevia')
@section('meta_description', $page->meta_description[$locale] ?? $page->meta_description[$fallback] ?? $t('subtitle', ''))

@section('content')
    <section class="bg-white px-[68px] py-20 max-lg:px-6 max-sm:px-5 max-sm:py-8">
        <header class="mx-auto max-w-[760px] text-center max-sm:text-left">
            <p class="m-0 text-[11px] font-medium uppercase tracking-[3px] text-[#A98088]">{{ $t('eyebrow', 'Звʼязок') }}</p>
            <h1 class="m-0 pt-3 font-cormorant text-[72px] font-medium leading-none text-[#5B2730] max-sm:text-[38px]">{{ $heading }}</h1>
            @if ($subtitle = $t('subtitle'))
                <p class="m-0 pt-4 text-[16px] leading-[25px] text-[#7A4751]">{{ $subtitle }}</p>
            @endif
        </header>

        <div class="mx-auto mt-16 grid max-w-[1180px] grid-cols-[minmax(0,1fr)_380px] gap-14 max-lg:grid-cols-1 max-sm:mt-8">
            <div class="border-t border-[#EFE4D9]">
                @foreach ($channels as $channel)
                    @php
                        $label = data_get($channel, "label.{$locale}") ?? data_get($channel, "label.{$fallback}");
                        $title = data_get($channel, "title.{$locale}") ?? data_get($channel, "title.{$fallback}");
                        $meta = data_get($channel, "meta.{$locale}") ?? data_get($channel, "meta.{$fallback}");
                        $action = data_get($channel, "action.{$locale}") ?? data_get($channel, "action.{$fallback}") ?? 'Відкрити';
                    @endphp
                    <a class="grid min-h-[110px] grid-cols-[180px_minmax(0,1fr)_24px] items-center gap-6 border-b border-[#EFE4D9] text-left hover:bg-[#FDFBF8] max-sm:grid-cols-[1fr_20px]" href="{{ data_get($channel, 'href', '#') }}">
                        <span class="text-[12px] uppercase tracking-[2px] text-[#A98088] max-sm:hidden">{{ $label }}</span>
                        <span class="flex flex-col">
                            <span class="hidden text-[11px] uppercase tracking-[1.5px] text-[#A98088] max-sm:block">{{ $label }}</span>
                            <span class="font-cormorant text-[28px] text-[#5B2730]">{{ $title }}</span>
                            <span class="text-[13px] text-[#7A4751]">{{ $meta }}</span>
                            <span class="pt-1 text-[12px] uppercase tracking-[1px] text-[#5B2730]">{{ $action }}</span>
                        </span>
                        <span class="text-[#A98088]">→</span>
                    </a>
                @endforeach
            </div>

            <aside class="border border-[#E8DAD0] bg-[#FDFBF8] p-8">
                <h2 class="m-0 font-cormorant text-[28px] font-medium text-[#5B2730]">Шоу-рум</h2>
                @if ($address = $t('address'))
                    <p class="m-0 pt-3 text-[15px] leading-6 text-[#7A4751]">{{ $address }}</p>
                @endif
                @if ($hours = $t('working_hours'))
                    <p class="m-0 whitespace-pre-line pt-4 text-[13px] leading-5 text-[#A98088]">{{ $hours }}</p>
                @endif
                @if ($mapUrl = data_get($content, 'map_url'))
                    <a class="mt-6 inline-flex h-11 items-center justify-center bg-[#5B2730] px-6 text-[12px] font-medium uppercase tracking-[1.4px] text-white" href="{{ $mapUrl }}" target="_blank" rel="noopener">Google Maps</a>
                @endif
            </aside>
        </div>
    </section>
@endsection
