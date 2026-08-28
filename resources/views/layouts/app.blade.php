<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sevia')</title>
    <meta name="description" content="@yield('meta_description', 'Sevia perfume storefront')">
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
            <h2 class="m-0 text-center font-cormorant text-[24px] font-medium leading-[29px] text-[#5B2730]" id="logout-modal-title">Вийти з акаунта?</h2>
            <p class="m-0 w-full max-w-[289px] text-center text-[13px] leading-5 text-[#7A4751]">Твоє обране та історія замовлень залишаться. Ти зможеш увійти будь-коли.</p>
            <span class="h-1.5 w-2.5" aria-hidden="true"></span>
            <button class="flex h-[45px] w-full max-w-[289px] items-center justify-center border-0 bg-[#5B2730] px-0 py-[15px] text-[12px] font-medium uppercase leading-[15px] tracking-[1.2px] text-white" type="button" data-logout-confirm>Так, вийти</button>
            <button class="flex h-[43px] w-full max-w-[289px] items-center justify-center border border-[#5B2730] bg-white px-0 py-3.5 text-[12px] font-medium uppercase leading-[15px] tracking-[1.2px] text-[#5B2730]" type="button" data-logout-cancel>Залишитись</button>
        </div>
    </div>
</body>
</html>
