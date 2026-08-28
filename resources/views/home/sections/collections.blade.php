@php
    $perfumeCollections = [
        ['image' => 'collection-muskusni.png', 'mobileImage' => 'collection-muskusni-mobile.png', 'capsule' => 'Капсула 01', 'title' => 'Мускусні', 'count' => '24 аромати', 'tone' => 'bg-[rgba(250,215,175,0.2)]'],
        ['image' => 'collection-kvitkovi.png', 'mobileImage' => 'collection-kvitkovi-mobile.png', 'capsule' => 'Капсула 02', 'title' => 'Квіткові', 'count' => '32 аромати', 'tone' => 'bg-[rgba(250,175,224,0.2)]'],
        ['image' => 'collection-solodki.png', 'mobileImage' => 'collection-solodki-mobile.png', 'capsule' => 'Капсула 03', 'title' => 'Солодкі', 'count' => '26 ароматів', 'tone' => 'bg-[rgba(250,175,178,0.2)]'],
    ];
@endphp

<section
    class="bg-white px-[75.6px] pb-[152px] pt-0
           min-[1600px]:px-[96px] min-[1600px]:pb-[124px]

           min-[640px]:max-[1099px]:px-[51.85px]
           min-[640px]:max-[1099px]:pb-0

           max-sm:relative max-sm:isolate max-sm:overflow-hidden
           max-sm:rounded-[20px] max-sm:bg-[#F8EDE7]
           max-sm:px-5 max-sm:pb-10 max-sm:pt-14"
    id="collections"
>    <div
        class="relative mx-auto flex w-full max-w-[1288.8px] flex-col gap-[75.59px]
           min-[1600px]:max-w-[1718px] min-[1600px]:gap-[101px]

           min-[640px]:max-[1099px]:max-w-[694.3px]
           min-[640px]:max-[1099px]:gap-[40.34px]

           max-sm:max-w-none max-sm:items-center max-sm:gap-2"
    >   <img
            class="pointer-events-none absolute
           right-[-140px]
           top-[-145px]
           z-0
           h-[444.96px]
           w-[613.46px]
           max-w-none
           object-contain
           opacity-80

           min-[1600px]:right-[-229px]
           min-[1600px]:top-[-145px]
           min-[1600px]:h-[632px]
           min-[1600px]:w-[779px]

           max-[1399px]:right-[-110px]

           min-[640px]:max-[1099px]:left-[56%]
           min-[640px]:max-[1099px]:right-auto
           min-[640px]:max-[1099px]:top-[-156.41px]
           min-[640px]:max-[1099px]:h-[401.98px]
           min-[640px]:max-[1099px]:w-auto

           max-sm:left-[125px]
           max-sm:right-auto
           max-sm:top-[-48px]
           max-sm:block
           max-sm:h-[242.3px]
           max-sm:w-[334.05px]"
            src="{{ asset('vendor/frontend-sevia/images/collection-mask-group-2.png') }}"
            alt=""
            aria-hidden="true"
        >
        <div
            class="relative min-h-[180.8px]
           min-[1600px]:min-h-[229px]

           min-[640px]:max-[1099px]:min-h-[132.92px]

           max-sm:z-[1] max-sm:min-h-0"
        >    <div
                class="absolute left-0 top-[76.84px] flex flex-col items-start gap-[12.95px]
           min-[1600px]:top-[102px] min-[1600px]:gap-[17px]

           min-[640px]:max-[1099px]:top-[55.34px]
           min-[640px]:max-[1099px]:gap-[8.58px]

           max-sm:static max-sm:items-center max-sm:gap-2"
            >  <p
                    class="m-0 text-[20px] font-medium uppercase leading-[17px]
           tracking-[3.08px] text-[#A98088]

           min-[640px]:max-[1099px]:text-[13.3px]
           min-[640px]:max-[1099px]:leading-[19px]
           min-[640px]:max-[1099px]:tracking-[2.048px]
           min-[640px]:max-[1099px]:text-[#8A5D66]

           max-sm:w-[234px] max-sm:text-center max-sm:text-[10.5px]
           max-sm:leading-[13px] max-sm:tracking-[1.8px]"
                >
                    який аромат відображає тебе?
                </p> <h2
                    class="m-0 font-cormorant text-[74px] font-normal italic uppercase
           leading-none tracking-[-1.944px] text-[#5B2730]

           min-[1600px]:text-[88.8px]
           min-[1600px]:leading-[89px]
           min-[1600px]:tracking-[-2.335px]

           min-[640px]:max-[1099px]:text-[49.4px]
           min-[640px]:max-[1099px]:leading-[49px]
           min-[640px]:max-[1099px]:tracking-[-1.299px]

           max-sm:w-[299px] max-sm:text-center max-sm:text-[29px]
           max-sm:font-medium max-sm:not-italic max-sm:leading-[35px]
           max-sm:tracking-[0.5px]"
                >
                    Колекції парфумів
                </h2>
                <p class="m-0 hidden text-center text-[13px] font-normal leading-[150%] text-[#7A4751] max-sm:block max-sm:w-[310px]">
                    Шукай не за брендом, а за станом.
                </p>
            </div>
        </div>

        <div
            class="grid min-h-[533px] grid-cols-3 gap-6
           min-[1600px]:min-h-[711px] min-[1600px]:gap-8

           min-[640px]:max-[1099px]:min-h-[515.45px]
           min-[640px]:max-[1099px]:grid-cols-3
           min-[640px]:max-[1099px]:gap-[17.29px]

           max-sm:z-[1] max-sm:min-h-0 max-sm:w-full
           max-sm:grid-cols-1 max-sm:gap-3.5 max-sm:pt-3"
        >      @foreach ($perfumeCollections as $collection)
                <a
                    class="group relative block h-[533px] overflow-visible
           border border-[#E8DAD0] bg-white text-[#5B2730]

           min-[640px]:max-[1099px]:h-[436.23px]

           max-sm:h-[250px] max-sm:overflow-hidden"
                    href="#"
                >     <figure class="absolute inset-px m-0 flex items-center justify-center overflow-hidden {{ $collection['tone'] }} max-sm:static max-sm:h-[158px] max-sm:w-full">
                        <img class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.03] max-sm:hidden" src="{{ asset('vendor/frontend-sevia/images/' . $collection['image']) }}" alt="{{ $collection['title'] }}">
                        <img class="hidden h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.03] max-sm:block" src="{{ asset('vendor/frontend-sevia/images/' . $collection['mobileImage']) }}" alt="{{ $collection['title'] }}">
                        <span class="absolute inset-x-0 bottom-0 h-[46%] bg-gradient-to-t from-[#FDFBF8] via-[rgba(253,251,248,0.58)] to-transparent max-sm:hidden"></span>
                    </figure>

                    <div
                        class="absolute left-2.5 right-2.5 bottom-[-92.61px]
           flex h-[202px] flex-col gap-1.5
           border border-[#5B2730] bg-[#FDFBF8]
           px-9 py-6

           min-[640px]:max-[1099px]:left-[7.49px]
           min-[640px]:max-[1099px]:right-[7.02px]
           min-[640px]:max-[1099px]:bottom-[-67.93px]
           min-[640px]:max-[1099px]:h-[153.06px]
           min-[640px]:max-[1099px]:gap-[4.3px]
           min-[640px]:max-[1099px]:px-[18.73px]
           min-[640px]:max-[1099px]:py-[15.84px]

           max-sm:static max-sm:h-[94px] max-sm:gap-[5px]
           max-sm:border-0 max-sm:bg-white max-sm:px-[13px]
           max-sm:pb-3.5 max-sm:pt-3"
                    >  <p
                            class="m-0 text-[12px] font-medium uppercase leading-4
           tracking-[2.8px] text-[rgba(91,39,48,0.86)]

           min-[640px]:max-[1099px]:text-[11.4px]
           min-[640px]:max-[1099px]:leading-[15px]
           min-[640px]:max-[1099px]:tracking-[2.656px]

           max-sm:text-[8.5px] max-sm:leading-[10px]
           max-sm:tracking-[1px] max-sm:text-[#A98088]"
                        ><h3
                            class="m-0 font-cormorant text-[48px] font-medium italic
           leading-[58px] text-[#5B2730]

           min-[640px]:max-[1099px]:text-[28px]
           min-[640px]:max-[1099px]:leading-[38px]

           max-sm:text-[24px] max-sm:not-italic max-sm:leading-[29px]"
                        >
                            {{ $collection['title'] }}
                        </h3>
                        <div
                            class="mt-[18px] flex h-[50px] items-baseline justify-between
           border-t border-[rgba(91,39,48,0.32)] pt-3.5

           min-[640px]:max-[1099px]:mt-[12.96px]
           min-[640px]:max-[1099px]:h-[44.8px]
           min-[640px]:max-[1099px]:pt-[10.8px]

           max-sm:mt-0 max-sm:h-[19px] max-sm:border-t-0
           max-sm:items-center max-sm:pt-0.5"
                        >            <span class="text-[14px] font-medium uppercase leading-4 tracking-[2.73px] text-[rgba(91,39,48,0.86)] max-sm:text-[10.5px] max-sm:font-normal max-sm:normal-case max-sm:leading-[13px] max-sm:tracking-normal max-sm:text-[#7A4751]">{{ $collection['count'] }}</span>
                            <span
                                class="text-[22px] leading-[34px] text-[#5B2730]
           min-[640px]:max-[1099px]:text-[20.9px]
           min-[640px]:max-[1099px]:leading-[32px]
           max-sm:text-[14px] max-sm:leading-[17px]"
                            >
    →
</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
