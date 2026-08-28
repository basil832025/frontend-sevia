<footer class="mt-12 border-t border-[#E8DAD0] bg-[#F8EDE7] max-sm:mt-0">
    <div class="mx-auto flex w-full max-w-[1304px] flex-col gap-7 px-4 py-[80px] pb-7 max-sm:max-w-none max-sm:gap-[26px] max-sm:px-6 max-sm:py-12 max-sm:pb-[26px]">
        <div class="grid min-h-[283px] grid-cols-[minmax(270px,1fr)_repeat(3,minmax(210px,265px))] gap-[45px] max-lg:grid-cols-2 max-sm:min-h-0 max-sm:grid-cols-1 max-sm:gap-[22px]">
            <div>
                <a class="block h-[169px] w-[175px] max-sm:h-[72px] max-sm:w-[74px]" href="{{ route('home') }}" aria-label="Sevia">
                    <img class="block h-[169px] w-[175px] object-contain max-sm:h-[72px] max-sm:w-[74px]" src="{{ asset('vendor/frontend-sevia/images/logo.png') }}" alt="Sevia" width="175" height="169">
                </a>
                <p class="mt-2 max-w-[270px] font-cormorant text-[16.6px] italic leading-[25px] text-[#7A4751] max-sm:mt-3 max-sm:max-w-none max-sm:font-sans max-sm:text-[12px] max-sm:not-italic max-sm:leading-[18px]">Сертифікований магазин нішевих та люксових парфумів на розпив в Україні.</p>
                <a class="mt-2 inline-block font-cormorant text-[22px] font-bold leading-[28px] text-[#5B2730] max-sm:mt-3 max-sm:text-[24px] max-sm:leading-8" href="tel:+380671234567">+380 67 123 45 67</a>
            </div>

            <nav class="flex flex-col items-start gap-[7.2px] text-[16px] font-light leading-[20px] tracking-[0.56px] text-[#7A4751] max-sm:gap-[9px] max-sm:text-[12.5px] max-sm:font-normal max-sm:leading-[15px] max-sm:tracking-normal" aria-label="Footer catalog navigation">
                <h2 class="mb-[15px] font-cormorant text-[24px] font-medium leading-[25px] tracking-normal text-[#5B2730] max-sm:mb-0 max-sm:text-[18px] max-sm:leading-[22px]">Магазин</h2>
                <a class="hover:text-[#5B2730]" href="#catalog">Каталог</a>
                <a class="hover:text-[#5B2730]" href="#discovery">Discovery 5×3</a>
                <a class="hover:text-[#5B2730]" href="#collections">Колекції</a>
                <a class="hover:text-[#5B2730]" href="{{ route('sale.index') }}">Sale</a>
            </nav>

            <nav class="flex flex-col items-start gap-[7.2px] text-[16px] font-light leading-[20px] tracking-[0.56px] text-[#7A4751] max-sm:gap-[9px] max-sm:text-[12.5px] max-sm:font-normal max-sm:leading-[15px] max-sm:tracking-normal" aria-label="Footer service navigation">
                <h2 class="mb-[15px] font-cormorant text-[24px] font-medium leading-[25px] tracking-normal text-[#5B2730] max-sm:mb-0 max-sm:text-[18px] max-sm:leading-[22px]">Сервіс</h2>
                <a class="hover:text-[#5B2730]" href="{{ route('delivery-payment') }}">Доставка та оплата</a>
                <a class="hover:text-[#5B2730]" href="{{ route('faq') }}">Часті запитання</a>
                <a class="hover:text-[#5B2730]" href="#">Шоу-рум</a>
                <a class="hover:text-[#5B2730]" href="{{ route('contacts') }}">Контакти</a>
            </nav>

            <nav class="flex flex-col items-start gap-[7.2px] text-[16px] font-light leading-[20px] tracking-[0.56px] text-[#7A4751] max-sm:gap-[9px] max-sm:text-[12.5px] max-sm:font-normal max-sm:leading-[15px] max-sm:tracking-normal" aria-label="Footer contact navigation">
                <h2 class="mb-[15px] font-cormorant text-[24px] font-medium leading-[25px] tracking-normal text-[#5B2730] max-sm:mb-0 max-sm:text-[18px] max-sm:leading-[22px]">Звʼязок</h2>
                <a class="hover:text-[#5B2730]" href="#">Instagram · sevia.parfume</a>
                <a class="hover:text-[#5B2730]" href="#">Instagram · sevia.lingerie</a>
                <a class="hover:text-[#5B2730]" href="#">Telegram · @sevia</a>
                <a class="hover:text-[#5B2730]" href="mailto:hello@sevia.ua">hello@sevia.ua</a>
            </nav>
        </div>

        <div class="hidden h-px w-full bg-[#E8DAD0] max-sm:block"></div>

        <div class="flex items-center justify-between gap-8 text-[11px] uppercase leading-[17px] tracking-[1.98px] text-[#A98088] max-lg:flex-col max-lg:items-start max-lg:gap-2.5 max-sm:gap-1 max-sm:text-[10px] max-sm:leading-3 max-sm:tracking-[0.6px]">
            <span>© 2026 Sevia · Всі права захищені</span>
            <span>Designed by Mariia Kunda</span>
        </div>
    </div>
</footer>
