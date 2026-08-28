@extends('front.sevia::layouts.app')

@section('title', 'Редагувати особисті дані | Sevia')
@section('meta_description', 'Редагування особистих даних в кабінеті Sevia')

@section('content')
    <div class="bg-white text-[#5B2730]">
        <nav class="mx-auto flex h-[50px] max-w-[1440px] items-center gap-2.5 px-[68px] pb-2 pt-[22px] text-[13px] leading-5 text-[#7A4751] max-lg:px-6 max-sm:hidden" aria-label="Breadcrumb">
            <a class="hover:text-[#5B2730]" href="{{ route('home') }}">Sevia</a>
            <span class="text-[#E8DAD0]">/</span>
            <a class="hover:text-[#5B2730]" href="{{ route('account.overview') }}">Особистий кабінет</a>
            <span class="text-[#E8DAD0]">/</span>
            <a class="hover:text-[#7A4751]" href="{{ route('account.profile') }}">Особисті дані</a>
            <span class="text-[#E8DAD0]">/</span>
            <strong class="font-normal text-[#5B2730]">Редагувати</strong>
        </nav>

        <header class="mx-auto max-w-[1440px] px-[68px] pb-[35px] pt-[30px] max-lg:px-6 max-sm:px-5 max-sm:pb-5 max-sm:pt-4">
            <div class="text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:text-[11px] max-sm:leading-[13px] max-sm:tracking-[0.8px]">Особистий кабінет</div>
            <h1 class="m-0 mt-2 font-cormorant text-[64px] font-medium leading-none tracking-[-0.96px] text-[#5B2730] max-sm:text-[32px] max-sm:leading-[39px] max-sm:tracking-normal">Особисті дані</h1>
        </header>

        <section class="mx-auto grid max-w-[1440px] grid-cols-[240px_minmax(0,1fr)] gap-[60px] px-[68px] pb-[91px] pt-14 max-lg:grid-cols-[220px_minmax(0,1fr)] max-lg:gap-8 max-lg:px-6 max-sm:block max-sm:px-5 max-sm:pb-12 max-sm:pt-2">
            <aside class="self-stretch max-sm:hidden" aria-label="Account navigation">
                <ul class="m-0 list-none divide-y divide-[#EFE4D9] border-y border-[#EFE4D9] p-0">
                    <li>
                        <a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)_auto] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.overview') }}">
                            <span class="font-cormorant text-[16px] font-medium leading-[25px] tracking-[0.14px] text-[#A85D66]">01</span>
                            <span class="text-[14px] leading-[22px] tracking-[0.14px]">Огляд</span>
                        </a>
                    </li>
                    <li>
                        <a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)_auto] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="#">
                            <span class="font-cormorant text-[16px] font-medium leading-[25px] tracking-[0.14px] text-[#A85D66]">02</span>
                            <span class="text-[14px] leading-[22px] tracking-[0.14px]">Замовлення</span>
                            <span class="text-[11px] uppercase leading-[17px] tracking-[1.98px] text-[#A98088]">{{ $ordersCount }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)_auto] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.favorites') }}">
                            <span class="font-cormorant text-[16px] font-medium leading-[25px] tracking-[0.14px] text-[#A85D66]">03</span>
                            <span class="text-[14px] leading-[22px] tracking-[0.14px]">Обране</span>
                            <span class="text-[11px] uppercase leading-[17px] tracking-[1.98px] text-[#A98088]">{{ $favoritesCount }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)_auto] items-center gap-2.5 py-3 text-[#5B2730] no-underline" href="{{ route('account.profile') }}">
                            <span class="font-cormorant text-[16px] font-medium leading-[25px] tracking-[0.14px] text-[#5B2730]">05</span>
                            <span class="text-[14px] font-semibold leading-[22px] tracking-[0.14px]">Особисті дані</span>
                        </a>
                    </li>
                </ul>
                <form method="POST" action="{{ route('account.logout') }}">
                    @csrf
                    <button class="mt-4 border-0 bg-transparent p-0 text-left text-[12px] uppercase leading-[19px] tracking-[2.16px] text-[#7A4751]" type="submit">← Вийти</button>
                </form>
            </aside>

            <div class="min-w-0">
                @if ($errors->any())
                    <div class="mb-10 flex min-h-[91px] items-start gap-4 border border-[#A85D66] bg-white px-6 py-5 max-sm:mb-2 max-sm:min-h-0 max-sm:items-center max-sm:gap-3 max-sm:px-4 max-sm:py-3">
                        <span class="flex h-[30px] w-[30px] shrink-0 items-center justify-center rounded-full bg-[#5B2730] text-[15px] leading-[23px] text-[#FDFBF8] max-sm:h-4 max-sm:w-4 max-sm:text-[11px]" aria-hidden="true">!</span>
                        <div class="flex flex-col gap-[3px] pt-px">
                            <strong class="text-[16px] font-semibold leading-[25px] text-[#5B2730] max-sm:text-[12px] max-sm:leading-[15px]">Не вдалося зберегти</strong>
                            <span class="text-[14px] leading-[21px] text-[#A98088] max-sm:text-[11px] max-sm:leading-[15px]">{{ $errors->first() }}</span>
                        </div>
                    </div>
                @endif
                <form class="flex w-full max-w-[680px] flex-col gap-12 max-sm:max-w-none max-sm:gap-8" method="POST" action="{{ route('account.profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <section class="flex flex-col gap-[22px] max-sm:gap-[14px]">
                        <h2 class="m-0 border-b border-[#EFE4D9] pb-3 font-cormorant text-[28px] font-medium leading-[43px] tracking-[-0.28px] text-[#5B2730] max-sm:border-0 max-sm:pb-0 max-sm:text-[20px] max-sm:leading-6 max-sm:tracking-normal">Контактна інформація</h2>

                        @php($birthdaySet = !empty($client?->birthday) && $client->birthday->lte(now()->subYears(16)->endOfDay()))
                        <div class="grid grid-cols-2 gap-x-7 gap-y-[22px] max-sm:grid-cols-1 max-sm:gap-y-[6px] max-sm:[&_label]:gap-[7px] max-sm:[&_label>span]:text-[10px] max-sm:[&_label>span]:leading-3 max-sm:[&_label>span]:tracking-[1.2px] max-sm:[&_label>input]:h-[42px] max-sm:[&_label>input]:bg-white max-sm:[&_label>input]:px-[14px] max-sm:[&_label>input]:text-[13px] max-sm:[&_label>input]:leading-4">
                            <label class="flex flex-col gap-1.5">
                                <span class="text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088]">Імʼя</span>
                                <input class="h-11 w-full border border-[#E8DAD0] bg-[#FDFBF8] px-3.5 text-[15px] leading-[18px] text-[#5B2730] outline-none focus:border-[#5B2730]" type="text" name="name" value="{{ $form['name'] }}">
                            </label>

                            <label class="flex flex-col gap-1.5">
                                <span class="text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088]">Прізвище</span>
                                <input class="h-11 w-full border border-[#E8DAD0] bg-[#FDFBF8] px-3.5 text-[15px] leading-[18px] text-[#5B2730] outline-none focus:border-[#5B2730]" type="text" name="surname" value="{{ $form['surname'] }}">
                            </label>

                            <label class="col-span-2 flex flex-col gap-1.5 max-sm:col-span-1">
                                <span class="text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088]">Email</span>
                                <input class="h-11 w-full border border-[#E8DAD0] bg-[#FDFBF8] px-3.5 text-[15px] leading-[18px] text-[#5B2730] outline-none focus:border-[#5B2730]" type="email" name="email" value="{{ $form['email'] }}">
                            </label>

                            <label class="flex flex-col gap-1.5">
                                <span class="text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088]">Телефон</span>
                                <input class="h-11 w-full cursor-default border border-[#E8DAD0] bg-[#F3F0ED] px-3.5 text-[15px] leading-[18px] text-[#7A4751] outline-none" type="tel" name="phone" value="{{ $form['phone'] }}" readonly aria-readonly="true">
                            </label>

                            <label class="flex flex-col gap-1.5">
                                <span class="text-[11px] font-semibold uppercase leading-[17px] tracking-[2.42px] text-[#A98088]">Дата народження</span>
                                <input x-ref="birthday" data-birthday-picker data-locale="{{ app()->getLocale() }}" data-max-date="{{ now()->subYears(16)->format('d.m.Y') }}" class="h-11 w-full border border-[#E8DAD0] bg-[#FDFBF8] px-3.5 text-[15px] leading-[18px] text-[#5B2730] outline-none focus:border-[#5B2730] {{ $birthdaySet ? 'cursor-default bg-[#F3F0ED] text-[#7A4751]' : '' }}" type="text" name="birthday" value="{{ $form['birthday'] }}" placeholder="ДД.ММ.ГГГГ" inputmode="numeric" autocomplete="bday" @if($birthdaySet) readonly aria-readonly="true" @endif>

                                <div data-birthday-dialog class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-[#5B2730]/35 px-5" role="dialog" aria-modal="true" aria-labelledby="birthday-dialog-title">
                                    <div class="w-full max-w-[380px] border border-[#E8DAD0] bg-[#FDFBF8] p-6 shadow-[0_20px_60px_rgba(91,39,48,0.18)] max-sm:p-5" data-birthday-dialog-panel>
                                        <p class="m-0 text-[10px] font-medium uppercase leading-3 tracking-[1.8px] text-[#A98088]">Особисті дані</p>
                                        <h3 id="birthday-dialog-title" class="m-0 mt-2 font-cormorant text-[26px] font-medium leading-8 text-[#5B2730]">Підтвердження дати народження</h3>
                                        <p class="m-0 mt-3 text-[13px] leading-5 text-[#7A4751]">Ви обрали <strong class="font-medium text-[#5B2730]" data-birthday-dialog-date></strong>.</p>
                                        <p class="m-0 mt-1 text-[13px] leading-5 text-[#7A4751]">Після підтвердження дату народження змінити буде неможливо.</p>
                                        <div class="mt-6 grid grid-cols-2 gap-3">
                                            <button class="h-11 border border-[#5B2730] bg-transparent px-3 text-[11px] font-medium uppercase tracking-[1.4px] text-[#5B2730]" type="button" data-birthday-dialog-cancel>Скасувати</button>
                                            <button class="h-11 border border-[#5B2730] bg-[#5B2730] px-3 text-[11px] font-medium uppercase tracking-[1.4px] text-[#FDFBF8]" type="button" data-birthday-dialog-confirm>Підтвердити</button>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                    </section>

                    <div class="flex justify-end gap-3.5 border-t border-[#EFE4D9] pt-7 max-sm:gap-[10px] max-sm:border-0 max-sm:pt-0">
                        <a class="inline-flex h-[50px] items-center justify-center border border-[#5B2730] px-7 text-[13px] font-medium uppercase leading-5 tracking-[1.56px] text-[#5B2730] no-underline max-sm:h-9 max-sm:flex-1 max-sm:px-2 max-sm:text-[10px] max-sm:tracking-[1.2px]" href="{{ route('account.profile') }}">Скасувати</a>
                        <button class="inline-flex h-[50px] items-center justify-center border border-[#5B2730] bg-[#5B2730] px-7 text-[13px] font-medium uppercase leading-5 tracking-[1.56px] text-[#FDFBF8] max-sm:h-9 max-sm:flex-1 max-sm:px-2 max-sm:text-[10px] max-sm:tracking-[1.2px]" type="submit">Зберегти зміни</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
