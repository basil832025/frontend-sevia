@extends('front.sevia::layouts.app')

@section('title', $title . ' | Sevia')
@section('meta_description', $description)

@section('content')
    <main class="discovery-guide-page px-6 py-12 text-[#5B2730] sm:py-20">
        <article>
            <h1 class="discovery-guide-title font-cormorant">{{ $title }}</h1>
            <div class="discovery-guide text-[#7A4751]">
                {!! $article !!}
            </div>
            <a class="mt-8 inline-flex border border-[#5B2730] px-6 py-4 uppercase" href="{{ route('discovery-53') }}">
                Зібрати свій Discovery 5 × 3 мл
            </a>
        </article>
    </main>
    <style>
        .discovery-guide-page {
            width: 100%;
            max-width: 800px;
            margin-inline: auto;
        }
        .discovery-guide-title {
            margin: 0 0 32px;
            font-size: clamp(38px, 5vw, 60px);
            font-weight: 600;
            line-height: 1.05;
            text-align: center;
            text-wrap: balance;
        }
        .discovery-guide { line-height: 1.65; }
        .discovery-guide p, .discovery-guide ol { margin: 0 0 1em; }
        .discovery-guide h2 { margin: 1.2em 0 .5em; font-size: 1.5em; font-weight: 600; }
        .discovery-guide ol { padding-inline-start: 1.5em; list-style: decimal; }
        .discovery-guide li + li { margin-top: .5em; }
    </style>
@endsection
