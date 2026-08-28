@php
    $discountProducts = [
        ['image' => 'discount-1.png', 'meta' => 'Жіночі · Eau de Parfum', 'brand' => 'Lanvin', 'name' => 'Éclat dʼArpège', 'notes' => 'Бузок · Зелений чай · Персик', 'price' => '294 ₴', 'oldPrice' => '420 ₴'],
        ['image' => 'discount-2.png', 'meta' => 'Жіночі · Eau de Parfum', 'brand' => 'Yves Saint Laurent', 'name' => 'Libre', 'notes' => 'Лаванда · Апельсин · Ваніль', 'price' => '360 ₴', 'oldPrice' => '450 ₴'],
        ['image' => 'discount-3.png', 'meta' => 'Унісекс · Eau de Parfum', 'brand' => 'Montale', 'name' => 'Intense Café', 'notes' => 'Троянда · Кава · Амбра', 'price' => '392 ₴', 'oldPrice' => '490 ₴'],
        ['image' => 'discount-4.png', 'meta' => 'Жіночі · Eau de Parfum', 'brand' => 'Tom Ford', 'name' => 'Lost Cherry', 'notes' => 'Вишня · Мигдаль · Боби тонка', 'price' => '520 ₴', 'oldPrice' => '650 ₴'],
        ['image' => 'discount-5.png', 'meta' => 'Жіночі · Eau de Parfum', 'brand' => 'Giorgio Armani', 'name' => 'Si', 'notes' => 'Чорна смородина · Троянда · Ваніль', 'price' => '344 ₴', 'oldPrice' => '430 ₴'],
    ];
@endphp
@php($discountProducts = collect($liveDiscountProducts ?? []))

<section
    class="relative isolate flex min-h-[862px] flex-col items-center bg-white pt-20
           min-[1600px]:min-h-[1131px] min-[1600px]:pt-[107px]

           min-[640px]:max-[1099px]:mt-[57px]
           min-[640px]:max-[1099px]:min-h-[1222px]
           min-[640px]:max-[1099px]:pt-0

           max-sm:min-h-0 max-sm:px-4 max-sm:pb-8 max-sm:pt-14"
>  <div
        class="relative z-0 flex h-[398.16px] w-full flex-col items-center gap-10
           rounded-[20px] bg-[#E7CDCA] px-2.5 py-20

           min-[1600px]:h-[531px]
           min-[1600px]:rounded-none
           min-[1600px]:py-[106px]

           min-[640px]:max-[1099px]:mt-[57.67px]
           min-[640px]:max-[1099px]:h-[286.76px]
           min-[640px]:max-[1099px]:rounded-none
           min-[640px]:max-[1099px]:p-0

           max-sm:h-auto max-sm:rounded-none max-sm:bg-white max-sm:p-0"
    >   <img
            class="pointer-events-none absolute
           left-[-138px] top-[-180.14px]
           z-0 h-[957px] w-[638px]
           max-w-none object-contain opacity-100

           min-[1600px]:left-0
           min-[1600px]:top-[-134px]
           min-[1600px]:h-[1276px]
           min-[1600px]:w-[653px]

           min-[640px]:max-[1099px]:left-[0.43px]
           min-[640px]:max-[1099px]:top-[-129.73px]
           min-[640px]:max-[1099px]:h-[689.24px]
           min-[640px]:max-[1099px]:w-[352.9px]

           max-sm:hidden"
            src="{{ asset('vendor/frontend-sevia/images/discount-flow-2.png') }}"
            alt=""
            aria-hidden="true"
        > <img class="pointer-events-none absolute bottom-[-57px] right-0 z-0 h-[323px] w-[290px] max-w-none object-cover opacity-100 min-[1600px]:bottom-auto min-[1600px]:right-0 min-[1600px]:top-[106px] min-[1600px]:h-[579px] min-[1600px]:w-[389px] max-sm:hidden" src="{{ asset('vendor/frontend-sevia/images/discount-right.png') }}" alt="" aria-hidden="true">

        <div
            class="relative z-10 flex h-[238.16px] w-full flex-col items-center gap-10 pb-10

           min-[640px]:max-[1099px]:h-[278.46px]
           min-[640px]:max-[1099px]:gap-[8.6px]
           min-[640px]:max-[1099px]:px-[51.86px]
           min-[640px]:max-[1099px]:pb-0
           min-[640px]:max-[1099px]:pt-[50.36px]

           max-sm:h-[54px] max-sm:gap-2 max-sm:pb-0"
        >     <div
                class="flex w-full max-w-[1420px] flex-col items-center gap-[13px] text-center

           min-[1600px]:gap-[17px]

           min-[640px]:max-[1099px]:max-w-[694.29px]
           min-[640px]:max-[1099px]:gap-[8.6px]

           max-sm:max-w-[320px] max-sm:gap-2"
            ><p
                    class="m-0 w-full text-[20px] font-medium uppercase
           leading-[17px] tracking-[3.08px] text-[#A98088]

           min-[640px]:max-[1099px]:max-w-[321.28px]
           min-[640px]:max-[1099px]:text-[13.3px]
           min-[640px]:max-[1099px]:leading-[19px]
           min-[640px]:max-[1099px]:tracking-[2.048px]
           min-[640px]:max-[1099px]:text-[#7A4751]

           max-sm:text-[9.5px]
           max-sm:leading-[11px]
           max-sm:tracking-[1.2px]"
                >
                    Знижки на парфуми на розпив 3-30 мл у червні 2026
                </p> <h2
                    class="m-0 w-full font-cormorant text-[74px] font-normal italic uppercase
           leading-none tracking-[-1.944px] text-[#5B2730]

           min-[1600px]:text-[88.8px]
           min-[1600px]:leading-[89px]
           min-[1600px]:tracking-[-2.335px]

           min-[640px]:max-[1099px]:text-[49.4px]
           min-[640px]:max-[1099px]:leading-[49px]
           min-[640px]:max-[1099px]:tracking-[-1.299px]

           max-sm:text-[29px]
           max-sm:font-medium
           max-sm:not-italic
           max-sm:leading-[35px]
           max-sm:tracking-[0.5px]"
                >
                    Знижки до -20%
                </h2>
            </div>

            <a
                class="inline-flex h-[54.16px] w-[255px] items-center justify-center
           border border-[#5B2730] bg-[#FDFBF8]
           px-9 py-[15.25px]
           text-[13px] font-medium uppercase
           leading-5 tracking-[1.56px] text-[#5B2730]

           min-[1600px]:h-[70px]
           min-[1600px]:w-[318px]
           min-[1600px]:px-[49px]
           min-[1600px]:text-[15.6px]
           min-[1600px]:tracking-[1.872px]

           min-[640px]:max-[1099px]:h-[45.02px]
           min-[640px]:max-[1099px]:w-[223.3px]
           min-[640px]:max-[1099px]:px-[26.65px]
           min-[640px]:max-[1099px]:py-[11.2px]
           min-[640px]:max-[1099px]:text-[12.4px]
           min-[640px]:max-[1099px]:leading-[19px]
           min-[640px]:max-[1099px]:tracking-[1.482px]

           max-sm:hidden"
                href="#"
            >
                Переглянути знижки
            </a>
        </div>
    </div>

    <div
        class="relative z-10 flex w-full max-w-[1296px] gap-2.5

           min-[1100px]:max-[1599px]:-mt-[72px]

           min-[1600px]:-mt-[36px]
           min-[1600px]:max-w-[1728px]
           min-[1600px]:gap-[13px]

           min-[640px]:max-[1099px]:!mt-[-22px]
           min-[640px]:max-[1099px]:grid
           min-[640px]:max-[1099px]:max-w-[798px]
           min-[640px]:max-[1099px]:grid-cols-3
           min-[640px]:max-[1099px]:gap-x-[14.47px]
           min-[640px]:max-[1099px]:gap-y-[5.96px]
           min-[640px]:max-[1099px]:px-[51.86px]

           max-sm:mt-5
           max-sm:max-w-none
           max-sm:grid
           max-sm:grid-cols-2
           max-sm:gap-x-2
           max-sm:gap-y-3"
        >  @foreach ($discountProducts as $product)
            @php($defaultVolume = $product['volumes']->first())
            <article
                class="relative flex h-[455.78px] flex-1 flex-col items-center
           gap-3 border border-[#5B2730] bg-[#FDFBF8] p-6

           min-[640px]:max-[1099px]:h-[455.78px]
           min-[640px]:max-[1099px]:w-full
           min-[640px]:max-[1099px]:flex-none
           min-[640px]:max-[1099px]:px-3
           min-[640px]:max-[1099px]:py-6

           max-sm:h-auto
           max-sm:gap-0
           max-sm:border-[#E8DAD0]
           max-sm:bg-white
           max-sm:p-0

           {{ $loop->iteration > 4 ? 'max-sm:hidden' : '' }}"
            >      <span class="absolute left-6 top-6 z-10 flex h-[25px] min-w-[47px] items-center justify-center rounded-[2px] border border-[#5B2730] bg-[#5B2730] px-[9px] py-1 text-[9.5px] font-semibold uppercase leading-[15px] tracking-[1.33px] text-[#FDFBF8] max-sm:left-2 max-sm:top-2 max-sm:h-[18px] max-sm:w-[37px] max-sm:rounded-none max-sm:border-0 max-sm:bg-[#B03B45] max-sm:px-[7px] max-sm:py-1 max-sm:text-[8px] max-sm:leading-[10px] max-sm:tracking-[0.4px]">-{{ $product['discount_percent'] }}%</span>
                <button class="absolute right-6 top-6 z-10 flex size-9 items-center justify-center rounded-full border border-[#E8DAD0] bg-white text-[#5B2730] max-sm:right-3 max-sm:top-2 max-sm:size-7" type="button" aria-label="Додати в обране">
                    <svg class="max-sm:size-[13px]" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M8.5 14.025C8.5 14.025 2.125 10.625 2.125 6.37502C2.125 4.50502 3.57 3.14502 5.355 3.14502C6.545 3.14502 7.65 3.82502 8.5 4.93002C9.35 3.82502 10.455 3.14502 11.645 3.14502C13.43 3.14502 14.875 4.50502 14.875 6.37502C14.875 10.625 8.5 14.025 8.5 14.025Z" stroke="#5B2730" stroke-width="1.19" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div
                    class="relative flex h-[235px] w-full flex-col px-0.5

           min-[640px]:max-[1099px]:max-w-[195.91px]

           max-sm:h-[132px]
           max-sm:px-3
           max-sm:pb-[13px]
           max-sm:pt-[11px]"
                >       <a class="flex h-full w-full items-center justify-center" href="{{ $product['url'] }}"><img class="max-h-[235px] w-auto max-w-[167px] object-contain max-sm:h-[134px] max-sm:w-[80.5px] max-sm:max-w-none" src="{{ $product['image'] }}" alt="{{ $product['brand'] }} {{ $product['name'] }}"></a>
                </div>

                <div class="flex w-full flex-1 flex-col px-0.5 max-sm:h-auto max-sm:flex-none max-sm:px-3 max-sm:pb-[13px] max-sm:pt-[11px]">
                    <p class="m-0 pt-1.5 text-[10px] font-medium uppercase leading-4 tracking-[1.8px] text-[#A98088] max-sm:hidden">{{ $product['meta'] }}</p>
                    <p class="m-0 pt-0.5 text-[12px] font-semibold uppercase leading-[19px] tracking-[0.48px] text-[#5B2730] max-sm:p-0 max-sm:text-[9px] max-sm:font-medium max-sm:leading-[11px] max-sm:tracking-[0.6px] max-sm:text-[#7A4751]">{{ $product['brand'] }}</p>
                    <h3 class="m-0 min-h-[46px] font-cormorant text-[19.7px] font-medium leading-[23px] text-[#5B2730] max-sm:mt-[5px] max-sm:min-h-[40px] max-sm:text-[17px] max-sm:leading-5">{{ $product['name'] }}</h3>
                    <p class="m-0 min-h-[38px] max-h-[38px] overflow-hidden pt-0.5 text-[12px] font-light leading-[18px] text-[#7A4751] max-sm:mt-[5px] max-sm:h-[11px] max-sm:min-h-[11px] max-sm:max-h-[11px] max-sm:truncate max-sm:p-0 max-sm:text-[9px] max-sm:font-normal max-sm:leading-[11px] max-sm:text-[#A98088]">{{ $product['notes'] }}</p>

                    <div class="mt-auto flex flex-wrap gap-1 max-sm:mt-2 max-sm:grid max-sm:w-full max-sm:grid-cols-3 max-sm:gap-1" role="group" aria-label="Volume" data-volume-options>
                        @foreach ($product['volumes'] as $volume)
                            <button class="inline-flex h-10 min-w-[46px] flex-col items-center justify-center gap-0 border border-[#D8C4BE] px-0.5 text-[9px] font-medium leading-3 text-[#7A4751] {{ $loop->first ? 'border-[#5B2730] text-[#5B2730]' : '' }}" type="button" data-volume-option data-price-label="{{ $volume['price_label'] }}" data-volume-label="{{ $volume['label'] }}" data-product-id="{{ $volume['id'] }}" data-product-price="{{ $volume['price'] }}" data-cart-product-label="{{ collect([$product['brand'], $product['name'], $volume['label'], $volume['price_label']])->implode(' · ') }}" data-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <span>{{ $volume['label'] }}</span>
                                <strong class="font-medium">{{ $volume['price_label'] }}</strong>
                            </button>
                        @endforeach
                    </div>

                    <div class="relative z-10 mt-auto flex h-[47px] items-center justify-between border-t border-[#EFE4D9] bg-[#FDFBF8] pt-3 max-sm:mt-0 max-sm:h-[47px] max-sm:border-t max-sm:border-[#E8DAD0] max-sm:bg-white max-sm:pt-3">
                        <p class="m-0 flex items-end gap-[5px]">
                            <span class="text-[15px] font-semibold leading-[23px] text-[#5B2730] max-sm:text-[13.5px] max-sm:leading-4 max-sm:text-[#B03B45]">{{ $product['price_label'] }}</span>
                            <span class="text-[12px] leading-[19px] text-[#A98088] line-through max-sm:text-[9px] max-sm:leading-[11px] max-sm:text-[#C9A9B0]">{{ $product['old_price_label'] }}</span>
                            <span class="text-[10.5px] leading-4 text-[#A98088] max-sm:hidden">/ {{ $defaultVolume['label'] }}</span>
                        </p>
                        <button
                            class="absolute right-6 top-6 z-10 flex size-9 items-center justify-center
           rounded-full border border-[#E8DAD0] bg-white text-[#5B2730]

           min-[640px]:max-[1099px]:right-[12px]
           min-[640px]:max-[1099px]:top-[12px]

           max-sm:right-3 max-sm:top-2 max-sm:size-7"
                            type="button"
                            data-cart-add
                            data-cart-add-url="{{ route('cart.add') }}"
                            data-product-id="{{ $defaultVolume['id'] }}"
                            data-product-price="{{ $defaultVolume['price'] }}"
                            data-volume-label="{{ $defaultVolume['label'] }}"
                            data-cart-product-label="{{ collect([$product['brand'], $product['name'], $defaultVolume['label'], $defaultVolume['price_label']])->implode(' · ') }}"
                        >+</button>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <a class="mt-5 hidden text-center text-[11px] font-medium uppercase leading-none tracking-[1.4px] text-[#7A4751] max-sm:inline-flex max-sm:items-center max-sm:justify-center max-sm:gap-1.5" href="#">
        <span>Усі знижки</span>
        <span aria-hidden="true">→</span>
    </a>
</section>
