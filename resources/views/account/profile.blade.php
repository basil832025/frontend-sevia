@extends('front.sevia::layouts.app')

@section('title', 'Особисті дані | Sevia')
@section('meta_description', 'Особисті дані в кабінеті Sevia')

@section('content')
    <div class="bg-white text-[#5B2730]">
        <nav class="mx-auto flex h-[50px] max-w-[1440px] items-center gap-2.5 px-[68px] pb-2 pt-[22px] text-[13px] leading-5 text-[#7A4751] max-lg:px-6 max-sm:hidden" aria-label="Breadcrumb">
            <a class="hover:text-[#5B2730]" href="{{ route('home') }}">Sevia</a>
            <span class="text-[#E8DAD0]">/</span>
            <a class="hover:text-[#5B2730]" href="{{ route('account.overview') }}">Особистий кабінет</a>
            <span class="text-[#E8DAD0]">/</span>
            <strong class="font-normal text-[#5B2730]">Особисті дані</strong>
        </nav>

        <header class="mx-auto grid max-w-[1440px] grid-cols-[minmax(0,1fr)_160px] items-end gap-8 px-[68px] pb-[35px] pt-[30px] max-lg:px-6 max-sm:flex max-sm:h-[90px] max-sm:flex-col max-sm:items-stretch max-sm:gap-2 max-sm:px-5 max-sm:pb-3.5 max-sm:pt-4">
            <div>
                <div class="text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:text-[11px] max-sm:leading-[13px] max-sm:tracking-[0.8px]">Особистий кабінет</div>
                <div class="max-sm:mt-2 max-sm:flex max-sm:items-baseline max-sm:justify-between">
                    <h1 class="m-0 mt-2 font-cormorant text-[64px] font-medium leading-none tracking-[-0.96px] text-[#5B2730] max-sm:m-0 max-sm:text-[32px] max-sm:leading-[39px] max-sm:tracking-normal">Особисті дані</h1>
                    <a class="hidden text-[11px] font-medium uppercase leading-[13px] tracking-[0.6px] text-[#7A4751] no-underline max-sm:inline-flex" href="{{ route('account.profile.edit') }}">Редагувати →</a>
                </div>
            </div>

            <a class="mb-1 justify-self-end text-[13px] font-medium uppercase leading-5 tracking-[2.34px] text-[#5B2730] no-underline max-sm:hidden" href="{{ route('account.profile.edit') }}">Редагувати →</a>
        </header>

        <section class="mx-auto grid max-w-[1440px] grid-cols-[240px_minmax(0,1fr)] gap-16 px-[68px] pb-[91px] pt-14 max-lg:grid-cols-[220px_minmax(0,1fr)] max-lg:gap-8 max-lg:px-6 max-sm:block max-sm:px-5 max-sm:pb-12 max-sm:pt-2">
            <aside class="self-start max-sm:hidden" aria-label="Account navigation">
                <ul class="m-0 list-none divide-y divide-[#EFE4D9] border-y border-[#EFE4D9] p-0">
                    <li>
                        <a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)_auto] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.overview') }}">
                            <span class="font-cormorant text-[16px] font-medium leading-[25px] tracking-[0.14px] text-[#A85D66]">01</span>
                            <span class="text-[14px] leading-[22px] tracking-[0.14px]">Огляд</span>
                        </a>
                    </li>
                    <li>
                        <a class="grid min-h-[50px] grid-cols-[32px_minmax(0,1fr)_auto] items-center gap-2.5 py-3 text-[#7A4751] no-underline" href="{{ route('account.orders') }}">
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

            <div class="flex min-w-0 max-w-[1000px] flex-col gap-9 max-sm:gap-0">
                @if (session('account_saved'))
                    <div class="mb-1 flex min-h-[91px] items-start gap-4 border border-[#ECD4CD] bg-[#E5F2EB] px-6 py-5 max-sm:mb-0 max-sm:h-10 max-sm:min-h-0 max-sm:items-center max-sm:gap-[9px] max-sm:border-0 max-sm:!bg-[#E5F2EB] max-sm:px-[14px] max-sm:py-3">
                        <svg class="h-[30px] w-[30px] shrink-0 max-sm:hidden" width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect width="30" height="30" rx="15" fill="#A85D66" />
                            <path d="M13.8501 19.8682C13.5864 19.8682 13.3545 19.7388 13.1543 19.48L10.1587 15.5908C10.0952 15.5078 10.0464 15.4272 10.0122 15.3491C9.98291 15.2661 9.96826 15.1855 9.96826 15.1074C9.96826 14.9219 10.0269 14.7729 10.144 14.6606C10.2612 14.5435 10.4126 14.4849 10.5981 14.4849C10.8228 14.4849 11.0132 14.5923 11.1694 14.8071L13.8208 18.3813L18.8599 10.2954C18.9429 10.1685 19.0259 10.0806 19.1089 10.0317C19.1919 9.97803 19.2993 9.95117 19.4312 9.95117C19.6118 9.95117 19.7559 10.0073 19.8633 10.1196C19.9756 10.2271 20.0317 10.3711 20.0317 10.5518C20.0317 10.6299 20.0195 10.708 19.9951 10.7861C19.9707 10.8643 19.9268 10.9521 19.8633 11.0498L14.5239 19.4946C14.3579 19.7437 14.1333 19.8682 13.8501 19.8682Z" fill="#FDFBF8" />
                        </svg>
                        <svg class="hidden h-4 w-4 shrink-0 max-sm:block" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <g clip-path="url(#clip0_358_853)">
                                <path d="M8 15C11.866 15 15 11.866 15 8C15 4.13401 11.866 1 8 1C4.13401 1 1 4.13401 1 8C1 11.866 4.13401 15 8 15Z" stroke="#4D8566" stroke-width="1.2" />
                                <path d="M5 8.1998L7 10.1998L11 5.7998" stroke="#4D8566" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_358_853">
                                    <rect width="16" height="16" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                        <div class="flex flex-col gap-[3px] pt-px max-sm:block max-sm:p-0">
                            <strong class="text-[16px] font-semibold leading-[25px] text-[#5B2730] max-sm:text-[12px] max-sm:font-medium max-sm:leading-[15px] max-sm:!text-[#4D8566]">Зміни збережено</strong>
                            <span class="text-[14px] leading-[21px] text-[#A98088] max-sm:hidden">Оновили твій профіль щойно. Тепер рекомендації стануть точнішими.</span>
                        </div>
                    </div>
                @endif
                <section class="flex flex-col gap-5 max-sm:gap-2.5 max-sm:pb-3.5 max-sm:pt-2">
                    <div class="flex h-[57px] items-end border-b border-[#EFE4D9] pb-3 max-sm:h-6 max-sm:items-baseline max-sm:justify-between max-sm:border-0 max-sm:pb-0">
                        <h2 class="m-0 font-cormorant text-[28px] font-medium leading-[43px] tracking-[-0.28px] text-[#5B2730] max-sm:text-[20px] max-sm:leading-6 max-sm:tracking-normal">Контактна інформація</h2>
                        <a class="hidden text-[10.5px] font-medium uppercase leading-[13px] tracking-[0.6px] text-[#A98088] no-underline max-sm:inline-flex" href="{{ route('account.profile.edit') }}">Редагувати →</a>
                    </div>

                    <dl class="m-0 flex w-[376px] max-w-full flex-col gap-7 border-t border-[#EFE4D9] pt-[18px] max-sm:w-full max-sm:gap-0 max-sm:border-0 max-sm:bg-[#F8EDE7] max-sm:px-4 max-sm:pt-0">
                        @foreach ($profileRows as $row)
                            @if ($loop->iteration <= 4)
                                <div class="flex min-h-[30px] items-baseline justify-between gap-8 border-b border-dashed border-[#EFE4D9] pb-2 max-sm:min-h-[41px] max-sm:items-center max-sm:gap-4 max-sm:border-b-0 max-sm:border-t max-sm:border-solid max-sm:border-[#E8DAD0] max-sm:py-[13px] max-sm:pb-[13px] max-sm:first:border-t-0">
                                    <dt class="shrink-0 text-[11px] uppercase leading-[17px] tracking-[2.42px] text-[#A98088] max-sm:text-[11px] max-sm:leading-[13px] max-sm:tracking-[0.4px]">{{ $row['label'] }}</dt>
                                    <dd class="m-0 min-w-0 flex-1 text-right text-[13px] font-medium leading-5 text-[#5B2730] max-sm:text-[12.5px] max-sm:leading-[15px]">{{ $row['value'] }}</dd>
                                </div>
                            @endif
                        @endforeach
                    </dl>
                </section>

                <section class="flex flex-col gap-5 max-sm:gap-2.5 max-sm:pb-3.5 max-sm:pt-2">
                    <div class="flex h-[57px] items-end justify-between border-b border-[#EFE4D9] pb-3 max-sm:h-6 max-sm:items-baseline max-sm:border-0 max-sm:pb-0">
                        <h2 class="m-0 font-cormorant text-[28px] font-medium leading-[43px] tracking-[-0.28px] text-[#5B2730] max-sm:text-[20px] max-sm:leading-6 max-sm:tracking-normal">Адреса доставки</h2>
                        <a class="text-[12px] font-medium uppercase leading-[19px] tracking-[2.16px] text-[#5B2730] no-underline max-sm:text-[10.5px] max-sm:leading-[13px] max-sm:tracking-[0.6px] max-sm:text-[#A98088]" href="{{ route('account.address.edit') }}">Змінити →</a>
                    </div>

                    <dl class="m-0 flex w-[376px] max-w-full flex-col gap-[26px] border-t border-[#EFE4D9] pt-[18px] max-sm:w-full max-sm:gap-0 max-sm:border-0 max-sm:bg-[#F8EDE7] max-sm:px-4 max-sm:pt-0">
                        @foreach ($deliveryRows as $row)
                            @if ($loop->iteration <= 2)
                                <div class="flex min-h-[30px] items-baseline justify-between gap-8 border-b border-dashed border-[#EFE4D9] pb-2 max-sm:min-h-[41px] max-sm:items-center max-sm:gap-4 max-sm:border-b-0 max-sm:border-t max-sm:border-solid max-sm:border-[#E8DAD0] max-sm:py-[13px] max-sm:pb-[13px] max-sm:first:border-t-0">
                                    <dt class="shrink-0 text-[11px] uppercase leading-[17px] tracking-[2.42px] text-[#A98088] max-sm:text-[11px] max-sm:leading-[13px] max-sm:tracking-[0.4px]">{{ $row['label'] }}</dt>
                                    <dd class="m-0 min-w-0 flex-1 text-right text-[13px] font-medium leading-5 text-[#5B2730] max-sm:text-[12.5px] max-sm:leading-[15px]">{{ $row['value'] }}</dd>
                                </div>
                            @endif
                        @endforeach
                    </dl>
                </section>
            </div>
        </section>
    </div>
@endsection
