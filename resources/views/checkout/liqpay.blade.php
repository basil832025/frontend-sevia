@extends('front.sevia::layouts.app')

@section('title', 'Оплата замовлення №' . ($order->number ?? $order->id))

@section('content')
    @php
        $money = fn ($value) => number_format((float) $value, 0, ',', ' ') . ' ₴';
        $saveEmailAction = route('checkout.pay.liqpay.email', $order);
        $itemsCount = (int) $order->items->sum('qty');
    @endphp

    <section class="mx-auto flex w-full max-w-[1180px] gap-14 px-6 py-14 max-lg:flex-col max-sm:px-5 max-sm:py-8">
        <div class="flex max-w-[700px] flex-1 flex-col gap-7">
            <nav class="flex items-center gap-4 text-[12.5px] leading-[19px] tracking-[0.125px]">
                <span class="text-[#7A4751]">Телефон</span>
                <span class="h-px w-12 bg-[#E8DAD0]"></span>
                <span class="text-[#7A4751]">Дані отримувача</span>
                <span class="h-px w-12 bg-[#E8DAD0]"></span>
                <span class="font-medium text-[#5B2730]">Оплата</span>
            </nav>

            <div class="flex flex-col gap-3">
                <h1 class="m-0 font-cormorant text-[52px] font-semibold leading-[53px] tracking-[-0.78px] text-[#5B2730] max-sm:text-[40px] max-sm:leading-[42px]">
                    Оплата замовлення
                </h1>
                <p class="m-0 text-[14px] leading-[22px] text-[#7A4751]">
                    Замовлення №{{ $order->number ?? $order->id }} створене як чернетка. Після успішної оплати воно автоматично перейде в нові замовлення, а листи підуть адміністратору та клієнту.
                </p>
            </div>

            @if (session('success'))
                <div class="border border-[#4D8566]/35 bg-[#F2FAF5] px-5 py-4 text-[13.5px] font-medium leading-5 text-[#4D8566]">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->has('contact_email'))
                <div class="border border-[#B84249]/35 bg-[#FCF4F3] px-5 py-4 text-[13.5px] font-medium leading-5 text-[#B84249]">
                    {{ $errors->first('contact_email') }}
                </div>
            @endif

            <div class="border border-[#E8DAD0] bg-white">
                <div class="flex items-baseline justify-between border-b border-[#E8DAD0] px-6 py-5">
                    <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.68px] text-[#A98088]">До сплати зараз</span>
                    <strong class="font-cormorant text-[34px] font-semibold leading-10 text-[#5B2730]">{{ $money($order->grand_total) }}</strong>
                </div>

                <div class="flex flex-col gap-5 px-6 py-6">
                    @if ($emailRequired)
                        <p class="m-0 text-[13.5px] leading-5 text-[#7A4751]">
                            Щоб перейти до оплати, вкажіть email. На нього надішлемо підтвердження замовлення та фіскальний чек після оплати.
                        </p>
                        <form class="flex flex-col gap-4" method="POST" action="{{ $saveEmailAction }}">
                            @csrf
                            <label class="flex flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]">
                                Електронна пошта <span class="sr-only">*</span>
                                <input class="h-[48px] border-b border-[#E8DAD0] bg-transparent text-[15.5px] font-normal normal-case leading-[23px] tracking-normal text-[#5B2730] outline-none placeholder:text-[#C9A9B0] focus:border-[#5B2730]" type="email" name="contact_email" value="{{ old('contact_email', $clientEmail) }}" required>
                            </label>
                            <button class="h-[52px] bg-[#5B2730] px-10 text-[11.5px] font-medium uppercase leading-[17px] tracking-[1.84px] text-[#FFF8F4]" type="submit">
                                Зберегти email
                            </button>
                        </form>
                    @else
                        <div class="flex flex-col gap-1">
                            <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.68px] text-[#A98088]">Email для листів</span>
                            <span class="text-[15px] leading-[22px] text-[#5B2730]">{{ $clientEmail }}</span>
                        </div>
                        <div class="sevia-liqpay-form">
                            {!! $liqpayForm !!}
                        </div>
                        <form method="POST" action="{{ $saveEmailAction }}" class="flex items-end gap-3 max-sm:flex-col max-sm:items-stretch">
                            @csrf
                            <label class="flex flex-1 flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]">
                                Змінити email
                                <input class="h-[44px] border-b border-[#E8DAD0] bg-transparent text-[15px] font-normal normal-case leading-[22px] tracking-normal text-[#5B2730] outline-none focus:border-[#5B2730]" type="email" name="contact_email" value="{{ old('contact_email', $clientEmail) }}">
                            </label>
                            <button class="h-[44px] border border-[#E8DAD0] px-5 text-[10.5px] font-medium uppercase tracking-[1.47px] text-[#7A4751]" type="submit">
                                Зберегти
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <aside class="w-[400px] shrink-0 border border-[#E8DAD0] bg-white max-lg:w-full max-lg:max-w-[700px]">
            <div class="flex h-[79px] items-baseline justify-between border-b border-[#E8DAD0] px-[26px] pb-5 pt-[22px]">
                <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.68px] text-[#A98088]">Замовлення</span>
                <strong class="font-cormorant text-[24px] font-semibold leading-9 text-[#5B2730]">{{ $money($order->grand_total) }}</strong>
            </div>
            <div class="px-[26px] py-5">
                <div class="flex justify-between text-[13.5px] leading-5 text-[#7A4751]">
                    <span>Аромати · {{ $itemsCount }}</span>
                    <span class="text-[#5B2730]">{{ $money($order->total_price_sale ?? $order->total_price) }}</span>
                </div>
                <div class="mt-3 flex justify-between text-[13.5px] leading-5 text-[#7A4751]">
                    <span>Доставка · оплата Новій пошті</span>
                    <span class="text-[#5B2730]">{{ ((float) $order->shipping_price) > 0 ? $money($order->shipping_price) : 'безкоштовно' }}</span>
                </div>
                <div class="mt-5 flex items-baseline justify-between border-t border-[#E8DAD0] pt-5">
                    <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.68px] text-[#7A4751]">До сплати зараз</span>
                    <strong class="font-cormorant text-[30px] font-semibold leading-[45px] text-[#5B2730]">{{ $money($order->grand_total) }}</strong>
                </div>
            </div>
        </aside>
    </section>
@endsection
