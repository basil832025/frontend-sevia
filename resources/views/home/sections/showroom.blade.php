<section
    class="
        relative
        mt-[247px]
        h-[1060px]
        bg-white

        min-[1600px]:mt-[240px]
        min-[1600px]:h-[1424px]
        min-[1600px]:px-[96px]

        sm:max-[1099px]:mt-0
        sm:max-[1099px]:h-auto
        sm:max-[1099px]:px-[52px]
        sm:max-[1099px]:py-0

        max-sm:mt-0
        max-sm:h-auto
        max-sm:bg-[#FDFBF8]
        max-sm:px-5
        max-sm:py-14
        max-sm:pb-12
    "
>
    {{-- Декоративный фон справа / сверху --}}
    <div
        class="
        pointer-events-none
        absolute
        right-0
        top-[-321.92px]
        h-[605.01px]
        w-[1081px]
        overflow-hidden
        border-b
        border-[#F3E3DF]

        min-[1600px]:top-[-416px]
        min-[1600px]:h-[807px]
        min-[1600px]:w-[1304px]

        sm:max-[1099px]:left-[34%]
        sm:max-[1099px]:right-[-10%]
        sm:max-[1099px]:top-[-144px]
        sm:max-[1099px]:h-[375px]
        sm:max-[1099px]:w-auto
        sm:max-[1099px]:overflow-visible
        sm:max-[1099px]:border-0

        max-sm:hidden
    "
        aria-hidden="true"
    >
        <img
            class="
            absolute
            bottom-0
            right-0
            h-[605px]
            w-[978px]
            max-w-none
            object-cover

            min-[1600px]:h-[807px]
            min-[1600px]:w-[1304px]

            sm:max-[1099px]:inset-0
            sm:max-[1099px]:h-full
            sm:max-[1099px]:w-full
            sm:max-[1099px]:object-contain
            sm:max-[1099px]:object-right-bottom
        "
            src="{{ asset('vendor/frontend-sevia/images/showroom-fon-right.png') }}"
            alt=""
        >
    </div>

    {{-- Основной контейнер --}}
    <div
        class="
            relative
            mx-auto
            h-full
            max-w-[1296px]

            min-[1600px]:max-w-[1728px]

            sm:max-[1099px]:w-full
            sm:max-[1099px]:max-w-[694px]

            max-sm:flex
            max-sm:h-auto
            max-sm:flex-col
            max-sm:items-start
            max-sm:gap-4
        "
    >

        {{-- Левая текстовая часть --}}
        <div
            class="
                absolute
                left-0
                top-[67.08px]
                flex
                h-[800.72px]
                w-[778px]
                flex-col
                items-start
                gap-[53px]

                min-[1600px]:top-[89px]
                min-[1600px]:h-[1335px]
                min-[1600px]:w-[1037px]
                min-[1600px]:gap-[71px]

                sm:max-[1099px]:static
                sm:max-[1099px]:h-auto
                sm:max-[1099px]:w-full
                sm:max-[1099px]:gap-[29px]
                sm:max-[1099px]:pt-[35px]

                max-sm:contents
            "
        >

            {{-- Заголовок --}}
            <div
                class="
                    flex
                    w-[765.24px]
                    flex-col
                    items-center
                    gap-7
                    text-center

                    sm:max-[1099px]:w-full
                    sm:max-[1099px]:items-start
                    sm:max-[1099px]:gap-[20px]
                    sm:max-[1099px]:text-left

                    max-sm:order-1
                    max-sm:gap-4
                "
            >
                <p
                    class="
                        m-0
                        text-[24px]
                        font-medium
                        uppercase
                        leading-[17px]
                        tracking-[3.08px]
                        text-[#A98088]

                        sm:max-[1099px]:text-[15.2px]
                        sm:max-[1099px]:leading-[19px]
                        sm:max-[1099px]:tracking-[2.341px]
                        sm:max-[1099px]:text-[#8A5D66]

                        max-sm:text-[10px]
                        max-sm:leading-3
                        max-sm:tracking-[1.8px]
                    "
                >
                    магазин Sevia
                </p>

                <h2
                    class="
                        m-0
                        max-w-[728px]
                        font-cormorant
                        text-[72px]
                        font-normal
                        leading-[74.88px]
                        tracking-[-0.864px]
                        text-[#5B2730]

                        min-[1600px]:max-w-[1037px]
                        min-[1600px]:text-[86.4px]
                        min-[1600px]:leading-[90px]
                        min-[1600px]:tracking-[-1.037px]

                        sm:max-[1099px]:w-full
                        sm:max-[1099px]:max-w-none
                        sm:max-[1099px]:text-[43.7px]
                        sm:max-[1099px]:leading-[46px]
                        sm:max-[1099px]:tracking-[-0.524px]

                        max-sm:w-[276px]
                        max-sm:text-[29px]
                        max-sm:leading-[35px]
                    "
                >
                    <span class="block">
                        Спробуй нові аромати
                    </span>

                    <span
                        class="
                            block
                            font-medium
                            uppercase
                            leading-none
                            tracking-[-1.944px]
                            text-[#D46568]

                            sm:max-[1099px]:leading-[44px]
                            sm:max-[1099px]:tracking-[-1.18px]
                        "
                    >
                        в нашому шоурумі
                    </span>
                </h2>
            </div>


            {{-- MOBILE картинка --}}
            <figure
                class="
                    m-0
                    hidden
                    h-[332px]
                    w-full
                    overflow-hidden

                    max-sm:order-2
                    max-sm:block
                "
            >
                <img
                    class="h-full w-full object-cover"
                    src="{{ asset('vendor/frontend-sevia/images/showroom-mobile.png') }}"
                    alt="Шоурум Sevia"
                >
            </figure>


            {{-- Цитата + преимущества + кнопка --}}
            <div
                class="
        flex
        w-full
        flex-col
        items-start
        gap-7

        sm:max-[1099px]:mt-[30px]
        sm:max-[1099px]:gap-0
        sm:max-[1099px]:border-t
        sm:max-[1099px]:border-[#EFE4D9]

        max-sm:contents
    "
            >

                {{-- Цитата --}}
                <blockquote
                    class="
                        m-0
                        flex
                        w-full
                        flex-col
                        gap-0
                        py-4

                        sm:max-[1099px]:py-[11.5px]

                        max-sm:order-3
                        max-sm:gap-0
                        max-sm:p-0
                    "
                >
                    <p
                        class="
                            m-0
                            font-cormorant
                            text-[21.2px]
                            font-normal
                            italic
                            leading-[33px]
                            text-[#5B2730]

                            sm:max-[1099px]:text-[18.1px]
                            sm:max-[1099px]:leading-[29px]

                            max-sm:text-[17px]
                            max-sm:font-medium
                            max-sm:leading-[140%]
                            max-sm:text-[#7A4751]
                        "
                    >
                        «Sevia починалася з моєї власної шафи з парфумами та маленького Instagram-акаунту.
                        Мені хотілося, щоб люди могли спробувати нішевий парфум, не платячи одразу
                        за повний флакон.»
                    </p>

                    <footer
                        class="
                            mt-2
                            text-[11px]
                            font-medium
                            uppercase
                            leading-[17px]
                            tracking-[2.42px]
                            text-[#A98088]

                            sm:max-[1099px]:mt-0
                            sm:max-[1099px]:text-[10.5px]
                            sm:max-[1099px]:leading-[16px]
                            sm:max-[1099px]:tracking-[2.299px]
                            sm:max-[1099px]:text-[#8A5D66]

                            max-sm:text-[10px]
                            max-sm:leading-3
                            max-sm:tracking-[1px]
                        "
                    >
                        — наталія, засновниця Sevia
                    </footer>
                </blockquote>


                {{-- Преимущества --}}
                <ul
                    class="
                        m-0
                        flex
                        w-full
                        list-none
                        flex-col
                        gap-[26px]
                        p-0
                        pt-2

                        sm:max-[1099px]:gap-[18.7px]
                        sm:max-[1099px]:pt-[27px]
                        sm:max-[1099px]:pb-[21px]

                        max-sm:order-5
                        max-sm:gap-0
                        max-sm:pt-1.5
                    "
                >

                    {{-- 01 --}}
                    <li
                        class="
                            grid
                            grid-cols-[24px_1fr]
                            gap-3

                            sm:max-[1099px]:grid-cols-[17px_1fr]
                            sm:max-[1099px]:gap-[14.4px]
                            sm:max-[1099px]:pb-[18px]

                            max-sm:h-[70px]
                            max-sm:grid-cols-[15px_1fr]
                            max-sm:border-t
                            max-sm:border-[#E8DAD0]
                            max-sm:py-3.5
                        "
                    >
                        <span
                            class="
                                font-cormorant
                                text-[24px]
                                font-normal
                                italic
                                leading-[22px]
                                text-[#A98088]

                                sm:max-[1099px]:text-[22.8px]
                                sm:max-[1099px]:leading-[21px]

                                max-sm:text-[18px]
                                max-sm:font-medium
                                max-sm:not-italic
                                max-sm:text-[#CD9B97]
                            "
                        >
                            01
                        </span>

                        <span class="sm:max-[1099px]:flex sm:max-[1099px]:flex-col sm:max-[1099px]:gap-[2.6px]">
                            <strong
                                class="
                                    block
                                    font-cormorant
                                    text-[24px]
                                    font-medium
                                    leading-[27px]
                                    text-[#5B2730]

                                    sm:max-[1099px]:text-[22.8px]
                                    sm:max-[1099px]:leading-[26px]

                                    max-sm:text-[18px]
                                    max-sm:leading-[22px]
                                "
                            >
                                Гарантія оригіналу.
                            </strong>

                            <span
                                class="
                                    block
                                    text-[16px]
                                    font-light
                                    leading-[21px]
                                    text-[#7A4751]

                                    sm:max-[1099px]:text-[15.2px]
                                    sm:max-[1099px]:font-normal
                                    sm:max-[1099px]:leading-[20px]

                                    max-sm:text-[11.5px]
                                    max-sm:leading-[145%]
                                    max-sm:text-[#A98088]
                                "
                            >
                                Лише офіційні дистрибʼютори та європейські бутики.
                            </span>
                        </span>
                    </li>


                    {{-- 02 --}}
                    <li
                        class="
                            grid
                            grid-cols-[24px_1fr]
                            gap-3

                            sm:max-[1099px]:grid-cols-[17px_1fr]
                            sm:max-[1099px]:gap-[14.4px]
                            sm:max-[1099px]:pb-[18px]

                            max-sm:h-[70px]
                            max-sm:grid-cols-[15px_1fr]
                            max-sm:border-t
                            max-sm:border-[#E8DAD0]
                            max-sm:py-3.5
                        "
                    >
                        <span
                            class="
                                font-cormorant
                                text-[24px]
                                font-normal
                                italic
                                leading-[22px]
                                text-[#A98088]

                                sm:max-[1099px]:text-[22.8px]
                                sm:max-[1099px]:leading-[21px]

                                max-sm:text-[18px]
                                max-sm:font-medium
                                max-sm:not-italic
                                max-sm:text-[#CD9B97]
                            "
                        >
                            02
                        </span>

                        <span class="sm:max-[1099px]:flex sm:max-[1099px]:flex-col sm:max-[1099px]:gap-[2.6px]">
                            <strong
                                class="
                                    block
                                    font-cormorant
                                    text-[24px]
                                    font-medium
                                    leading-[27px]
                                    text-[#5B2730]

                                    sm:max-[1099px]:text-[22.8px]
                                    sm:max-[1099px]:leading-[26px]

                                    max-sm:text-[18px]
                                    max-sm:leading-[22px]
                                "
                            >
                                Розпив від 3 мл.
                            </strong>

                            <span
                                class="
                                    block
                                    text-[16px]
                                    font-light
                                    leading-[21px]
                                    text-[#7A4751]

                                    sm:max-[1099px]:text-[15.2px]
                                    sm:max-[1099px]:font-normal
                                    sm:max-[1099px]:leading-[20px]

                                    max-sm:text-[11.5px]
                                    max-sm:leading-[145%]
                                    max-sm:text-[#A98088]
                                "
                            >
                                Спробуй вдома й повертайся за більшим обʼємом, коли впевнишся, що це твоє.
                            </span>
                        </span>
                    </li>


                    {{-- 03 --}}
                    <li
                        class="
                            grid
                            grid-cols-[24px_1fr]
                            gap-3

                            sm:max-[1099px]:grid-cols-[17px_1fr]
                            sm:max-[1099px]:gap-[14.4px]

                            max-sm:h-[70px]
                            max-sm:grid-cols-[15px_1fr]
                            max-sm:border-y
                            max-sm:border-[#E8DAD0]
                            max-sm:py-3.5
                        "
                    >
                        <span
                            class="
                                font-cormorant
                                text-[24px]
                                font-normal
                                italic
                                leading-[22px]
                                text-[#A98088]

                                sm:max-[1099px]:text-[22.8px]
                                sm:max-[1099px]:leading-[21px]

                                max-sm:text-[18px]
                                max-sm:font-medium
                                max-sm:not-italic
                                max-sm:text-[#CD9B97]
                            "
                        >
                            03
                        </span>

                        <span class="sm:max-[1099px]:flex sm:max-[1099px]:flex-col sm:max-[1099px]:gap-[2.6px]">
                            <strong
                                class="
                                    block
                                    font-cormorant
                                    text-[24px]
                                    font-medium
                                    leading-[27px]
                                    text-[#5B2730]

                                    sm:max-[1099px]:text-[22.8px]
                                    sm:max-[1099px]:leading-[26px]

                                    max-sm:text-[18px]
                                    max-sm:leading-[22px]
                                "
                            >
                                Тепла консультація.
                            </strong>

                            <span
                                class="
                                    block
                                    text-[16px]
                                    font-light
                                    leading-[21px]
                                    text-[#7A4751]

                                    sm:max-[1099px]:text-[15.2px]
                                    sm:max-[1099px]:font-normal
                                    sm:max-[1099px]:leading-[20px]

                                    max-sm:text-[11.5px]
                                    max-sm:leading-[145%]
                                    max-sm:text-[#A98088]
                                "
                            >
                                Допоможемо знайти аромат під настрій, сезон і твою шкіру.
                            </span>
                        </span>
                    </li>
                </ul>


                {{-- Кнопка --}}
                <a
                    class="
        hidden
        box-border
        items-center
        justify-center
        border
        border-[#5B2730]
        bg-[#FDFBF8]
        text-[#5B2730]

        sm:max-[1099px]:inline-flex
        sm:max-[1099px]:h-[45.02px]
        sm:max-[1099px]:w-[213.3px]
        sm:max-[1099px]:gap-0
        sm:max-[1099px]:px-[26.648px]
        sm:max-[1099px]:pt-[11.2px]
        sm:max-[1099px]:pb-[11.82px]

        max-sm:order-6
        max-sm:mt-4
        max-sm:h-[43px]
        max-sm:w-[223px]
        max-sm:px-[26px]
        max-sm:py-3.5
    "
                    href="#catalog"
                >
    <span
        class="
            flex
            shrink-0
            items-center
            justify-center

            sm:max-[1099px]:h-[20px]
            sm:max-[1099px]:w-[146px]
            sm:max-[1099px]:text-[12.4px]
            sm:max-[1099px]:font-medium
            sm:max-[1099px]:leading-[19px]
            sm:max-[1099px]:tracking-[1.482px]
            sm:max-[1099px]:uppercase

            max-sm:text-[12px]
            max-sm:font-medium
            max-sm:uppercase
            max-sm:leading-none
            max-sm:tracking-[1.4px]
        "
    >
        Перейти в каталог
    </span>

                    <span
                        class="
            flex
            shrink-0
            items-center

            sm:max-[1099px]:h-[20px]
            sm:max-[1099px]:w-[12px]
            sm:max-[1099px]:text-[12.4px]
            sm:max-[1099px]:font-medium
            sm:max-[1099px]:leading-[19px]
            sm:max-[1099px]:tracking-[1.482px]

            max-sm:ml-2
            max-sm:text-[12px]
        "
                        aria-hidden="true"
                    >
        →
    </span>
                </a>
            </div>
        </div>


        {{-- DESKTOP + TABLET фотографии --}}
        <div
            class="
        absolute
        right-0
        top-[123.08px]
        h-[870px]
        w-[565px]

        min-[1600px]:top-[123px]
        min-[1600px]:h-[1324px]
        min-[1600px]:w-[691px]

        sm:max-[1099px]:relative
        sm:max-[1099px]:left-auto
        sm:max-[1099px]:right-auto
        sm:max-[1099px]:top-auto
        sm:max-[1099px]:mt-[40px]
        sm:max-[1099px]:h-[560px]
        sm:max-[1099px]:w-full

        max-sm:hidden
    "
        >

            {{-- Большая фотография --}}
            <figure
                class="
                    absolute
                    left-[109px]
                    top-0
                    m-0
                    flex
                    h-[600px]
                    w-[456px]
                    items-center
                    justify-center
                    bg-[#F8EDE7]
                    p-9
                    shadow-[0_2px_12px_rgba(91,39,48,0.04)]

                    min-[1600px]:left-[95px]
                    min-[1600px]:top-[75px]
                    min-[1600px]:h-[800px]
                    min-[1600px]:w-[608px]
                    min-[1600px]:p-[13px]

                    sm:max-[1099px]:left-[36%]
                    sm:max-[1099px]:top-0
                    sm:max-[1099px]:h-[470px]
                    sm:max-[1099px]:w-[64%]
                    sm:max-[1099px]:p-[7px]
                "
            >
                <img
                    class="
                        h-[580px]
                        w-[436px]
                        object-cover

                        min-[1600px]:h-[773px]
                        min-[1600px]:w-[581px]

                        sm:max-[1099px]:h-full
                        sm:max-[1099px]:w-full
                    "
                    src="{{ asset('vendor/frontend-sevia/images/try-new-up.png') }}"
                    alt=""
                >
            </figure>


            {{-- Нижняя фотография --}}
            <figure
                class="
                    absolute
                    left-0
                    top-[550px]
                    m-0
                    flex
                    h-80
                    w-80
                    items-center
                    justify-center
                    bg-[#F8EDE7]
                    p-9
                    shadow-[0_2px_12px_rgba(91,39,48,0.04)]

                    min-[1600px]:left-[-51px]
                    min-[1600px]:top-[808px]
                    min-[1600px]:h-[427px]
                    min-[1600px]:w-[427px]
                    min-[1600px]:p-[13px]

                    sm:max-[1099px]:left-0
                    sm:max-[1099px]:top-[258px]
                    sm:max-[1099px]:h-[302px]
                    sm:max-[1099px]:w-[42%]
                    sm:max-[1099px]:p-[7px]
                "
            >
                <img
                    class="
                        size-[300px]
                        object-cover

                        min-[1600px]:size-[400px]

                        sm:max-[1099px]:h-full
                        sm:max-[1099px]:w-full
                    "
                    src="{{ asset('vendor/frontend-sevia/images/try-new-down.png') }}"
                    alt=""
                >
            </figure>

        </div>
    </div>
</section>
