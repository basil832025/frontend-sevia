<section class="bg-white px-[72px] pt-[180px] max-xl:px-6 max-xl:py-20 max-sm:px-0 max-sm:pb-12 max-sm:pt-14">
    <div class="mx-auto flex h-[791px] max-w-[1275px] items-center gap-16 max-xl:h-auto max-xl:flex-col max-sm:gap-5">
    <figure class="relative m-0 flex h-[791px] w-[633px] shrink-0 items-center justify-center overflow-hidden bg-[#F8EDE7] p-9 shadow-[0_2px_12px_rgba(91,39,48,0.04)] max-xl:h-[680px] max-xl:w-full max-xl:max-w-[633px] max-sm:order-2 max-sm:h-[260px] max-sm:max-w-none max-sm:bg-white max-sm:p-0 max-sm:shadow-none">
        <img class="h-full w-full object-cover mix-blend-multiply max-sm:hidden" src="{{ asset('vendor/frontend-sevia/images/discovery-band.png') }}" alt="Discovery 5x3 мл">
        <img class="hidden h-full w-full object-cover mix-blend-multiply max-sm:block" src="{{ asset('vendor/frontend-sevia/images/discovery-band-mobile.png') }}" alt="Discovery 5x3 мл">
    </figure>

    <a class="hidden h-[43px] w-[208px] items-center justify-center gap-2 bg-[#5B2730] px-7 py-3.5 text-[12px] font-medium uppercase leading-none tracking-[1.4px] text-white max-sm:order-3 max-sm:inline-flex" href="#">
        <span>Зібрати свій сет</span>
        <span aria-hidden="true">→</span>
    </a>

    <div class="flex h-[450.16px] w-[578px] flex-col items-start gap-[27.5px] max-xl:h-auto max-xl:w-full max-xl:max-w-[578px] max-xl:items-center max-sm:order-1 max-sm:h-[198px] max-sm:max-w-none max-sm:gap-3.5 max-sm:px-6">
        <p class="m-0 w-full text-center text-[24px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:text-[10px] max-sm:leading-3 max-sm:tracking-[1.8px]">
            Discovery · 5 × 3 ML
        </p>
        <div class="flex w-full flex-col text-center">
            <h2 class="m-0 font-cormorant text-[72px] font-normal italic leading-[76px] text-[#5B2730] max-sm:text-[29px] max-sm:font-medium max-sm:leading-[35px]">
                Збирай 5 парфумів
            </h2>
            <p class="m-0 font-cormorant text-[72px] font-medium uppercase leading-none tracking-[-1.944px] text-[#D46568] max-sm:text-[29px] max-sm:normal-case max-sm:leading-[35px] max-sm:tracking-normal">
                лови -15% на усі!
            </p>
        </div>
        <p class="m-0 max-w-[549.44px] pr-[28.56px] text-[20px] font-light leading-[26px] text-[#7A4751] max-sm:max-w-none max-sm:pr-0 max-sm:text-center max-sm:text-[13.5px] max-sm:font-normal max-sm:leading-[150%]">
            Збирай свій сет із пʼяти ароматів по 3 мл і отримай −15% на весь сет.
        </p>
        <div class="flex h-8 items-center gap-3.5 pt-1.5 max-sm:h-[34px] max-sm:gap-2 max-sm:pb-1 max-sm:pt-0.5">
            @for ($i = 1; $i <= 5; $i++)
                <span class="flex size-8 items-center justify-center rounded-full border {{ $i <= 3 ? 'border-[#5B2730] bg-[#F3E3DF]' : 'border-[rgba(91,39,48,0.16)]' }} pb-[4.36px] pt-[3.64px] font-cormorant text-[14px] font-normal italic leading-[22px] text-[#5B2730] max-sm:grid max-sm:size-7 max-sm:place-items-center max-sm:border-[#CD9B97] max-sm:bg-transparent max-sm:p-0 max-sm:not-italic max-sm:leading-none">
                    {{ $i }}
                </span>
            @endfor
        </div>
        <div class="mt-[6px] flex items-center gap-8 max-sm:hidden">
            <a class="inline-flex h-[54px] min-w-[245px] items-center justify-center border border-[#5B2730] bg-[#FDFBF8] px-9 text-[13px] font-medium uppercase leading-5 tracking-[1.56px] text-[#5B2730]" href="#">
                Зібрати свій сет
            </a>
            <a class="inline-flex items-center gap-3 text-[13px] font-medium uppercase leading-5 tracking-[1.56px] text-[#5B2730]" href="#">
                Як це працює
                <span aria-hidden="true">→</span>
            </a>
        </div>
    </div>
    </div>
</section>
