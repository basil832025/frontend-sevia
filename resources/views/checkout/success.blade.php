@extends('front.sevia::layouts.app')

@section('title', st('checkout-success.title', 'Замовлення оформлено'))

@section('content')
    @php($successText = static fn (string $key, string $default): string => st("checkout-success.$key", $default))
    @php
        $money = fn ($value) => number_format((float) $value, 0, ',', ' ') . ' ₴';
        $payment = $order->payment instanceof \App\Enums\PaymentMethodEnum
            ? $order->payment
            : \App\Enums\PaymentMethodEnum::tryFrom((int) $order->payment);
        $isPaidOnline = $payment === \App\Enums\PaymentMethodEnum::LIQPAY
            && $order->status !== \App\Enums\OrderStatus::Cart;
        $isPaymentPending = $payment === \App\Enums\PaymentMethodEnum::LIQPAY
            && $order->status === \App\Enums\OrderStatus::Cart;
        $clientEmail = trim((string) ($order->clients?->email ?? ''));
    @endphp

    <section class="mx-auto flex min-h-[560px] w-full max-w-[960px] flex-col justify-center px-6 py-16 max-sm:px-5 max-sm:py-10">
        <div class="border border-[#E8DAD0] bg-white px-8 py-9 max-sm:px-5">
            <div class="flex flex-col gap-4">
                 <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.89px] text-[#A98088]">{{ $successText('order_number', 'Замовлення №') }}{{ $order->number ?? $order->id }}</span>
                <h1 class="m-0 font-cormorant text-[52px] font-semibold leading-[53px] tracking-[-0.78px] text-[#5B2730] max-sm:text-[40px] max-sm:leading-[42px]">
                     {{ $successText('heading', 'Дякуємо, замовлення прийнято') }}
                </h1>
                <p class="m-0 max-w-[620px] text-[14px] leading-[22px] text-[#7A4751]">
                    @if ($isPaidOnline)
                         {{ $successText('paid_notice', 'Оплату отримано. Ми надіслали підтвердження') }}{{ $clientEmail !== '' ? ' ' . $successText('to', 'на') . ' ' . $clientEmail : '' }} {{ $successText('processed_notice', 'і передали замовлення в обробку.') }}
                    @elseif ($isPaymentPending)
                         {{ $successText('pending_notice', 'LiqPay прийняв перехід після оплати. Перевіряємо підтвердження платежу, після callback замовлення перейде в обробку, а листи підуть адміністратору та клієнту.') }}
                    @else
                         {{ $successText('processing_notice', 'Замовлення передано в обробку. Адміністратор отримає повідомлення, а підтвердження буде надіслано на email клієнта.') }}
                    @endif
                </p>
            </div>

            <div class="mt-8 grid grid-cols-3 gap-5 border-y border-[#F0E6DE] py-6 max-sm:grid-cols-1">
                <div class="flex flex-col gap-1">
                     <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.68px] text-[#A98088]">{{ $successText('status', 'Статус') }}</span>
                     <span class="text-[15px] leading-[22px] text-[#5B2730]">{{ $order->status?->getLabel() ?? $successText('new', 'Нове') }}</span>
                </div>
                <div class="flex flex-col gap-1">
                     <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.68px] text-[#A98088]">{{ $successText('payment', 'Оплата') }}</span>
                    <span class="text-[15px] leading-[22px] text-[#5B2730]">{{ $payment?->label('uk') ?? 'LiqPay' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                     <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.68px] text-[#A98088]">{{ $successText('payable', 'До сплати') }}</span>
                    <span class="font-cormorant text-[30px] font-semibold leading-[34px] text-[#5B2730]">{{ $money($order->grand_total) }}</span>
                </div>
            </div>

            <div class="mt-7 flex flex-wrap gap-3">
                <a class="flex h-[52px] items-center justify-center bg-[#5B2730] px-10 text-[11.5px] font-medium uppercase leading-[17px] tracking-[1.84px] text-[#FFF8F4]" href="{{ route('catalog.index') }}">
                     {{ $successText('catalog', 'До каталогу') }}
                </a>
                <a class="flex h-[52px] items-center justify-center border border-[#E8DAD0] px-10 text-[11.5px] font-medium uppercase leading-[17px] tracking-[1.84px] text-[#7A4751]" href="{{ route('home') }}">
                     {{ $successText('home', 'На головну') }}
                </a>
            </div>
        </div>
    </section>
@endsection
