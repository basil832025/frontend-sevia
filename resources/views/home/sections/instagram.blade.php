@php
    $posts = collect($instagramPosts ?? [])->take(6)->values();

    if ($posts->isEmpty()) {
        $fallbackImages = [
            'bestseller-1.png',
            'bestseller-2.png',
            'bestseller-3.png',
            'discount-1.png',
            'discount-2.png',
            'discount-3.png',
        ];

        $posts = collect($fallbackImages)->map(fn (string $image, int $index): array => [
            'id' => 'fallback-' . $index,
            'caption' => 'Sevia в Instagram',
            'image' => asset('vendor/frontend-sevia/images/' . $image),
            'permalink' => 'https://www.instagram.com/sevia.parfume/',
        ]);
    }

    $instagramUrl = $posts->first()['permalink'] ?? 'https://www.instagram.com/sevia.parfume/';
@endphp

@once
    <style>
        .sevia-instagram {
            position: relative;
            isolation: isolate;
            overflow: visible;
            background: #ffffff;
            width: 100%;
            min-height: clamp(885px, 60.79vw, 1167px);
            padding: clamp(180px, 12.5vw, 239px) clamp(75.6px, 5.25vw, 100.8px) 0;
        }

        .sevia-instagram__floral {
            position: absolute;
            right: 0;
            top: -282px;
            z-index: 0;
            width: auto;
            height: clamp(520px, 67.64vw, 974px);
            max-width: none;
            object-fit: contain;
            opacity: 1;
            pointer-events: none;
        }

        .sevia-instagram__floral--mobile {
            display: none;
        }

        .sevia-instagram__inner {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1718.35px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: clamp(64px, 4.44vw, 85.33px);
        }

        .sevia-instagram__header {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 36px;
        }

        .sevia-instagram__eyebrow {
            margin: 0;
            color: #a98088;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 2.2px;
            line-height: 16px;
            text-transform: uppercase;
        }

        .sevia-instagram__title {
            display: inline-flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 4px 10px;
            text-decoration: none;
        }

        .sevia-instagram__brand,
        .sevia-instagram__accent,
        .sevia-instagram__in {
            font-family: "Cormorant Garamond", serif;
            line-height: 1;
            letter-spacing: 0;
        }

        .sevia-instagram__brand {
            margin: 0;
            color: #5b2730;
            font-size: 40px;
            font-style: italic;
            font-weight: 400;
        }

        .sevia-instagram__in {
            color: #5b2730;
            font-size: 30px;
            font-weight: 400;
        }

        .sevia-instagram__accent {
            color: #d46568;
            font-size: 40px;
            font-weight: 500;
            text-transform: uppercase;
            transition: color .2s ease;
        }

        .sevia-instagram__title:hover .sevia-instagram__accent {
            color: #a85d66;
        }

        .sevia-instagram__rule {
            width: 100%;
            max-width: min(80.23%, 1378.63px);
            height: 1px;
            margin-top: 0;
            background: #e8dad0;
        }

        .sevia-instagram__grid {
            display: grid;
            width: 100%;
            height: clamp(360px, 27.5vw, 520px);
            grid-template-columns: 2.08fr 1fr 1fr 2.08fr;
            grid-template-rows: repeat(2, minmax(0, 1fr));
            gap: clamp(16px, 1.11vw, 18.7px);
        }

        .sevia-instagram__tile {
            position: relative;
            display: block;
            min-width: 0;
            min-height: 0;
            overflow: hidden;
            border: 1px solid #efe4d9;
            background: #ffffff;
        }

        .sevia-instagram__tile:nth-child(1) {
            grid-column: 1;
            grid-row: 1 / 3;
        }

        .sevia-instagram__tile:nth-child(2) {
            grid-column: 2;
            grid-row: 1;
        }

        .sevia-instagram__tile:nth-child(3) {
            grid-column: 3;
            grid-row: 1 / 3;
        }

        .sevia-instagram__tile:nth-child(4) {
            grid-column: 4;
            grid-row: 1;
        }

        .sevia-instagram__tile:nth-child(5) {
            grid-column: 2;
            grid-row: 2;
        }

        .sevia-instagram__tile:nth-child(6) {
            grid-column: 4;
            grid-row: 2;
        }

        .sevia-instagram__image {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform .5s ease;
        }

        .sevia-instagram__tile:hover .sevia-instagram__image {
            transform: scale(1.035);
        }

        .sevia-instagram__gradient {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(180deg, rgba(91, 39, 48, 0) 60%, rgba(91, 39, 48, .18) 100%);
            opacity: 0;
        }

        .sevia-instagram__heart {
            position: absolute;
            right: 12px;
            top: 12px;
            display: grid;
            width: 36px;
            height: 36px;
            place-items: center;
            border: 1px solid #e8dad0;
            border-radius: 999px;
            background: #ffffff;
        }

        .sevia-instagram__heart-icon {
            width: 18px;
            height: 18px;
        }

        @media (max-width: 1099px) {
            .sevia-instagram {
                min-height: 747.4px;
                padding: 0 51.85px;
            }

            .sevia-instagram__floral:not(.sevia-instagram__floral--mobile) {
                display: none;
            }

            .sevia-instagram__floral--mobile {
                display: block;
                left: 46.19%;
                right: -20.19%;
                top: -225.06px;
                width: auto;
                height: 915.86px;
            }

            .sevia-instagram__inner {
                max-width: 694.3px;
                gap: 34.57px;
            }

            .sevia-instagram__header {
                gap: 10.1px;
            }

            .sevia-instagram__eyebrow {
                color: #8a5d66;
                font-size: 13.3px;
                letter-spacing: 2.048px;
                line-height: 19px;
            }

            .sevia-instagram__brand,
            .sevia-instagram__accent {
                font-size: 41.8px;
                line-height: 42px;
            }

            .sevia-instagram__in {
                font-size: 30.1px;
                line-height: 30px;
            }

            .sevia-instagram__rule {
                max-width: 100%;
                margin-top: 16.565px;
            }

            .sevia-instagram__grid {
                height: clamp(474px, calc((100vw - 103.7px) * .8848), 614.26px);
                grid-template-columns: 2.06fr 1fr 1fr;
                grid-template-rows: repeat(3, minmax(0, 1fr));
                gap: 10.1px;
            }

            .sevia-instagram__tile:nth-child(1) {
                grid-column: 1;
                grid-row: 1 / 3;
            }

            .sevia-instagram__tile:nth-child(2) {
                grid-column: 2;
                grid-row: 1;
            }

            .sevia-instagram__tile:nth-child(3) {
                grid-column: 3;
                grid-row: 1 / 3;
            }

            .sevia-instagram__tile:nth-child(4) {
                grid-column: 1;
                grid-row: 3;
            }

            .sevia-instagram__tile:nth-child(5) {
                grid-column: 2;
                grid-row: 2;
            }

            .sevia-instagram__tile:nth-child(6) {
                grid-column: 3;
                grid-row: 3;
            }

            .sevia-instagram__heart {
                right: 8.64px;
                top: 8.64px;
                width: 25.92px;
                height: 25.92px;
            }

            .sevia-instagram__heart-icon {
                width: 12.24px;
                height: 12.24px;
            }
        }

        @media (max-width: 767px) {
            .sevia-instagram__grid {
                height: clamp(420px, 76vw, 560px);
            }
        }

        @media (max-width: 639px) {
            .sevia-instagram {
                padding: 64px 20px 56px;
            }

            .sevia-instagram__floral:not(.sevia-instagram__floral--mobile) {
                display: none;
            }

            .sevia-instagram__floral--mobile {
                display: block;
                left: 42%;
                right: -36%;
                top: -80px;
                height: 520px;
            }

            .sevia-instagram__inner {
                gap: 28px;
            }

            .sevia-instagram__brand,
            .sevia-instagram__accent {
                font-size: 34px;
            }

            .sevia-instagram__in {
                font-size: 24px;
            }

            .sevia-instagram__grid {
                grid-template-columns: 1fr;
                grid-auto-rows: 300px;
            }
        }
    </style>
@endonce

<section class="sevia-instagram relative isolate overflow-hidden bg-white px-[68px] pb-[76px] pt-[150px] max-lg:px-6 max-lg:pt-24 max-sm:px-5 max-sm:pb-14 max-sm:pt-16" aria-labelledby="sevia-instagram-title">
    <img
        class="sevia-instagram__floral pointer-events-none absolute right-[-110px] top-[-250px] z-0 h-[620px] w-[450px] max-w-none object-contain opacity-75 max-lg:right-[-180px] max-lg:top-[-190px] max-lg:h-[500px] max-sm:hidden"
        src="{{ asset('vendor/frontend-sevia/images/inst_fon2.png') }}"
        alt=""
        aria-hidden="true"
    >
    <img
        class="sevia-instagram__floral sevia-instagram__floral--mobile pointer-events-none absolute z-0 max-w-none object-contain"
        src="{{ asset('vendor/frontend-sevia/images/fon_insta_mob.png') }}"
        alt=""
        aria-hidden="true"
    >

    <div class="sevia-instagram__inner relative z-10 mx-auto flex w-full max-w-[1304px] flex-col gap-[34px] max-lg:gap-10 max-sm:gap-7">
        <header class="sevia-instagram__header flex flex-col items-start gap-[12px]">
            <p class="sevia-instagram__eyebrow m-0 text-[11px] font-medium uppercase leading-4 tracking-[2.2px] text-[#A98088] max-lg:text-[10px] max-lg:tracking-[1.9px] max-sm:text-[9px] max-sm:leading-3 max-sm:tracking-[1.4px]">
                Slowly · Daily · sevia.parfume
            </p>

            <a class="sevia-instagram__title group inline-flex flex-wrap items-baseline gap-x-2.5 gap-y-1" href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer">
                <h2 id="sevia-instagram-title" class="sevia-instagram__brand m-0 font-cormorant text-[40px] font-normal italic leading-none tracking-normal text-[#5B2730] max-lg:text-[42px] max-sm:text-[34px]">
                    Sevia
                </h2>
                <span class="font-cormorant text-[30px] font-normal leading-none tracking-normal text-[#5B2730] max-lg:text-[32px] max-sm:text-[24px]">в</span>
                <span class="font-cormorant text-[40px] font-medium uppercase leading-none tracking-normal text-[#D46568] transition group-hover:text-[#A85D66] max-lg:text-[42px] max-sm:text-[34px]">
                    Instagram
                </span>
            </a>

            <div class="sevia-instagram__rule mt-3 h-px w-full max-w-[1040px] bg-[#EFE4D9]"></div>
        </header>

        <div class="sevia-instagram__grid grid h-[420px] grid-cols-[2.05fr_1fr_1fr_2.05fr] grid-rows-2 gap-[12px] max-lg:h-auto max-lg:grid-cols-3 max-lg:auto-rows-[220px] max-md:grid-cols-2 max-md:auto-rows-[200px] max-sm:grid-cols-1 max-sm:auto-rows-[300px]">
            @foreach ($posts as $post)
                @php
                    $classes = match ($loop->index) {
                        0 => 'sevia-instagram__tile--large-left row-span-2 max-lg:row-span-1',
                        2 => 'sevia-instagram__tile--tall row-span-2 max-lg:row-span-1',
                        3 => 'sevia-instagram__tile--wide col-span-2 max-lg:col-span-1',
                        default => '',
                    };
                @endphp

                <a
                    class="sevia-instagram__tile group relative block overflow-hidden bg-[#F8EDE7] {{ $classes }}"
                    href="{{ $post['permalink'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="{{ $post['caption'] ?: 'Sevia в Instagram' }}"
                >
                    <img
                        class="sevia-instagram__image h-full w-full object-cover transition duration-500 group-hover:scale-[1.035]"
                        src="{{ $post['image'] }}"
                        alt="{{ $post['caption'] ?: 'Sevia в Instagram' }}"
                        loading="lazy"
                    >
                    <span class="sevia-instagram__gradient pointer-events-none absolute inset-0 bg-gradient-to-b from-[rgba(91,39,48,0)] from-55% to-[rgba(91,39,48,0.16)]"></span>

                    @if (! $loop->first)
                        <span class="sevia-instagram__heart absolute right-3 top-3 grid size-9 place-items-center rounded-full border border-[#E8DAD0] bg-white text-[#5B2730]">
                            <img class="sevia-instagram__heart-icon size-[18px]" src="{{ asset('vendor/frontend-sevia/images/heart.svg') }}" alt="" aria-hidden="true">
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
