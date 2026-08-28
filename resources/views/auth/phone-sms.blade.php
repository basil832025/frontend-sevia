@extends('front.sevia::layouts.app')

@section('title', 'Авторизація | Sevia')
@section('meta_description', 'Авторизація Sevia за номером телефону')

@section('content')
    @php
        $authItems = collect($checkoutItems ?? []);
        $authQty = (int) $authItems->sum(fn ($item) => (int) ($item['qty'] ?? 1));
        $authItemsSubtotal = (float) $authItems->sum(fn ($item) => (float) ($item['old_subtotal'] ?? $item['subtotal'] ?? 0));
        $authDiscount = max(0, $authItemsSubtotal - (float) ($checkoutTotal ?? 0));
        $authBottleBreakdown = [];
        $authBottleFee = (float) $authItems->sum(function ($item) {
            $meta = is_array($item['meta'] ?? null) ? $item['meta'] : [];
            return (float) ($meta['bottle_price'] ?? 0) * max(1, (int) ($item['qty'] ?? 1));
        });
        $authBottleCount = (int) $authItems->sum(function ($item) {
            $meta = is_array($item['meta'] ?? null) ? $item['meta'] : [];
            return ! empty($meta['bottle_price']) ? max(1, (int) ($item['qty'] ?? 1)) : 0;
        });
        foreach ($authItems as $item) {
            $meta = is_array($item['meta'] ?? null) ? $item['meta'] : [];

            if (empty($meta['bottle_price'])) {
                continue;
            }

            $bottleQty = max(1, (int) ($item['qty'] ?? 1));
            $bottleTitle = (string) ($meta['bottle_title'] ?? 'Стандартний');
            $bottleKey = (string) ($meta['bottle_id'] ?? $bottleTitle);
            $authBottleBreakdown[$bottleKey] = [
                'title' => $bottleTitle,
                'count' => (int) data_get($authBottleBreakdown, $bottleKey . '.count', 0) + $bottleQty,
            ];
        }
        $authBottleTitleLabel = static function (string $title, int $count): string {
            $label = trim(\Illuminate\Support\Str::lower($title));
            $label = preg_replace('/^флакон\s+/iu', '', $label) ?: $label;

            if ($count !== 1) {
                $label = str_replace(
                    ['стандартний', 'преміальний'],
                    ['стандартні', 'преміальні'],
                    $label
                );
            }

            return $count . ' ' . $label;
        };
        $authBottleSummaryLabel = static function (array $breakdown, int $fallbackCount) use ($authBottleTitleLabel): string {
            if ($breakdown === []) {
                return $fallbackCount . ' стандартні';
            }

            return collect($breakdown)
                ->map(fn (array $row): string => $authBottleTitleLabel((string) $row['title'], (int) $row['count']))
                ->implode(', ');
        };
        $authCartTotal = (float) ($checkoutTotal ?? 0) + $authBottleFee;
        $authFreeShippingFrom = max(0, (float) \App\Models\Setting::admin('cart.free_shipping_from', 0));
        $authFreeShippingLeft = $authFreeShippingFrom > 0 ? max(0, $authFreeShippingFrom - $authCartTotal) : 0;
        $authFreeShippingProgress = $authFreeShippingFrom > 0 ? min(100, max(0, ($authCartTotal / $authFreeShippingFrom) * 100)) : 0;
        $authHasFreeShipping = $authFreeShippingFrom > 0 && $authCartTotal >= $authFreeShippingFrom;
        $authQtyLabel = $authQty === 1 ? 'аромат' : ($authQty >= 2 && $authQty <= 4 ? 'аромати' : 'ароматів');
        $checkoutDelivery = session('checkout.delivery', []);
        $authDeliveryMethod = in_array((string) ($checkoutDelivery['delivery_method'] ?? ''), ['nova_branch', 'nova_postomat', 'nova_courier', 'sevia_pickup'], true)
            ? (string) $checkoutDelivery['delivery_method']
            : 'nova_branch';
        $authDeliveryMethodTitle = [
            'nova_branch' => 'Нова Пошта · відділення',
            'nova_postomat' => 'Нова Пошта · поштомат',
            'nova_courier' => 'Курʼєр Нової Пошти',
            'sevia_pickup' => 'Шоу-рум Sevia · самовивіз',
        ][$authDeliveryMethod];
        $authDeliveryMethodMeta = [
            'nova_branch' => '1–2 дні · отримання за телефоном',
            'nova_postomat' => '1–2 дні · код у СМС, без черг',
            'nova_courier' => '1–2 дні · привезе на адресу',
            'sevia_pickup' => 'сьогодні · вул. Хрещатик 22, Київ, з 11:00',
        ][$authDeliveryMethod];
        $authDeliveryMethodIcon = $authDeliveryMethod === 'sevia_pickup' ? 'S' : '✣';
    @endphp
    @php
        $authMoney = fn ($value): string => number_format((float) $value, 0, '.', ' ') . ' ₴';
        $checkoutStep = (string) ($checkoutStep ?? (($isAuthenticated ?? false) ? 'recipient' : 'phone'));
        $isDeliveryStep = ($isAuthenticated ?? false) && $checkoutStep === 'delivery';
        $authDeliveryPrice = match ($authDeliveryMethod) {
            'nova_courier' => $authHasFreeShipping ? 0 : 130,
            'sevia_pickup' => 0,
            default => $authHasFreeShipping ? 0 : 70,
        };
        $authNovaWarehousePrice = $authHasFreeShipping ? 0 : 70;
        $authNovaCourierPrice = $authHasFreeShipping ? 0 : 130;
        $authNovaWarehouseLabel = $authHasFreeShipping ? 'безкоштовно' : 'від ' . $authMoney($authNovaWarehousePrice);
        $authNovaCourierLabel = $authHasFreeShipping ? 'безкоштовно' : 'від ' . $authMoney($authNovaCourierPrice);
        $authDeliveryMethodPriceLabel = match ($authDeliveryMethod) {
            'nova_courier' => $authNovaCourierLabel,
            'sevia_pickup' => 'безкоштовно',
            default => $authNovaWarehouseLabel,
        };
        $authGrandTotal = $authCartTotal;
        $authCityValue = (string) ($checkoutDelivery['city'] ?? 'Київ');
        $authCityRef = (string) ($checkoutDelivery['city_ref'] ?? '8d5a980d-391c-11dd-90d9-001a92567626');
        $authCityName = (string) ($checkoutDelivery['city_name'] ?? $authCityValue);
        $authCityDisplayName = (string) ($checkoutDelivery['city_display_name'] ?? $authCityName);
        $authCityDetails = (string) ($checkoutDelivery['city_details'] ?? '');
        $authWarehouseRef = (string) ($checkoutDelivery['warehouse_ref'] ?? '');
        $authWarehouseName = (string) ($checkoutDelivery['warehouse_name'] ?? '');
        $authStreetRef = (string) ($checkoutDelivery['street_ref'] ?? '');
        $authStreet = (string) ($checkoutDelivery['street'] ?? '');
        $authHouse = (string) ($checkoutDelivery['house'] ?? '');
        $authApartment = (string) ($checkoutDelivery['apartment'] ?? '');
        $authFloor = (string) ($checkoutDelivery['floor'] ?? '');
        $authEntrance = (string) ($checkoutDelivery['entrance'] ?? '');
        $authElevator = (string) ($checkoutDelivery['elevator'] ?? '');
        $authBringToFloor = (bool) ($checkoutDelivery['bring_to_floor'] ?? false);
        $authPayment = in_array((string) ($checkoutDelivery['payment'] ?? ''), ['liqpay', 'cash'], true) ? (string) $checkoutDelivery['payment'] : 'liqpay';
        $authPayment = $authDeliveryMethod === 'sevia_pickup' ? $authPayment : 'liqpay';
        $authOtherRecipient = (bool) ($checkoutDelivery['other_recipient'] ?? false);
        $authConfirmWithoutCall = array_key_exists('confirm_without_call', $checkoutDelivery) ? (bool) $checkoutDelivery['confirm_without_call'] : true;
        $authGiftNoReceipt = (bool) ($checkoutDelivery['gift_no_receipt'] ?? false);
        $phoneDigits = preg_replace('/\D+/', '', (string) ($authenticatedPhone ?? ''));
        $phoneLocal = preg_replace('/^380/', '', $phoneDigits);
        $authPhonePretty = strlen($phoneLocal) === 9
            ? '+380 ' . substr($phoneLocal, 0, 2) . ' ' . substr($phoneLocal, 2, 3) . ' ' . substr($phoneLocal, 5, 2) . ' ' . substr($phoneLocal, 7, 2)
            : (string) ($authenticatedPhone ?? '');
        $clientNameValue = trim((string) (auth('web')->user()?->name ?? ''));
        $clientSurnameValue = trim((string) (auth('web')->user()?->surname ?? ''));
        $recipientNameParts = preg_split('/\s+/u', $clientNameValue) ?: [];
        $recipientFirstName = old('first_name', $recipientNameParts[0] ?? '');
        $recipientLastName = old('last_name', $clientSurnameValue !== '' ? $clientSurnameValue : implode(' ', array_slice($recipientNameParts, 1)));
        $recipientEmail = old('email', auth('web')->user()?->email ?? '');
        $recipientSummary = trim(implode(' · ', array_filter([
            trim($recipientFirstName . ' ' . $recipientLastName),
            $authPhonePretty,
            $recipientEmail,
        ])));
    @endphp
    <section class="mx-auto flex w-full max-w-[1440px] items-start gap-[212px] bg-[#FDFBF8] px-16 pb-[110px] pt-14 max-lg:flex-col max-lg:gap-12 max-sm:px-5 max-sm:pb-0 max-sm:pt-7">
        <main class="w-full max-w-[700px] flex-1" data-phone-auth>
            <nav class="flex h-[25px] items-center gap-4 text-[12.5px] leading-[19px] tracking-[0.125px] {{ $isDeliveryStep ? 'mb-3.5' : '' }}" aria-label="Кроки оформлення">
                <span class="flex items-center gap-[11px] {{ ($isAuthenticated ?? false) ? 'text-[#7A4751]' : 'text-[#5B2730]' }}"><b class="grid size-[25px] place-items-center rounded-full border text-[11px] font-medium {{ ($isAuthenticated ?? false) ? 'border-[#7A4751] text-[#7A4751]' : 'border-[#5B2730] bg-[#5B2730] text-[#FFF8F4]' }}">@if ($isAuthenticated ?? false)<svg class="size-[11px]" viewBox="0 0 11 11" fill="none" aria-hidden="true"><path d="M2.1 5.65L4.35 7.8L8.9 3.2" stroke="currentColor" stroke-width="0.9625" stroke-linecap="round" stroke-linejoin="round"/></svg>@else 1 @endif</b>Телефон</span>
                <i class="h-px w-[46px] bg-[#E8DAD0]"></i>
                <span class="flex items-center gap-[11px] {{ $isDeliveryStep ? 'text-[#7A4751]' : (($isAuthenticated ?? false) ? 'text-[#5B2730]' : 'text-[#A98088]') }}"><b class="grid size-[25px] place-items-center rounded-full border text-[11px] font-medium {{ $isDeliveryStep ? 'border-[#7A4751] text-[#7A4751]' : (($isAuthenticated ?? false) ? 'border-[#5B2730] bg-[#5B2730] text-[#FFF8F4]' : 'border-[#E8DAD0] text-[#A98088]') }}">@if ($isDeliveryStep)<svg class="size-[11px]" viewBox="0 0 11 11" fill="none" aria-hidden="true"><path d="M2.1 5.65L4.35 7.8L8.9 3.2" stroke="currentColor" stroke-width="0.9625" stroke-linecap="round" stroke-linejoin="round"/></svg>@else 2 @endif</b>Дані отримувача</span>
                <i class="h-px w-[46px] bg-[#E8DAD0]"></i>
                <span class="flex items-center gap-[11px] {{ $isDeliveryStep ? 'text-[#5B2730]' : 'text-[#A98088]' }}"><b class="grid size-[25px] place-items-center rounded-full border text-[11px] {{ $isDeliveryStep ? 'border-[#5B2730] bg-[#5B2730] font-medium text-[#FFF8F4]' : 'border-[#E8DAD0] font-normal' }}">3</b>Доставка та оплата</span>
            </nav>

            <div class="{{ ($isAuthenticated ?? false) ? 'hidden' : '' }}" data-phone-step>
            <div class="pt-7">
                <h1 class="m-0 font-cormorant text-[52px] font-semibold leading-[53px] tracking-[-0.78px] text-[#5B2730] max-sm:text-[38px] max-sm:leading-10">Введіть номер телефону</h1>
                <p class="m-0 mt-3.5 text-[14px] leading-[22px] text-[#7A4751]">Надішлемо код у СМС.</p>
            </div>

            <form class="mt-6 flex flex-col gap-[42px]" data-phone-auth-form action="{{ route('auth.phone.send') }}" method="POST">
                @csrf
                <input type="hidden" name="redirect_to_checkout" value="1">
                <div class="w-full max-w-[336px]">
                    <label class="mb-2 block text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]" for="auth-phone">Телефон <span class="text-[#C9A9B0]">*</span></label>
                    <input class="h-[44px] w-full border-0 border-b border-[#E8DAD0] bg-transparent px-0 text-[15.5px] leading-[23px] text-[#5B2730] outline-none placeholder:text-[#A98088] focus:border-[#5B2730]" id="auth-phone" name="phone" type="tel" inputmode="numeric" autocomplete="tel" value="+380 " maxlength="17" required data-phone-input>
                </div>

                <div class="hidden max-w-[336px]" data-code-wrap>
                    <label class="mb-2 block text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]" for="auth-code">Код із СМС <span class="text-[#C9A9B0]">*</span></label>
                    <input class="h-[44px] w-full border-0 border-b border-[#E8DAD0] bg-transparent px-0 text-[20px] tracking-[7px] text-[#5B2730] outline-none focus:border-[#5B2730]" id="auth-code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="4" pattern="\d{4}" data-code-input>
                </div>

                <div class="flex items-center gap-4 max-sm:flex-col max-sm:items-stretch">
                    <button class="flex h-[52px] min-w-[215px] items-center justify-center gap-3 bg-[#5B2730] px-10 text-[11.5px] font-medium uppercase leading-[17px] tracking-[1.84px] text-[#FFF8F4] disabled:opacity-50" type="submit" data-phone-submit>Отримати код</button>
                    <button class="hidden h-[52px] border border-[#E8DAD0] px-5 text-[11px] uppercase tracking-[1.3px] text-[#7A4751]" type="button" data-phone-resend>Надіслати ще раз</button>
                </div>
                <p class="m-0 min-h-5 text-[13px] leading-5 text-[#7A4751]" data-phone-message aria-live="polite"></p>
            </form>
            </div>

            <div class="hidden" data-code-step>
                <div class="pt-1">
                    <h1 class="m-0 font-cormorant text-[52px] font-semibold leading-[53px] tracking-[-0.78px] text-[#5B2730] max-sm:text-[38px] max-sm:leading-10">Код із СМС</h1>
                    <p class="m-0 mt-3.5 text-[14px] leading-[22px] text-[#7A4751]">Надіслали чотири цифри на <span data-code-phone></span></p>
                </div>
                <div class="mt-6 flex flex-col gap-2">
                    <label class="block text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]">Код підтвердження</label>
                    <div class="flex gap-2.5 max-sm:gap-2.5" data-code-digits>
                        @for ($index = 0; $index < 4; $index++)
                            <input class="grid h-[58px] w-[50px] place-items-center border border-[#E8DAD0] bg-transparent text-center text-[20px] leading-[30px] text-[#5B2730] outline-none focus:border-[#5B2730] max-sm:h-[52px] max-sm:w-[44px] max-sm:text-[18px] max-sm:leading-[27px]" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="1" data-code-digit>
                        @endfor
                    </div>
                    <div class="order-4 mt-8 flex w-full max-w-[560px] items-center gap-7 rounded-[20px] border border-[#E8CEC7] bg-[#FBF1EC] px-8 py-7 max-sm:items-start max-sm:gap-4 max-sm:rounded-[16px] max-sm:px-5 max-sm:py-5">
                        <div class="flex h-[92px] w-[92px] shrink-0 items-center justify-center rounded-full border border-[#E8D3CE] bg-[#FFFDFC] max-sm:h-16 max-sm:w-16">
                            <img class="h-[46px] w-[46px] text-[#642A35] max-sm:h-8 max-sm:w-8" src="{{ asset('vendor/frontend-sevia/images/phone-help.svg') }}" alt="">
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start gap-2">
                                <h3 class="font-cormorant text-[25px] leading-[1.15] text-[#642A35] max-sm:text-[20px]">Не прийшов код?</h3>
                                <span class="mt-[-4px] text-[26px] leading-none text-[#C99C96]" aria-hidden="true">*</span>
                            </div>
                            <p class="mt-3 text-[16px] leading-[1.4] text-[#642A35] max-sm:mt-2 max-sm:text-[14px]">Зателефонуйте нам на</p>
                            <a class="mt-1 inline-block font-cormorant text-[31px] leading-none text-[#642A35] transition-opacity hover:opacity-70 max-sm:text-[24px]" href="tel:+380978984333">+38 (097) 898 43 33</a>
                            <p class="mt-4 max-w-[340px] text-[16px] leading-[1.5] text-[#642A35] max-sm:mt-3 max-sm:text-[14px] max-sm:leading-[1.45]">і ми оформимо ваше замовлення<br class="max-sm:hidden"> в телефонному режимі.</p>
                        </div>
                    </div>
                    <p class="m-0 min-h-[18px] pt-1 text-[13.5px] leading-[17px] text-[#A98088]" data-code-timer>Надіслати повторно можна через 1:00</p>
                    <div class="hidden w-full max-w-[359px] items-center gap-5 text-[13px] leading-5 text-[#7A4751]" data-code-actions>
                        <button class="p-0 text-left underline decoration-[#A98088] underline-offset-4" type="button" data-phone-back>Змінити номер</button>
                        <button class="hidden p-0 text-left underline decoration-[#A98088] underline-offset-4" type="button" data-code-resend>Надіслати код повторно</button>
                    </div>
                    <p class="m-0 min-h-5 text-[13px] leading-5 text-[#7A4751]" data-code-message aria-live="polite"></p>
                    <div class="hidden min-h-[52px] w-full max-w-[491px] items-center gap-3 border border-[rgba(184,66,73,0.35)] bg-[#FCF4F3] px-[18px] py-[15px] text-[13.5px] font-medium leading-5 text-[#B84249]" data-code-error role="alert">
                        <span class="grid size-[15px] shrink-0 place-items-center rounded-full border border-[#B84249] text-[11px] leading-none">!</span>
                        <span>Код не підійшов. Перевірте останнє СМС або надішліть код повторно.</span>
                    </div>
                </div>
                <div class="hidden mt-1 flex w-full max-w-[700px] items-center justify-between gap-5 text-[13.5px] leading-5 text-[#7A4751] max-sm:flex-wrap">
                    <span>Не отримали СМС?</span>
                    <button class="p-0 text-left text-[13px] leading-5 text-[#7A4751] underline decoration-[#A98088] underline-offset-4" type="button" data-phone-back>Змінити номер</button>
                </div>
                <button class="hidden h-[52px] min-w-[206px] items-center justify-center border border-[#5B2730] bg-[#5B2730] px-10 text-[11.5px] font-medium uppercase leading-[17px] tracking-[1.84px] text-[#FFF8F4]" type="button" data-code-resend>Надіслати код повторно</button>
                <div class="pt-[35px]">
                    <button class="flex h-[52px] min-w-[206px] items-center justify-center gap-3 border border-[#E8DAD0] px-10 text-[11.5px] font-medium uppercase leading-[17px] tracking-[1.84px] text-[#C9A9B0] disabled:cursor-not-allowed" type="button" data-code-submit disabled>Підтвердити</button>
                    <button class="mt-3 hidden text-[11px] uppercase tracking-[1.3px] text-[#7A4751]" type="button" data-code-resend>Надіслати ще раз</button>
                </div>
            </div>

            <div class="{{ (($isAuthenticated ?? false) && ! $isDeliveryStep) ? '' : 'hidden' }}" data-recipient-step>
                <div class="pt-1">
                    @if (! empty($authenticatedFirstNameVocative))
                        <h1 class="m-0 font-cormorant text-[52px] font-semibold leading-[53px] tracking-[-0.78px] text-[#5B2730] max-sm:text-[38px] max-sm:leading-10">З поверненням, {{ $authenticatedFirstNameVocative }},</h1>
                    @else
                        <h1 class="m-0 font-cormorant text-[52px] font-semibold leading-[53px] tracking-[-0.78px] text-[#5B2730] max-sm:text-[38px] max-sm:leading-10">Контактна інформація</h1>
                        <div class="mt-3.5 flex w-full max-w-[615.13px] flex-col items-start">
                            <p class="m-0 text-[14px] font-normal leading-[22px] text-[#7A4751]">Номер підтверджено. Залишилось імʼя та пошта, на неї надішлемо накладну.</p>
                        </div>
                    @endif
                </div>
                <form class="mt-6 grid max-w-[700px] grid-cols-2 gap-x-7 gap-y-6 max-sm:grid-cols-1" id="recipient-form" data-recipient-form action="{{ route('checkout.recipient') }}" method="POST" novalidate>
                    @csrf
                    <label class="flex flex-col gap-2 text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]">Ім'я *
                        <input class="h-[44px] border-0 border-b border-[#E8DAD0] bg-transparent px-0 text-[15.5px] font-normal normal-case tracking-normal text-[#5B2730] outline-none focus:border-[#5B2730]" name="first_name" autocomplete="given-name" value="{{ $recipientFirstName }}" data-recipient-field data-recipient-required aria-describedby="recipient-first-name-error">
                        <span class="hidden text-[11.5px] font-normal normal-case leading-[17px] tracking-normal text-[#B84249]" id="recipient-first-name-error" data-recipient-error-for="first_name"></span>
                    </label>
                    <label class="flex flex-col gap-2 text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]">Прізвище *
                        <input class="h-[44px] border-0 border-b border-[#E8DAD0] bg-transparent px-0 text-[15.5px] font-normal normal-case tracking-normal text-[#5B2730] outline-none focus:border-[#5B2730]" name="last_name" autocomplete="family-name" value="{{ $recipientLastName }}" data-recipient-field data-recipient-required aria-describedby="recipient-last-name-error">
                        <span class="hidden text-[11.5px] font-normal normal-case leading-[17px] tracking-normal text-[#B84249]" id="recipient-last-name-error" data-recipient-error-for="last_name"></span>
                    </label>
                    <label class="flex flex-col gap-2 text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]">Електронна пошта *
                        <input class="h-[44px] border-0 border-b border-[#E8DAD0] bg-transparent px-0 text-[15.5px] font-normal normal-case tracking-normal text-[#5B2730] outline-none focus:border-[#5B2730]" name="email" type="email" autocomplete="email" value="{{ $recipientEmail }}" data-recipient-field data-recipient-required aria-describedby="recipient-email-error">
                        <span class="hidden text-[11.5px] font-normal normal-case leading-[17px] tracking-normal text-[#B84249]" id="recipient-email-error" data-recipient-error-for="email"></span>
                        <span class="text-[11.5px] font-normal normal-case tracking-normal text-[#A98088]" data-recipient-help-for="email">Надішлемо номер накладної та статус посилки</span>
                    </label>
                    <label class="flex flex-col gap-2 text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#4D8566]">Телефон *
                        <input class="h-[44px] border-0 border-b border-[#E8DAD0] bg-transparent px-0 text-[15.5px] font-normal normal-case tracking-normal text-[#5B2730] outline-none" data-recipient-phone value="{{ $authPhonePretty }}" readonly>
                        <span class="text-[11.5px] font-medium normal-case tracking-normal text-[#4D8566]">Підтверджено</span>
                    </label>
                    <div class="col-span-2 flex items-center justify-between pt-2 max-sm:hidden">
                        <a class="text-[13px] leading-5 text-[#7A4751]" href="{{ route('cart.page') }}">Повернутися до кошика</a>
                        <button class="flex h-[52px] min-w-[139px] items-center justify-center gap-3 bg-[#5B2730] px-10 text-[11.5px] font-medium uppercase leading-[17px] tracking-[1.84px] text-[#FFF8F4]" type="submit">Далі</button>
                    </div>
                </form>
            </div>

            <div class="{{ (($isAuthenticated ?? false) && ! $isDeliveryStep) ? 'flex' : 'hidden' }} mt-8 -mx-5 flex-col items-center border-t border-[#E8DAD0] bg-white pb-[18px] sm:hidden">
                <button class="flex h-16 w-full items-center justify-between px-5 py-[13px]" type="button" aria-label="Показати замовлення">
                    <span class="flex flex-col items-start gap-[3px]">
                        <span class="text-[12px] font-normal uppercase leading-[18px] tracking-[1.68px] text-[#7A4751]">Замовлення</span>
                        <span class="text-[11px] font-normal leading-4 tracking-[0.22px] text-[#A98088]">{{ $authQty }} {{ $authQtyLabel }}</span>
                    </span>
                    <span class="flex items-center gap-[11px]">
                        <span class="min-w-[68px] font-cormorant text-[23px] font-semibold leading-[35px] text-[#5B2730]">{{ $authMoney($authCartTotal) }}</span>
                        <svg class="size-[13px] rotate-180 text-[#A98088]" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                            <path d="M3.25 5.2L6.5 8.45L9.75 5.2" stroke="currentColor" stroke-width="1.1375" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </button>
                <button class="flex h-[52px] min-h-[52px] w-[calc(100%-40px)] items-center justify-center gap-3 bg-[#5B2730] px-10 text-[11.5px] font-medium uppercase leading-[17px] tracking-[1.84px] text-[#FFF8F4]" type="submit" form="recipient-form">
                    <span>Далі доставка</span>
                    <svg class="size-[15px]" viewBox="0 0 15 15" fill="none" aria-hidden="true">
                        <path d="M4.25 7.5H10.75M8.2 4.95L10.75 7.5L8.2 10.05" stroke="currentColor" stroke-width="1.3125" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <div class="{{ $isDeliveryStep ? 'flex' : 'hidden' }} flex-col gap-[26px]" data-delivery-step data-cart-total="{{ $authCartTotal }}" data-free-shipping-from="{{ $authFreeShippingFrom }}" data-delivery-prices-url="{{ route('checkout.nova-post.delivery-prices') }}">
                <div class="flex w-full items-end justify-between border-b border-[#E8DAD0] pb-[18px]">
                    <div class="flex flex-col gap-2">
                        <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.89px] text-[#A98088]">Отримувач</span>
                        <span class="text-[15px] font-normal leading-[22px] text-[#5B2730]">{{ $recipientSummary }}</span>
                    </div>
                    <button class="pb-px text-[13px] leading-5 text-[#7A4751]" type="button" data-edit-recipient>Змінити</button>
                </div>

                <form class="flex w-full flex-col gap-[26px]" data-delivery-form data-delivery-save-url="{{ route('checkout.delivery') }}" data-checkout-submit-url="{{ route('checkout.submit') }}">
                    <section class="flex flex-col gap-[22px] pt-3.5">
                        <h2 class="m-0 font-cormorant text-[38px] font-semibold leading-[41px] tracking-[-0.38px] text-[#5B2730]">Доставка</h2>
                        <label class="relative flex flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]" data-delivery-method-wrap>
                            <span>Спосіб доставки <span class="text-[#C9A9B0]">*</span></span>
                            <button class="flex h-[65px] w-full items-center justify-between border-b border-[#E8DAD0] py-[9px] text-left normal-case tracking-normal" type="button" data-delivery-method-toggle aria-expanded="false">
                                <span class="flex items-center gap-4">
                                    <span class="grid size-[18px] place-items-center text-[#B84249]" data-delivery-selected-icon>{{ $authDeliveryMethodIcon }}</span>
                                    <span class="flex flex-col">
                                        <span class="text-[15.5px] font-normal leading-[23px] text-[#5B2730]" data-delivery-selected-title>{{ $authDeliveryMethodTitle }}</span>
                                        <span class="text-[12px] font-normal leading-[18px] text-[#A98088]" data-delivery-selected-meta>{{ $authDeliveryMethodMeta }}</span>
                                    </span>
                                </span>
                                <span class="flex items-center gap-3 text-[13px] font-normal leading-5 text-[#7A4751]"><span data-delivery-selected-price>{{ $authDeliveryMethodPriceLabel }}</span> <svg class="size-[13px]" viewBox="0 0 13 13" fill="none"><path d="M3.25 5.2L6.5 8.45L9.75 5.2" stroke="currentColor" stroke-width="1.1375" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                            </button>
                            <input type="hidden" name="delivery_method" value="{{ $authDeliveryMethod }}" data-delivery-method-input>
                            <input type="hidden" name="shipping_price" value="{{ $authDeliveryPrice }}" data-delivery-price-input>
                            <div class="absolute left-0 right-0 top-[93px] z-20 hidden flex-col border border-[#E8DAD0] bg-white shadow-[0_8px_20px_rgba(91,39,48,0.07)]" data-delivery-method-menu>
                                <button class="{{ $authDeliveryMethod === 'nova_branch' ? 'relative ' : '' }}flex min-h-[65.62px] w-full items-center justify-between px-4 py-3 pl-[15px] text-left normal-case tracking-normal" type="button" data-delivery-option data-value="nova_branch" data-title="Нова Пошта · відділення" data-meta="1–2 дні · отримання за телефоном" data-price="{{ $authNovaWarehousePrice }}" data-price-label="{{ $authNovaWarehouseLabel }}" data-icon="✣" @if($authDeliveryMethod === 'nova_branch') data-active="true" @endif>
                                    <span class="flex items-center gap-4"><span class="grid size-[18px] place-items-center text-[#B84249]">✣</span><span class="flex flex-col gap-[2.62px]"><span class="text-[14px] leading-[21px] text-[#5B2730]">Нова Пошта · відділення</span><span class="text-[11.5px] leading-[17px] text-[#A98088]">1–2 дні · отримання за телефоном</span></span></span>
                                    <span class="text-[12.5px] leading-[19px] text-[#A98088]" data-delivery-option-price>{{ $authNovaWarehouseLabel }}</span>
                                    @if($authDeliveryMethod === 'nova_branch')<span class="absolute bottom-0 left-0 top-0 w-0.5 bg-[#5B2730]" data-delivery-active-bar></span>@endif
                                </button>
                                <button class="{{ $authDeliveryMethod === 'nova_postomat' ? 'relative ' : '' }}flex min-h-[66.62px] w-full items-center justify-between border-t border-[#F0E6DE] px-4 py-3 pl-[15px] text-left normal-case tracking-normal" type="button" data-delivery-option data-value="nova_postomat" data-title="Нова Пошта · поштомат" data-meta="1–2 дні · код у СМС, без черг" data-price="{{ $authNovaWarehousePrice }}" data-price-label="{{ $authNovaWarehouseLabel }}" data-icon="✣" @if($authDeliveryMethod === 'nova_postomat') data-active="true" @endif>
                                    <span class="flex items-center gap-4"><span class="grid size-[18px] place-items-center text-[#B84249]">✣</span><span class="flex flex-col gap-[2.62px]"><span class="text-[14px] leading-[21px] text-[#7A4751]">Нова Пошта · поштомат</span><span class="text-[11.5px] leading-[17px] text-[#A98088]">1–2 дні · код у СМС, без черг</span></span></span>
                                    <span class="text-[12.5px] leading-[19px] text-[#A98088]" data-delivery-option-price>{{ $authNovaWarehouseLabel }}</span>
                                    @if($authDeliveryMethod === 'nova_postomat')<span class="absolute bottom-0 left-0 top-0 w-0.5 bg-[#5B2730]" data-delivery-active-bar></span>@endif
                                </button>
                                <button class="{{ $authDeliveryMethod === 'nova_courier' ? 'relative ' : '' }}flex min-h-[66.62px] w-full items-center justify-between border-t border-[#F0E6DE] px-4 py-3 pl-[15px] text-left normal-case tracking-normal" type="button" data-delivery-option data-value="nova_courier" data-title="Курʼєр Нової Пошти" data-meta="1–2 дні · привезе на адресу" data-price="{{ $authNovaCourierPrice }}" data-price-label="{{ $authNovaCourierLabel }}" data-icon="✣" @if($authDeliveryMethod === 'nova_courier') data-active="true" @endif>
                                    <span class="flex items-center gap-4"><span class="grid size-[18px] place-items-center text-[#B84249]">✣</span><span class="flex flex-col gap-[2.62px]"><span class="text-[14px] leading-[21px] text-[#7A4751]">Курʼєр Нової Пошти</span><span class="text-[11.5px] leading-[17px] text-[#A98088]">1–2 дні · привезе на адресу</span></span></span>
                                    <span class="text-[12.5px] leading-[19px] text-[#A98088]" data-delivery-option-price>{{ $authNovaCourierLabel }}</span>
                                    @if($authDeliveryMethod === 'nova_courier')<span class="absolute bottom-0 left-0 top-0 w-0.5 bg-[#5B2730]" data-delivery-active-bar></span>@endif
                                </button>
                                <button class="{{ $authDeliveryMethod === 'sevia_pickup' ? 'relative ' : '' }}flex min-h-[66.62px] w-full items-center justify-between border-t border-[#F0E6DE] px-4 py-3 pl-[15px] text-left normal-case tracking-normal" type="button" data-delivery-option data-value="sevia_pickup" data-title="Шоу-рум Sevia · самовивіз" data-meta="сьогодні · вул. Хрещатик 22, Київ, з 11:00" data-price="0" data-price-label="безкоштовно" data-icon="S" @if($authDeliveryMethod === 'sevia_pickup') data-active="true" @endif>
                                    <span class="flex items-center gap-4"><span class="grid size-[18px] place-items-center text-[10px] font-semibold text-[#5B2730]">S</span><span class="flex flex-col gap-[2.62px]"><span class="text-[14px] leading-[21px] text-[#7A4751]">Шоу-рум Sevia · самовивіз</span><span class="text-[11.5px] leading-[17px] text-[#A98088]">сьогодні · вул. Хрещатик 22, Київ, з 11:00</span></span></span>
                                    <span class="text-[12.5px] leading-[19px] text-[#A98088]" data-delivery-option-price>безкоштовно</span>
                                    @if($authDeliveryMethod === 'sevia_pickup')<span class="absolute bottom-0 left-0 top-0 w-0.5 bg-[#5B2730]" data-delivery-active-bar></span>@endif
                                </button>
                            </div>
                        </label>
                        <div class="flex flex-col gap-7" data-nova-delivery-fields>
                            <div class="relative flex flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]" data-nova-city-wrap data-cities-url="{{ route('checkout.nova-post.cities') }}">
                                <span>Місто <span class="text-[#C9A9B0]">*</span></span>
                                <button class="flex h-[54px] w-full items-center justify-between border-b border-[#E8DAD0] py-1 text-left normal-case tracking-normal" type="button" data-nova-city-open data-nova-city-selected>
                                    <span class="flex min-w-0 flex-col">
                                        <span class="truncate text-[15.5px] font-normal leading-[23px] text-[#5B2730]" data-nova-city-selected-name>{{ $authCityDisplayName ?: $authCityName }}</span>
                                        <span class="truncate text-[12px] font-normal leading-[18px] text-[#A98088]" data-nova-city-selected-details>{{ $authCityDetails }}</span>
                                    </span>
                                    <span class="ml-3 shrink-0 text-[13px] leading-5 text-[#7A4751]">Змінити</span>
                                </button>
                                <input type="hidden" name="city" value="{{ $authCityValue }}" data-nova-city-input>
                                <input type="hidden" name="city_ref" value="{{ $authCityRef }}" data-nova-city-ref>
                                <input type="hidden" name="city_name" value="{{ $authCityName }}" data-nova-city-name>
                                <input type="hidden" name="city_display_name" value="{{ $authCityDisplayName }}" data-nova-city-display-name>
                                <input type="hidden" name="city_details" value="{{ $authCityDetails }}" data-nova-city-details>
                                <div class="fixed inset-0 z-50 hidden items-start justify-center bg-[#5B2730]/35 px-4 py-8 normal-case tracking-normal backdrop-blur-[1px] max-sm:items-stretch max-sm:p-0" data-nova-city-modal>
                                    <div class="flex max-h-[calc(100vh-64px)] w-full max-w-[880px] flex-col overflow-hidden border border-[#E8DAD0] bg-[#FFF8F4] shadow-[0_20px_60px_rgba(91,39,48,0.18)] max-sm:max-h-none max-sm:min-h-screen">
                                        <div class="flex items-center justify-between border-b border-[#E8DAD0] bg-white px-8 py-5 max-sm:px-5">
                                            <h3 class="m-0 font-cormorant text-[40px] font-semibold leading-[44px] tracking-[-0.4px] text-[#5B2730] max-sm:text-[32px] max-sm:leading-9">Виберіть своє місто</h3>
                                            <button class="text-[34px] font-light leading-none text-[#A98088] transition hover:text-[#5B2730]" type="button" data-nova-city-close aria-label="Закрити">×</button>
                                        </div>
                                        <div class="overflow-y-auto px-6 py-6 max-sm:px-5">
                                            <p class="m-0 text-[14px] font-medium leading-[22px] text-[#A98088]">Доставляємо замовлення по всій Україні!</p>
                                            <div class="grid grid-cols-3 gap-x-20 gap-y-7 py-8 text-[21px] leading-8 text-[#7A4751] max-sm:grid-cols-2 max-sm:gap-x-10 max-sm:text-[18px]">
                                                @foreach (['Київ', 'Харків', 'Одеса', 'Дніпро', 'Запоріжжя', 'Львів'] as $popularCity)
                                                    <button class="text-left text-[#7A4751] transition hover:text-[#5B2730]" type="button" data-nova-popular-city="{{ $popularCity }}">{{ $popularCity }}</button>
                                                @endforeach
                                            </div>
                                            <label class="flex flex-col gap-1.5 text-[12.5px] font-medium uppercase leading-5 tracking-[1.4px] text-[#A98088]">Вкажіть населений пункт України
                                                <input class="h-[56px] border border-[#E8DAD0] bg-white px-4 text-[18px] font-normal normal-case leading-7 tracking-normal text-[#5B2730] outline-none transition placeholder:text-[#C9A9B0] focus:border-[#5B2730]" type="text" autocomplete="off" data-nova-city-search>
                                            </label>
                                            <div class="mt-2 hidden max-h-[230px] flex-col overflow-y-auto border border-[#E8DAD0] bg-white" data-nova-city-menu></div>
                                            <p class="mt-8 text-[13.5px] leading-5 text-[#A98088]">Наприклад, <button class="text-[#7A4751] underline-offset-4 transition hover:text-[#5B2730] hover:underline" type="button" data-nova-popular-city="Котюжини">Котюжини</button></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="{{ $authDeliveryMethod === 'nova_courier' ? 'hidden' : 'flex' }} relative flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]" data-nova-warehouse-wrap data-warehouses-url="{{ route('checkout.nova-post.warehouses') }}">
                                <span><span data-nova-warehouse-label>{{ $authDeliveryMethod === 'nova_postomat' ? 'Поштомат' : 'Відділення' }}</span> <span class="text-[#C9A9B0]">*</span></span>
                                <button class="flex h-[54px] items-center justify-between border-b border-[#E8DAD0] text-left text-[15.5px] font-normal normal-case leading-[23px] tracking-normal text-[#C9A9B0]" type="button" data-nova-warehouse-toggle aria-expanded="false">
                                    <span class="truncate {{ $authWarehouseName !== '' ? 'text-[#5B2730]' : 'text-[#C9A9B0]' }}" data-nova-warehouse-selected>{{ $authWarehouseName !== '' ? $authWarehouseName : ($authDeliveryMethod === 'nova_postomat' ? 'Оберіть поштомат' : 'Оберіть відділення') }}</span>
                                    <svg class="size-[13px] shrink-0 text-[#7A4751]" viewBox="0 0 13 13" fill="none"><path d="M3.25 5.2L6.5 8.45L9.75 5.2" stroke="currentColor" stroke-width="1.1375" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                                <input type="hidden" name="warehouse_ref" value="{{ $authWarehouseRef }}" data-nova-warehouse-ref>
                                <input type="hidden" name="warehouse_name" value="{{ $authWarehouseName }}" data-nova-warehouse-name>
                                <span class="hidden text-[11.5px] font-normal normal-case leading-[17px] tracking-normal text-[#B84249]" data-nova-warehouse-error>Оберіть відділення Нової Пошти.</span>
                                <div class="absolute left-0 right-0 top-[78px] z-30 hidden flex-col border border-[#E8DAD0] bg-white normal-case tracking-normal shadow-[0_8px_20px_rgba(91,39,48,0.07)]" data-nova-warehouse-panel>
                                    <input class="h-[56px] border-0 border-b border-[#E8DAD0] px-4 text-[15.5px] font-normal leading-[23px] text-[#5B2730] outline-none placeholder:text-[#A98088] focus:border-[#5B2730]" type="text" placeholder="Введіть адресу або номер відділення" autocomplete="off" data-nova-warehouse-search>
                                    <div class="max-h-[292px] overflow-y-auto" data-nova-warehouse-menu></div>
                                </div>
                            </div>
                            <div class="{{ $authDeliveryMethod === 'nova_courier' ? 'flex' : 'hidden' }} flex-col gap-5 border border-[#E8DAD0] bg-[#FFF8F4] px-5 py-5 normal-case tracking-normal" data-nova-courier-panel data-streets-url="{{ route('checkout.nova-post.streets') }}">
                                <div class="grid grid-cols-12 gap-x-5 gap-y-5">
                                    <label class="relative col-span-7 flex flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088] max-sm:col-span-12">Вулиця <span class="sr-only">*</span>
                                        <input class="h-[54px] border border-[#E8DAD0] bg-white px-4 text-[15.5px] font-normal normal-case leading-[23px] tracking-normal text-[#5B2730] outline-none placeholder:text-[#C9A9B0] focus:border-[#5B2730]" type="text" name="street" value="{{ $authStreet }}" autocomplete="street-address" data-nova-street-input>
                                        <input type="hidden" name="street_ref" value="{{ $authStreetRef }}" data-nova-street-ref>
                                        <span class="hidden text-[11.5px] font-normal normal-case leading-[17px] tracking-normal text-[#B84249]" data-nova-courier-error-for="street"></span>
                                        <div class="absolute left-0 right-0 top-[78px] z-30 hidden max-h-[230px] flex-col overflow-y-auto border border-[#E8DAD0] bg-white shadow-[0_8px_20px_rgba(91,39,48,0.07)]" data-nova-street-menu></div>
                                    </label>
                                    <label class="col-span-3 flex flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088] max-sm:col-span-6">Будинок <span class="sr-only">*</span>
                                        <input class="h-[54px] border border-[#E8DAD0] bg-white px-4 text-[15.5px] font-normal normal-case leading-[23px] tracking-normal text-[#5B2730] outline-none placeholder:text-[#C9A9B0] focus:border-[#5B2730]" type="text" name="house" value="{{ $authHouse }}" autocomplete="address-line2" data-nova-house-input>
                                        <span class="hidden text-[11.5px] font-normal normal-case leading-[17px] tracking-normal text-[#B84249]" data-nova-courier-error-for="house"></span>
                                    </label>
                                    <label class="col-span-2 flex flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088] max-sm:col-span-6">Квартира
                                        <input class="h-[54px] border border-[#E8DAD0] bg-white px-4 text-[15.5px] font-normal normal-case leading-[23px] tracking-normal text-[#5B2730] outline-none placeholder:text-[#C9A9B0] focus:border-[#5B2730]" type="text" name="apartment" value="{{ $authApartment }}" autocomplete="off">
                                    </label>
                                    <label class="col-span-12 flex items-center gap-[11px] text-[13.5px] font-normal leading-5 text-[#7A4751]">
                                        <input class="size-[17px] border border-[#5B2730] accent-[#5B2730]" type="checkbox" name="bring_to_floor" value="1" data-bring-to-floor @checked($authBringToFloor)>
                                        Підняти на поверх
                                    </label>
                                    <label class="col-span-6 flex flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088] max-sm:col-span-12">Поверх
                                        <input class="h-[54px] border border-[#E8DAD0] bg-white px-4 text-[15.5px] font-normal normal-case leading-[23px] tracking-normal text-[#5B2730] outline-none placeholder:text-[#C9A9B0] focus:border-[#5B2730]" type="text" name="floor" value="{{ $authFloor }}" autocomplete="off">
                                    </label>
                                    <label class="col-span-6 flex flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088] max-sm:col-span-12">Ліфт
                                        <select class="h-[54px] border border-[#E8DAD0] bg-white px-4 text-[15.5px] font-normal normal-case leading-[23px] tracking-normal text-[#5B2730] outline-none focus:border-[#5B2730]" name="elevator">
                                            <option value="" @selected($authElevator === '')>Наявність вантажного ліфта</option>
                                            <option value="yes" @selected($authElevator === 'yes')>Є вантажний ліфт</option>
                                            <option value="no" @selected($authElevator === 'no')>Немає вантажного ліфта</option>
                                        </select>
                                    </label>
                                    <label class="col-span-6 flex flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088] max-sm:col-span-12">Підʼїзд
                                        <input class="h-[54px] border border-[#E8DAD0] bg-white px-4 text-[15.5px] font-normal normal-case leading-[23px] tracking-normal text-[#5B2730] outline-none placeholder:text-[#C9A9B0] focus:border-[#5B2730]" type="text" name="entrance" value="{{ $authEntrance }}" autocomplete="off">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <label class="flex items-start gap-[11px] pt-1.5 text-[13.5px] font-normal leading-5 text-[#7A4751]" data-other-recipient-toggle-label>
                            <input class="mt-1 size-[17px] border border-[#5B2730] accent-[#5B2730]" type="checkbox" name="other_recipient" value="1" data-other-recipient-toggle @checked($authOtherRecipient)>
                            Замовлення отримає інша людина
                        </label>
                        <div class="{{ $authOtherRecipient ? 'flex' : 'hidden' }} flex-col gap-5 border border-[#E8DAD0] bg-[#FFF8F4] px-5 py-5" data-other-recipient-panel>
                            <div class="flex flex-col gap-2">
                                <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.89px] text-[#A98088]">Отримувач</span>
                                <button class="flex min-h-[54px] w-full items-center justify-between border border-[#E8DAD0] bg-white px-4 text-left" type="button">
                                    <span class="flex min-w-0 flex-col">
                                        <span class="truncate text-[15.5px] font-normal leading-[23px] text-[#5B2730]" data-other-recipient-summary>{{ trim(implode(' ', array_filter([$checkoutDelivery['other_recipient_surname'] ?? '', $checkoutDelivery['other_recipient_name'] ?? '', $checkoutDelivery['other_recipient_patronymic'] ?? '']))) ?: 'Новий отримувач' }}</span>
                                        <span class="text-[12px] font-normal leading-[18px] text-[#A98088]">Дані для отримання у відділенні</span>
                                    </span>
                                    <svg class="size-[13px] shrink-0 text-[#7A4751]" viewBox="0 0 13 13" fill="none"><path d="M3.25 5.2L6.5 8.45L9.75 5.2" stroke="currentColor" stroke-width="1.1375" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-x-7 gap-y-5 max-sm:grid-cols-1">
                                <label class="flex flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]">Прізвище <span class="sr-only">*</span>
                                    <input class="h-[44px] border-b border-[#E8DAD0] bg-transparent py-[9px] text-[15.5px] font-normal normal-case leading-[23px] tracking-normal text-[#5B2730] outline-none placeholder:text-[#C9A9B0] focus:border-[#5B2730]" type="text" name="other_recipient_surname" value="{{ $checkoutDelivery['other_recipient_surname'] ?? '' }}" autocomplete="family-name" data-other-recipient-field data-other-recipient-label="Прізвище">
                                    <span class="hidden text-[11.5px] font-normal normal-case leading-[17px] tracking-normal text-[#B84249]" data-other-recipient-error-for="other_recipient_surname"></span>
                                </label>
                                <label class="flex flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]">Ім'я <span class="sr-only">*</span>
                                    <input class="h-[44px] border-b border-[#E8DAD0] bg-transparent py-[9px] text-[15.5px] font-normal normal-case leading-[23px] tracking-normal text-[#5B2730] outline-none placeholder:text-[#C9A9B0] focus:border-[#5B2730]" type="text" name="other_recipient_name" value="{{ $checkoutDelivery['other_recipient_name'] ?? '' }}" autocomplete="given-name" data-other-recipient-field data-other-recipient-label="Ім'я">
                                    <span class="hidden text-[11.5px] font-normal normal-case leading-[17px] tracking-normal text-[#B84249]" data-other-recipient-error-for="other_recipient_name"></span>
                                </label>
                                <label class="flex flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]">По батькові <span class="sr-only">*</span>
                                    <input class="h-[44px] border-b border-[#E8DAD0] bg-transparent py-[9px] text-[15.5px] font-normal normal-case leading-[23px] tracking-normal text-[#5B2730] outline-none placeholder:text-[#C9A9B0] focus:border-[#5B2730]" type="text" name="other_recipient_patronymic" value="{{ $checkoutDelivery['other_recipient_patronymic'] ?? '' }}" autocomplete="additional-name" data-other-recipient-field data-other-recipient-label="По батькові">
                                    <span class="hidden text-[11.5px] font-normal normal-case leading-[17px] tracking-normal text-[#B84249]" data-other-recipient-error-for="other_recipient_patronymic"></span>
                                </label>
                                <label class="flex flex-col gap-[7px] text-[10.5px] font-medium uppercase leading-4 tracking-[1.575px] text-[#A98088]">Мобільний телефон <span class="sr-only">*</span>
                                    <input class="h-[44px] border-b border-[#E8DAD0] bg-transparent py-[9px] text-[15.5px] font-normal normal-case leading-[23px] tracking-normal text-[#5B2730] outline-none placeholder:text-[#C9A9B0] focus:border-[#5B2730]" type="tel" name="other_recipient_phone" inputmode="numeric" autocomplete="tel" value="{{ $checkoutDelivery['other_recipient_phone'] ?? '+380 ' }}" maxlength="17" data-other-recipient-field data-other-recipient-label="Мобільний телефон" data-other-recipient-phone>
                                    <span class="hidden text-[11.5px] font-normal normal-case leading-[17px] tracking-normal text-[#B84249]" data-other-recipient-error-for="other_recipient_phone"></span>
                                </label>
                            </div>
                            <div class="border border-[#E8DAD0] bg-white px-4 py-3 text-[12.5px] font-normal leading-[19px] text-[#7A4751]">
                                Зверніть увагу: отримання замовлення за паспортом. Введіть прізвище, ім'я, по батькові як зазначено у документі та мобільний номер телефону отримувача замовлення.
                            </div>
                        </div>
                    </section>

                    <section class="flex flex-col gap-[22px] pt-[18px]">
                        <div class="flex items-center justify-between">
                            <h2 class="m-0 font-cormorant text-[38px] font-semibold leading-[41px] tracking-[-0.38px] text-[#5B2730]">Оплата</h2>
                            <span class="text-[12px] font-semibold uppercase tracking-[1px] text-[#7A4751]">LiqPay</span>
                        </div>
                        <div class="border-t border-[#E8DAD0]">
                            <label class="flex min-h-[81px] items-center gap-3.5 border border-[#5B2730] bg-[#FBF4F0] px-[19px] py-[18px]" data-payment-option>
                                <input class="size-[17px] accent-[#5B2730]" type="radio" name="payment" value="liqpay" @checked($authPayment === 'liqpay') data-payment-liqpay>
                                <span class="flex flex-1 flex-col gap-[3px]">
                                    <span class="text-[14.5px] font-medium leading-[22px] text-[#5B2730]">Онлайн оплата карткою</span>
                                    <span class="text-[12px] font-normal leading-[18px] text-[#A98088]">Переадресуємо на захищену сторінку LiqPay — ми не обробляємо дані вашої картки</span>
                                </span>
                                <span class="text-[12px] font-bold uppercase text-[#1A3F8B]">Visa</span>
                                <span class="size-5 rounded-full bg-[#E84D3D] shadow-[-10px_0_0_#F6A623]"></span>
                            </label>
                            <label class="hidden min-h-[81px] items-center gap-3.5 px-[19px] py-[18px]" data-payment-option data-payment-cash-label>
                                <input class="size-[17px] accent-[#5B2730]" type="radio" name="payment" value="cash" @checked($authPayment === 'cash')>
                                <span class="flex flex-col gap-[3px]">
                                    <span class="text-[14.5px] font-normal leading-[22px] text-[#5B2730]">Готівкою при отриманні</span>
                                    <span class="text-[12px] font-normal leading-[18px] text-[#A98088]">Оплата при отриманні замовлення</span>
                                </span>
                            </label>
                        </div>
                    </section>

                    <section class="flex flex-col gap-[26px] pt-[18px]">
                        <label class="flex flex-col gap-[26px]">
                            <span class="flex items-baseline justify-between">
                                <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.89px] text-[#A98088]">Коментар до замовлення</span>
                                <span class="text-[12.5px] font-normal leading-[19px] text-[#A98088]">не обовʼязково</span>
                            </span>
                            <textarea class="min-h-[104px] resize-none border border-[#E8DAD0] bg-white px-4 py-[13px] text-[14.5px] leading-[22px] text-[#5B2730] outline-none placeholder:text-[#C9A9B0]" name="comment" placeholder="Наприклад: покласти пробник вечірнього аромату або підписати листівку">{{ $checkoutDelivery['comment'] ?? '' }}</textarea>
                        </label>
                        <div class="grid grid-cols-2 gap-7 max-sm:grid-cols-1">
                            <label class="flex items-start gap-[11px] text-[13.5px] leading-5 text-[#5B2730]"><input class="mt-1 size-[17px] accent-[#5B2730]" type="checkbox" name="confirm_without_call" value="1" @checked($authConfirmWithoutCall)>Можна не телефонувати — підтверджую замовлення як є</label>
                            <label class="flex items-start gap-[11px] text-[13.5px] leading-5 text-[#7A4751]"><input class="mt-1 size-[17px] accent-[#5B2730]" type="checkbox" name="gift_no_receipt" value="1" @checked($authGiftNoReceipt)>Не кладіть чек у посилку — це подарунок</label>
                        </div>
                    </section>

                    <p class="m-0 border-t border-[#F0E6DE] pt-[22px] text-[11.5px] leading-[19px] text-[#A98088]">Оформлюючи замовлення, ви погоджуєтесь з Політикою конфіденційності, Публічною офертою та обробкою вказаних даних для доставки.</p>
                    <div class="flex items-center justify-between pt-4 max-sm:flex-col max-sm:items-stretch max-sm:gap-4">
                        <button class="text-left text-[13px] leading-5 text-[#7A4751]" type="button" data-edit-recipient>Змінити контактні дані</button>
                        <button class="flex h-[52px] min-h-[52px] w-[368px] items-center justify-center gap-3 whitespace-nowrap bg-[#5B2730] px-10 text-[11.5px] font-medium uppercase leading-[17px] tracking-[1.84px] text-[#FFF8F4] max-sm:w-full max-sm:px-5" type="button" data-delivery-confirm>Підтвердити замовлення · <span class="whitespace-nowrap" data-confirm-grand-total>{{ $authMoney($authGrandTotal) }}</span> →</button>
                    </div>
                </form>
            </div>
        </main>

        <div class="flex w-[400px] shrink-0 flex-col gap-4 max-lg:w-full max-lg:max-w-[400px] max-[1023px]:hidden">
        <figure class="{{ ($isAuthenticated ?? false) ? 'hidden' : 'flex' }} m-0 w-[400px] shrink-0 flex-col gap-[15px] max-lg:w-full max-lg:max-w-[400px]" data-auth-visual>
            <img class="h-[470px] w-[400px] rounded-[40px] object-cover max-lg:w-full max-sm:h-[360px]" src="{{ asset('vendor/frontend-sevia/images/fon_avtor.png') }}" alt="Sevia">
            <figcaption>
                <p class="m-0 text-[10.5px] font-medium uppercase leading-[14px] tracking-[1.89px] text-[#A98088]">Розпив від 3 мл</p>
                <p class="m-0 mt-2 font-cormorant text-[19px] leading-[26px] text-[#5B2730]">Спробуйте аромат, перш ніж брати повний флакон</p>
            </figcaption>
        </figure>

        <aside class="{{ ($isAuthenticated ?? false) ? 'flex' : 'hidden' }} w-[400px] shrink-0 flex-col border border-[#E8DAD0] bg-white max-lg:hidden" data-auth-order-summary>
            <div class="flex h-[79px] items-baseline justify-between border-b border-[#E8DAD0] px-[26px] pb-5 pt-[22px]">
                <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.68px] text-[#A98088]">Замовлення</span>
                <strong class="font-cormorant text-[24px] font-semibold leading-9 text-[#5B2730]" data-order-grand-total>{{ $authMoney($authGrandTotal) }}</strong>
            </div>
            <div class="{{ $authFreeShippingFrom > 0 ? 'block' : 'hidden' }} border-b border-[#E8DAD0] bg-[#FBF4F0] px-[26px] py-[18px]">
                <p class="m-0 text-[12.5px] leading-[18px] text-[#7A4751]">@if ($authFreeShippingLeft > 0) Ще {{ $authMoney($authFreeShippingLeft) }} і доставимо безкоштовно @else Доставка буде безкоштовною @endif</p>
                <div class="mt-3 h-0.5 w-full bg-[#EDDCD5]"><div class="h-0.5 bg-[#5B2730]" style="width: {{ $authFreeShippingProgress }}%"></div></div>
            </div>
            <div class="max-h-[420px] overflow-y-auto px-[26px]">
                @forelse ($checkoutItems ?? [] as $item)
                    @php
                        $meta = is_array($item['meta'] ?? null) ? $item['meta'] : [];
                        $labelParts = collect(preg_split('/\s*·\s*/u', (string) ($meta['cart_label'] ?? '')))->filter()->values();
                        $brand = (string) ($meta['brand'] ?? ($labelParts->get(0) ?? ''));
                        $name = (string) ($meta['name'] ?? ($labelParts->get(1) ?? ($item['name'] ?? 'Товар')));
                        $volume = (string) ($meta['volume'] ?? ($labelParts->get(2) ?? ($item['variant'] ?? '')));
                        $itemQty = (int) ($item['qty'] ?? 1);
                        $itemTotal = (float) ($item['subtotal'] ?? 0);
                    @endphp
                    <article class="flex min-h-[88px] items-start gap-3.5 border-b border-[#F0E6DE] py-3.5">
                        <div class="flex h-[58px] w-[46px] shrink-0 items-center justify-center bg-[#FDFBF8] p-1.5">
                            @if (! empty($item['image']))
                                <img class="max-h-[46px] max-w-[34px] object-contain" src="{{ $item['image'] }}" alt="{{ trim($brand . ' ' . $name) }}">
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="m-0 truncate text-[10px] uppercase leading-[15px] tracking-[1.4px] text-[#A98088]">{{ $brand }}</p>
                            <h2 class="m-0 truncate font-cormorant text-[17px] font-medium leading-5 text-[#5B2730]">{{ $name }}</h2>
                            <p class="m-0 truncate text-[11.5px] leading-[18px] text-[#A98088]">Розпив {{ $volume }} · {{ $itemQty }} шт.</p>
                        </div>
                        <div class="shrink-0 pt-3 text-right">
                            <p class="m-0 text-[14px] font-medium leading-[21px] text-[#5B2730]">{{ $authMoney($itemTotal) }}</p>
                        </div>
                    </article>
                @empty
                    <p class="py-6 text-[13px] leading-5 text-[#7A4751]">Кошик порожній.</p>
                @endforelse
            </div>
            <form class="border-t border-[#E8DAD0] px-[26px] py-8" action="#" method="GET">
                <label class="flex h-12 border border-[#E8DAD0]">
                    <input class="min-w-0 flex-1 px-4 text-[13px] leading-4 text-[#5B2730] outline-none placeholder:text-[#C9A9B0]" type="text" name="promo" placeholder="Промокод або сертифікат">
                    <button class="w-[120px] text-[10.5px] uppercase leading-4 tracking-[1.47px] text-[#7A4751]" type="submit">Застосувати</button>
                </label>
            </form>
            <div class="border-t border-[#E8DAD0] px-[26px] py-5">
                <dl class="m-0 grid gap-0 text-[13.5px] leading-5">
                    <div class="flex justify-between py-[5.5px]"><dt class="text-[#7A4751]">Аромати · {{ $authQty }} розпиви</dt><dd class="m-0 text-[#5B2730]">{{ $authMoney($authItemsSubtotal) }}</dd></div>
                    <div class="flex justify-between py-[5.5px]"><dt class="text-[#7A4751]">Знижка на аромати</dt><dd class="m-0 text-[#4D8566]">{{ $authDiscount > 0 ? '- ' . $authMoney($authDiscount) : $authMoney(0) }}</dd></div>
                    <div class="flex justify-between py-[5.5px]"><dt class="text-[#7A4751]">Флакони · {{ $authBottleSummaryLabel($authBottleBreakdown, $authBottleCount) }}</dt><dd class="m-0 text-[#5B2730]">{{ $authMoney($authBottleFee) }}</dd></div>
                    <div class="flex justify-between pb-[19px] pt-[5.5px]"><dt class="text-[#7A4751]">Доставка<span data-delivery-summary-title>{{ $isDeliveryStep ? ' · Нова Пошта' : '' }}</span></dt><dd class="m-0 text-[#5B2730]" data-delivery-summary-price>{{ $isDeliveryStep ? $authNovaWarehouseLabel : 'на кроці 2' }}</dd></div>
                </dl>
                <div class="flex items-baseline justify-between">
                    <span class="text-[10.5px] font-medium uppercase leading-4 tracking-[1.68px] text-[#7A4751]">До сплати</span>
                    <strong class="font-cormorant text-[30px] font-semibold leading-[45px] text-[#5B2730]" data-order-grand-total>{{ $authMoney($authGrandTotal) }}</strong>
                </div>
            </div>
            <div class="hidden flex items-center justify-between px-0.5 pt-3">
                <span class="text-[10px] uppercase leading-[15px] tracking-[1.4px] text-[#A98088]">Оплата захищена</span>
                <img class="h-5 w-[162px] shrink-0 object-contain" src="{{ asset('vendor/frontend-sevia/images/oplatu.png') }}" alt="LiqPay, Visa, Mastercard">
            </div>
            <ul class="hidden m-0 mt-4 grid list-none gap-[11px] p-0 text-[12px] leading-[17px] text-[#7A4751]">
                <li class="flex items-center gap-2.5"><span class="grid size-[13px] place-items-center rounded-full border border-[#A98088] text-[9px]">✓</span>100% оригінал · розпив із власного флакону бренду</li>
                <li class="flex items-center gap-2.5"><span class="grid size-[13px] place-items-center rounded-full border border-[#A98088] text-[9px]">✓</span>Відправка того ж дня при замовленні до 15:00</li>
            </ul>
        </aside>
        <div class="{{ ($isAuthenticated ?? false) ? 'flex' : 'hidden' }} w-[400px] shrink-0 flex-col max-lg:hidden" data-auth-order-meta>
            <div class="flex items-center justify-between px-0.5 pt-3">
                <span class="text-[10px] uppercase leading-[15px] tracking-[1.4px] text-[#A98088]">Оплата захищена</span>
                <img class="h-5 w-[162px] shrink-0 object-contain" src="{{ asset('vendor/frontend-sevia/images/oplatu.png') }}" alt="LiqPay, Visa, Mastercard">
            </div>
            <ul class="m-0 mt-4 grid list-none gap-[11px] p-0 text-[12px] leading-[17px] text-[#7A4751]">
                <li class="flex items-center gap-2.5"><span class="grid size-[13px] place-items-center rounded-full border border-[#A98088] text-[9px]">✓</span>100% оригінал · розпив із власного флакону бренду</li>
                <li class="flex items-center gap-2.5"><span class="grid size-[13px] place-items-center rounded-full border border-[#A98088] text-[9px]">✓</span>Відправка того ж дня при замовленні до 15:00</li>
            </ul>
        </div>
        </div>
    </section>
@endsection
