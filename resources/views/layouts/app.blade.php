<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sevia')</title>
    <meta name="description" content="@yield('meta_description', 'Sevia perfume storefront')">
    <script>
        window.seviaText = {!! json_encode([
            'request_error' => st('js.request_error', 'Не вдалося виконати запит.'),
            'phone_full' => st('js.phone_full', 'Введіть номер повністю: +380 XX XXX XX XX.'),
            'sending_code' => st('js.sending_code', 'Надсилаємо код...'),
            'code_sent' => st('js.code_sent', 'Код надіслано. Введіть 4 цифри з СМС.'),
            'checking_code' => st('js.checking_code', 'Перевіряємо код...'),
            'code_digits' => st('js.code_digits', 'Введіть 4 цифри з СМС.'),
            'favorite_remove' => st('js.favorite_remove', 'Видалити з обраного'),
            'favorite_add' => st('js.favorite_add', 'Додати в обране'),
            'city_not_found' => st('js.city_not_found', 'Місто не знайдено'),
            'warehouse_not_found' => st('js.warehouse_not_found', 'Відділення не знайдено'),
            'choose_city' => st('js.choose_city', 'Спочатку оберіть місто.'),
            'load_cities' => st('js.load_cities', 'Завантажуємо міста...'),
            'load_warehouses' => st('js.load_warehouses', 'Завантажуємо відділення...'),
            'load_error' => st('js.load_error', 'Не вдалося завантажити дані'),
            'free' => st('js.free', 'безкоштовно'),
            'new_recipient' => st('js.new_recipient', 'Новий отримувач'),
            'order_error' => st('js.order_error', 'Не вдалося оформити замовлення.'),
            'submitting' => st('js.submitting', 'Оформлюємо...'),
            'review_error' => st('js.review_error', 'Не вдалося відправити відгук. Спробуйте пізніше.'),
            'network_error' => st('js.network_error', 'Мережа недоступна. Спробуйте пізніше.'),
            'collapse' => st('js.collapse', 'Згорнути'),
            'confirm' => st('js.confirm', 'Підтвердити'),
            'nova_poshta' => st('js.nova_poshta', 'Нова Пошта'),
            'oblast' => st('js.oblast', 'обл.'),
            'district' => st('js.district', 'р-н.'),
            'weekdays' => st('js.weekdays', 'Пн - Пт'),
            'resend_timer' => st('js.resend_timer', 'Надіслати повторно можна через'),
            'resend_ready' => st('js.resend_ready', 'Можна надіслати код повторно.'),
            'contact_save_error' => st('js.contact_save_error', 'Не вдалося зберегти контактні дані.'),
            'load_cities_error' => st('js.load_cities_error', 'Не вдалося завантажити міста'),
            'street_not_found' => st('js.street_not_found', 'Вулиці не знайдено'),
            'searching_streets' => st('js.searching_streets', 'Шукаємо вулиці...'),
            'load_streets_error' => st('js.load_streets_error', 'Не вдалося завантажити вулиці'),
            'street_required' => st('js.street_required', 'Вкажіть вулицю.'),
            'house_required' => st('js.house_required', 'Вкажіть будинок.'),
            'postomat' => st('js.postomat', 'Поштомат'),
            'warehouse' => st('js.warehouse', 'Відділення'),
            'choose_postomat' => st('js.choose_postomat', 'Оберіть поштомат'),
            'choose_warehouse' => st('js.choose_warehouse', 'Оберіть відділення'),
            'postomats_not_found' => st('js.postomats_not_found', 'Поштомати не знайдено'),
            'load_postomats' => st('js.load_postomats', 'Завантажуємо поштомати...'),
            'choose_postomat_error' => st('js.choose_postomat_error', 'Оберіть поштомат Нової Пошти.'),
            'choose_warehouse_error' => st('js.choose_warehouse_error', 'Оберіть відділення Нової Пошти.'),
            'postomat_search' => st('js.postomat_search', 'Введіть адресу або номер поштомата'),
            'warehouse_search' => st('js.warehouse_search', 'Введіть адресу або номер відділення'),
            'field' => st('js.field', 'Поле'),
            'phone_invalid' => st('js.phone_invalid', 'Введіть коректний мобільний номер.'),
            'to_cart' => st('js.to_cart', 'У кошик'),
            'choose_one_more' => st('js.choose_one_more', 'Оберіть ще 1 аромат'),
            'choose_more' => st('js.choose_more', 'Оберіть ще'),
            'fragrances_few' => st('js.fragrances_few', 'аромати'),
            'fragrances_many' => st('js.fragrances_many', 'ароматів'),
            'remove' => st('js.remove', 'Прибрати'),
            'add' => st('js.add', 'Додати'),
            'set_ready' => st('js.set_ready', 'Сет зібрано'),
            'of' => st('js.of', 'із'),
            'choose_fragrances' => st('js.choose_fragrances', 'Обери ароматів'),
            'remaining' => st('js.remaining', 'лишилось'),
            'adding' => st('js.adding', 'Додаємо...'),
        ], JSON_UNESCAPED_UNICODE) !!};
    </script>
    @vite(['packages/frontend-sevia/resources/css/app.css', 'packages/frontend-sevia/resources/js/app.js'], 'build/frontend-sevia')
</head>
<body class="site-body {{ request()->routeIs('auth.phone', 'checkout', 'checkout.*', 'cart.page') ? 'site-body--checkout' : '' }}">
    <div class="site-page">
        @include('front.sevia::partials.header')

        <main class="site-main">
            @yield('content')
        </main>

        @include('front.sevia::partials.footer')
    </div>

    <div class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#5B2730]/20 px-5" data-logout-modal aria-hidden="true">
        <div class="flex w-full max-w-[337px] flex-col items-center gap-2.5 bg-white px-6 pb-[22px] pt-7" role="dialog" aria-modal="true" aria-labelledby="logout-modal-title">
            <h2 class="m-0 text-center font-cormorant text-[24px] font-medium leading-[29px] text-[#5B2730]" id="logout-modal-title">{{ st('layout.logout_title', 'Вийти з акаунта?') }}</h2>
            <p class="m-0 w-full max-w-[289px] text-center text-[13px] leading-5 text-[#7A4751]">{{ st('layout.logout_description', 'Твоє обране та історія замовлень залишаться. Ти зможеш увійти будь-коли.') }}</p>
            <span class="h-1.5 w-2.5" aria-hidden="true"></span>
             <button class="flex h-[45px] w-full max-w-[289px] items-center justify-center border-0 bg-[#5B2730] px-0 py-[15px] text-[12px] font-medium uppercase leading-[15px] tracking-[1.2px] text-white" type="button" data-logout-confirm>{{ st('layout.logout_confirm', 'Так, вийти') }}</button>
             <button class="flex h-[43px] w-full max-w-[289px] items-center justify-center border border-[#5B2730] bg-white px-0 py-3.5 text-[12px] font-medium uppercase leading-[15px] tracking-[1.2px] text-[#5B2730]" type="button" data-logout-cancel>{{ st('layout.logout_cancel', 'Залишитись') }}</button>
        </div>
    </div>
</body>
</html>
