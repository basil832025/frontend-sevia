@php
    $summerBestsellers = collect($homeProducts ?? []);
@endphp

<section class="bg-white pb-[120px] pt-[55px] max-sm:px-4 max-sm:pb-8 max-sm:pt-14" id="catalog">
    <div class="mx-auto flex w-full max-w-[1209.58px] flex-col items-center gap-[120px] max-xl:max-w-[calc(100%-48px)] max-sm:max-w-none max-sm:gap-5">
        <h2 class="m-0 w-full text-center font-cormorant text-[74px] font-normal italic uppercase leading-none tracking-[-1.944px] text-[#5B2730] max-sm:text-[29px] max-sm:font-medium max-sm:not-italic max-sm:leading-[35px] max-sm:tracking-[0.5px]">Бестселери літа 2026</h2>

        <div class="grid w-full grid-cols-5 gap-6 max-xl:grid-cols-3 max-sm:grid-cols-2 max-sm:gap-x-2 max-sm:gap-y-3">
            @foreach ($summerBestsellers as $product)
                @php($defaultVolume = $product['volumes']->first())
                <article class="flex min-h-[469.73px] flex-col gap-3 max-sm:h-[390px] max-sm:min-h-0 max-sm:gap-0 max-sm:border max-sm:border-[#E8DAD0] max-sm:bg-white {{ $loop->iteration > 4 ? 'max-sm:hidden' : '' }}" data-product-card>
                    <div class="relative flex h-[296.95px] items-center justify-center p-[21.168px] max-sm:h-[182px] max-sm:bg-[#FDFBF8] max-sm:px-12 max-sm:py-6">
                        <img class="max-h-[254.61px] w-auto max-w-full object-contain max-sm:h-[134px] max-sm:w-[80.5px] max-sm:max-w-none" src="{{ $product['image'] }}" alt="{{ $product['brand'] }} {{ $product['name'] }}">
                        <span class="absolute left-2.5 top-2.5 flex h-[25px] items-center border border-[rgba(91,39,48,0.16)] bg-[#F3E3DF] px-[9px] text-[9.5px] font-semibold uppercase leading-[15px] tracking-[1.33px] text-[#5B2730] max-sm:hidden">Bestseller</span>
                        <button class="absolute right-3 top-3 flex size-9 items-center justify-center rounded-full border border-[#E8DAD0] bg-white text-[#5B2730] max-sm:right-3 max-sm:top-2 max-sm:size-7" type="button" aria-label="Додати в обране">♡</button>
                    </div>

                    <div class="flex flex-1 flex-col px-0.5 max-sm:h-[208px] max-sm:px-3 max-sm:pb-[13px] max-sm:pt-[11px]">
                        <p class="m-0 pt-1.5 text-[10px] font-medium uppercase leading-4 tracking-[1.8px] text-[#A98088] max-sm:hidden">{{ $product['meta'] }}</p>
                        <p class="m-0 pt-0.5 text-[12px] font-semibold uppercase leading-[19px] tracking-[0.48px] text-[#5B2730] max-sm:p-0 max-sm:text-[9px] max-sm:leading-[11px] max-sm:text-[#7A4751]">{{ $product['brand'] }}</p>
                        <h3 class="m-0 h-[46px] overflow-hidden font-cormorant text-[19.7px] font-medium leading-[23px] text-[#5B2730] max-sm:mt-[5px] max-sm:h-[40px] max-sm:text-[17px] max-sm:leading-5">{{ $product['name'] }}</h3>
                        <p class="m-0 h-[35px] overflow-hidden pt-0.5 text-[12px] font-light leading-[17px] text-[#7A4751] max-sm:mt-[5px] max-sm:h-auto max-sm:p-0 max-sm:text-[9px] max-sm:leading-[11px] max-sm:text-[#A98088]">{{ $product['notes'] }}</p>

                        <div class="mt-3 grid w-full grid-cols-6 gap-1 max-sm:mt-2 max-sm:grid-cols-3 max-sm:gap-1" role="group" aria-label="Volume" data-volume-options>
                            @foreach ($product['volumes'] as $volume)
                                <button class="inline-flex h-10 min-w-0 w-full flex-col items-center justify-center gap-0 border border-[#D8C4BE] px-0.5 text-[9px] font-medium leading-3 text-[#7A4751] {{ $loop->first ? 'border-[#5B2730] text-[#5B2730]' : '' }}" type="button" data-volume-option data-price-label="{{ $volume['price_label'] }}" data-volume-label="{{ $volume['label'] }}" data-product-id="{{ $volume['id'] }}" data-product-price="{{ $volume['price'] }}" data-cart-product-label="{{ collect([$product['brand'], $product['name'], $volume['label'], $volume['price_label']])->implode(' · ') }}" data-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    <span class="whitespace-nowrap text-[8px] leading-[10px]">{{ $volume['label'] }}</span>
                                    <strong class="whitespace-nowrap text-[8px] font-medium leading-[10px]">{{ $volume['price_label'] }}</strong>
                                </button>
                            @endforeach
                        </div>

                        <div class="mt-auto flex h-[47px] items-center justify-between border-t border-[#EFE4D9] pt-3 max-sm:h-[38px] max-sm:border-t-0 max-sm:pt-[5px]">
                            <p class="m-0 flex items-end gap-[5px]">
                                <span class="text-[15px] font-semibold leading-[23px] text-[#5B2730] max-sm:text-[13.5px] max-sm:leading-4" data-product-price-display>{{ $defaultVolume['price_label'] }}</span>
                                <span class="text-[10.5px] leading-4 text-[#A98088] max-sm:hidden" data-product-volume-label>/ {{ $defaultVolume['label'] }}</span>
                            </p>
                            <button class="flex size-[34px] items-center justify-center rounded-full border border-[#A85D66] text-[19px] leading-[19px] text-[#A85D66] max-sm:size-[26px] max-sm:border-[#5B2730] max-sm:text-[15px] max-sm:leading-[18px] max-sm:text-[#5B2730]" type="button" aria-label="Додати в кошик" data-cart-add data-cart-add-url="{{ route('cart.add') }}" data-product-id="{{ $defaultVolume['id'] }}" data-product-price="{{ $defaultVolume['price'] }}" data-volume-label="{{ $defaultVolume['label'] }}" data-cart-product-label="{{ collect([$product['brand'], $product['name'], $defaultVolume['label'], $defaultVolume['price_label']])->implode(' · ') }}">+</button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
