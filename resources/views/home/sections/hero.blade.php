<section
    class="relative isolate overflow-hidden bg-white
           min-[1100px]:min-h-[778px]
           min-[1600px]:min-h-[1135px]
           max-sm:min-h-[734px]
           max-sm:pb-[26px]"
>

    {{-- ========================================================= --}}
    {{-- DESKTOP: 1100px+ --}}
    {{-- ========================================================= --}}
    <div
        class="relative mx-auto hidden
               min-h-[778px]
               w-full
               max-w-none
               min-[1100px]:block
               min-[1600px]:min-h-[1135px]"
    >
        {{-- Текст --}}
        <div
            class="absolute
                   left-[72px]
                   top-[103px]
                   z-20
                   w-[934px]

                   min-[1600px]:left-[96px]
                   min-[1600px]:top-[137px]
                   min-[1600px]:w-[1245px]

                   max-xl:left-12
                   max-xl:w-[760px]"
        >
            <p
                class="text-[20px]
                       font-medium
                       uppercase
                       leading-[17px]
                       tracking-[3.08px]
                       text-[#A98088]"
            >
                Sevia · Maison · Est. 2026
            </p>

            <h1
                class="mt-[33px]
                       max-w-[934px]
                       font-cormorant
                       text-[74px]
                       font-normal
                       italic
                       uppercase
                       leading-none
                       tracking-[-1.944px]
                       text-[#5B2730]

                       min-[1600px]:max-w-[1245px]
                       min-[1600px]:text-[88.8px]
                       min-[1600px]:leading-[89px]
                       min-[1600px]:tracking-[-2.335px]

                       max-xl:text-[64px]"
            >
                <span class="block">Твої улюблені</span>
                <span class="block">парфуми</span>
                <span class="block text-[#D46568]">
                    у нас на розпив
                </span>
            </h1>

            <a
                class="mt-[167px]
                       inline-flex
                       h-[54px]
                       min-w-[245px]
                       items-center
                       justify-center
                       gap-3
                       border
                       border-[#5B2730]
                       bg-[#FDFBF8]
                       px-9
                       text-[13px]
                       font-medium
                       uppercase
                       leading-[20px]
                       tracking-[1.56px]
                       text-[#5B2730]

                       min-[1600px]:mt-[350px]
                       min-[1600px]:h-[70px]
                       min-[1600px]:min-w-[306px]
                       min-[1600px]:px-[49px]
                       min-[1600px]:text-[15.6px]
                       min-[1600px]:tracking-[1.872px]"
                href="{{ route('catalog.index') }}"
            >
                <span>Перейти в каталог</span>

                <svg
                    class="h-2.5 w-3.5"
                    viewBox="0 0 14 10"
                    fill="none"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M1 5H13M13 5L9 1M13 5L9 9"
                        stroke="currentColor"
                        stroke-width="1.1"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </a>

            <p
                class="mt-[35px]
                       max-w-[725px]
                       text-[24px]
                       leading-[120%]
                       text-[#7A4751]

                       min-[1600px]:mt-[51px]
                       min-[1600px]:max-w-[869px]
                       min-[1600px]:text-[28.8px]
                       min-[1600px]:leading-[39px]"
            >
                Нішева парфумерія повними флаконами та на розпив від 3 мл.
                Шовкова білизна, що говорить пошепки. Один простір — два світи.
            </p>
        </div>

        {{-- Основные цветы справа --}}
        <div
            class="absolute
                   right-0
                   top-[-104px]
                   z-0
                   aspect-[937/856]
                   w-[clamp(937px,62vw,1249px)]

                   max-[1399px]:w-[820px]
                   max-[1299px]:w-[760px]

                   max-xl:top-[-64px]"
        >
            <img
                class="absolute inset-0 h-full w-full"
                src="{{ asset('vendor/frontend-sevia/images/hero-rectangle-2.png') }}"
                alt=""
                aria-hidden="true"
            >

            {{-- Флакон --}}
            <img
                class="absolute
                       left-[52.9%]
                       top-[16.6%]
                       z-10
                       h-[48%]
                       w-[23.9%]
                       object-contain
                       drop-shadow-[0_4px_50px_rgba(215,82,88,0.3)]"
                src="{{ asset('vendor/frontend-sevia/images/hero-valaya.png') }}"
                alt="Parfums de Marly Valaya"
            >
        </div>

        {{-- Нижние цветы --}}
        <div
            class="pointer-events-none
                   absolute
                   left-0
                   top-[340px]
                   z-[1]
                   h-[605.01px]
                   w-[1059px]
                   overflow-hidden
                   border-b
                   border-[#F3E3DF]
                   opacity-100

                   min-[1600px]:top-[483px]
                   min-[1600px]:h-[807px]
                   min-[1600px]:w-[1412px]

                   max-xl:left-[-80px]"
            aria-hidden="true"
        >
            <img
                class="h-full
                       w-full
                       max-w-none
                       object-cover
                       opacity-100"
                src="{{ asset('vendor/frontend-sevia/images/hero-flower-merged.png') }}"
                alt=""
            >
        </div>

        {{-- Товар --}}
        <a
            class="absolute
                   left-[767px]
                   top-[517px]
                   z-20
                   flex
                   w-[242px]
                   items-center
                   gap-3.5
                   text-right
                   text-[#7A4751]

                   min-[1600px]:left-[1023px]
                   min-[1600px]:top-[689px]
                   min-[1600px]:w-[323px]
                   min-[1600px]:gap-[18px]"
            href="#product-valaya"
        >
            <span class="flex w-[216px] flex-col items-end">
                <span
                    class="text-[16px]
                           font-light
                           leading-[25px]"
                >
                    Parfums de Marly Valaya
                </span>

                <span
                    class="text-[16px]
                           font-medium
                           italic
                           leading-[25px]"
                >
                    3 мл - 320 грн
                </span>
            </span>

            <span
                class="text-[13px]
                       font-medium
                       uppercase
                       leading-[20px]
                       tracking-[2.34px]
                       text-[#5B2730]"
            >
                →
            </span>
        </a>
    </div>


    {{-- ========================================================= --}}
    {{-- TABLET: 640px - 1099px --}}
    {{-- Figma width: ~798px --}}
    {{-- ========================================================= --}}
    <div
        class="relative mx-auto hidden
               min-h-[1032px]
               w-full
               flex-col
               items-start
               overflow-hidden
               px-[52px]
               pt-[46px]

               min-[640px]:flex
               min-[1100px]:hidden"
    >

        {{-- Заголовочная часть --}}
        <div
            class="relative z-20
                   flex
                   w-full
                   flex-col
                   items-start"
        >
            <p
                class="m-0
                       text-[13.3px]
                       font-medium
                       uppercase
                       leading-[19px]
                       tracking-[2.048px]
                       text-[#8A5D66]"
            >
                Sevia · Maison · Est. 2026
            </p>

            <h1
                class="mt-[18px]
                       w-full
                       max-w-[694px]
                       font-cormorant
                       text-[49.4px]
                       font-normal
                       italic
                       uppercase
                       leading-[49px]
                       tracking-[-1.299px]
                       text-[#5B2730]"
            >
                <span class="block">
                    Твої улюблені
                </span>

                <span class="block">
                    парфуми
                </span>

                <span class="block text-[#D46568]">
                    у нас на розпив
                </span>
            </h1>
        </div>

        {{-- Кнопка --}}
        <a
            href="{{ route('catalog.index') }}"
            class="relative
                   z-30
                   mt-[18px]
                   inline-flex
                   h-[45px]
                   w-[213px]
                   shrink-0
                   items-center
                   justify-center
                   gap-[10px]
                   border
                   border-[#5B2730]
                   bg-[#FDFBF8]
                   px-[26px]
                   text-[12.4px]
                   font-medium
                   uppercase
                   leading-[19px]
                   tracking-[1.482px]
                   text-[#5B2730]"
        >
            <span class="whitespace-nowrap">
                Перейти в каталог
            </span>

            <svg
                class="h-[10px] w-[12px] shrink-0"
                viewBox="0 0 14 10"
                fill="none"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M1 5H13M13 5L9 1M13 5L9 9"
                    stroke="currentColor"
                    stroke-width="1.1"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </a>

        {{-- Описание --}}
        <p
            class="relative
                   z-20
                   mt-[18px]
                   w-full
                   max-w-[694px]
                   text-[17.1px]
                   font-normal
                   leading-[26px]
                   text-[#5B2730]"
        >
            Нішева парфумерія повними флаконами та на розпив від 3 мл.
            Шовкова білизна, що говорить пошепки. Один простір — два світи.
        </p>

        {{-- ================================================= --}}
        {{-- Композиция с товаром --}}
        {{-- ================================================= --}}
        {{-- ================================================= --}}
        {{-- Композиция с товаром --}}
        {{-- ================================================= --}}
        <div
            class="relative
           z-10
           mt-[10px]
           h-[642px]
           w-[calc(100%+52px)]
           max-w-none
           shrink-0"
        >

            {{-- Левый декоративный botanical --}}
            <div
                class="pointer-events-none
           absolute
           left-[-52px]
           top-[5px]
           z-0
           h-[470px]
           w-[430px]
           overflow-hidden"
                aria-hidden="true"
            >
                <img
                    src="{{ asset('vendor/frontend-sevia/images/hero-flower-merged.png') }}"
                    alt=""
                    class="absolute
               left-[-110px]
               top-[-20px]
               h-[470px]
               w-[823px]
               max-w-none
               object-contain
               opacity-100"
                >
            </div>

            {{-- Основная композиция справа --}}
            <div
                class="absolute
           right-[-52px]
           top-0
           z-[1]
           h-[582px]
           w-[calc(100vw-52px)]
           overflow-visible"
            >
                <img
                    class="absolute
           right-0
           top-[-85px]
           h-[calc(100%+85px)]
           w-full
           max-w-none
           object-cover
           object-right-top"
                    src="{{ asset('vendor/frontend-sevia/images/hero-rectangle-2.png') }}"
                    alt=""
                    aria-hidden="true"
                >

                <img
                    class="absolute
               left-[53%]
               top-[-9px]
               z-10
               h-[391px]
               w-[214px]
               max-w-none
               object-contain
               drop-shadow-[0_4px_50px_rgba(215,82,88,0.3)]"
                    src="{{ asset('vendor/frontend-sevia/images/hero-valaya.png') }}"
                    alt="Parfums de Marly Valaya"
                >
            </div>

            {{-- Ссылка на товар --}}
            <a
                href="#product-valaya"
                class="absolute
           bottom-[10px]
           right-[28px]
           z-20
           flex
           h-[48px]
           w-full
           max-w-[694px]
           items-center
           justify-end
           gap-[10px]
           text-right
           text-[#7A4751]"
            >
    <span class="flex flex-1 flex-col items-end">
        <span class="text-[14.3px] font-normal leading-[24px] text-[#7A4751]">
            Parfums de Marly Valaya
        </span>

        <span class="font-cormorant text-[16.2px] font-semibold italic leading-[24px] text-[#5B2730]">
            3 мл - 320 грн
        </span>
    </span>

                <span class="flex h-[19px] w-[15px] shrink-0 items-center justify-end text-[16.2px] leading-[19px] text-[#5B2730]">
        →
    </span>
            </a>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- MOBILE: до 639px --}}
    {{-- ========================================================= --}}
    <div
        class="relative
               mx-auto
               hidden
               w-full
               max-w-[393px]
               flex-col
               items-start
               gap-5
               overflow-hidden
               px-5
               pb-[26px]
               pt-[34px]
               max-sm:flex"
    >
        <p
            class="m-0
                   text-[11px]
                   font-medium
                   uppercase
                   leading-[13px]
                   tracking-[2.4px]
                   text-[#A98088]"
        >
            Sevia · Maison · Est. 2026
        </p>

        <div
            class="relative
                   z-20
                   flex
                   w-full
                   flex-col
                   items-start
                   gap-4"
        >
            <h1
                class="m-0
                       w-full
                       font-cormorant
                       text-[40px]
                       font-medium
                       italic
                       uppercase
                       leading-none
                       tracking-[-1.944px]
                       text-[#5B2730]"
            >
                <span class="block">
                    Твої улюблені
                </span>

                <span class="block">
                    парфуми
                </span>

                <span class="block text-[#D46568]">
                    у нас на розпив
                </span>
            </h1>

            <p
                class="m-0
                       w-full
                       text-[15px]
                       font-normal
                       leading-[148%]
                       text-[#7A4751]"
            >
                Нішева парфумерія повними флаконами та на розпив від 3 мл.
                Шовкова білизна, що говорить пошепки. Один простір — два світи.
            </p>
        </div>

        <a
            class="relative
                   z-30
                   inline-flex
                   h-[43px]
                   w-[223px]
                   items-center
                   justify-center
                   gap-2
                   border
                   border-[#5B2730]
                   bg-[#FDFBF8]
                   px-[26px]
                   text-[12px]
                   font-medium
                   uppercase
                   leading-[15px]
                   tracking-[1.4px]
                   text-[#5B2730]"
            href="{{ route('catalog.index') }}"
        >
            <span>
                Перейти в каталог
            </span>

            <svg
                class="h-2 w-3"
                viewBox="0 0 12 8"
                fill="none"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M1 4H11M11 4L8 1M11 4L8 7"
                    stroke="currentColor"
                    stroke-width="1.05"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </a>

        {{-- Товарная композиция --}}
        <div
            class="relative
                   z-10
                   flex
                   h-[391px]
                   w-full
                   flex-col
                   items-stretch
                   gap-2.5
                   overflow-visible
                   pt-2"
        >
            <div
                class="relative
                       left-1/2
                       h-[360px]
                       w-screen
                       -translate-x-1/2
                       overflow-hidden"
            >
                <img
                    class="absolute
                           right-0
                           top-0
                           h-[360px]
                           w-[calc(100vw+60px)]
                           max-w-none
                           object-cover
                           opacity-90"
                    src="{{ asset('vendor/frontend-sevia/images/hero-rectangle-2.png') }}"
                    alt=""
                    aria-hidden="true"
                >

                <img
                    class="absolute
                           right-[99px]
                           top-[2px]
                           h-[231px]
                           w-[126px]
                           object-contain
                           drop-shadow-[0_2.2514px_28.1425px_rgba(215,82,88,0.3)]"
                    src="{{ asset('vendor/frontend-sevia/images/hero-valaya.png') }}"
                    alt="Parfums de Marly Valaya"
                >
            </div>

            <a
                class="flex
                       h-[13px]
                       w-full
                       items-center
                       justify-center
                       text-center
                       text-[11px]
                       font-medium
                       leading-[13px]
                       tracking-[0.4px]
                       text-[#A98088]"
                href="#product-valaya"
            >
                <span>
                    Parfums de Marly Valaya · 3 мл — 320 грн →
                </span>
            </a>
        </div>
    </div>

</section>
