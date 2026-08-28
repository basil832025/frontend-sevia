@extends('front.sevia::layouts.app')

@section('title', '404 - Сторінка не знайдена | Sevia')
@section('meta_description', 'Сторінка не знайдена')

@section('content')
    <section class="flex min-h-[743.83px] flex-col items-center bg-white px-[68.04px] py-[105.84px] text-center max-lg:px-6 max-lg:py-20 max-sm:min-h-[680.47px] max-sm:px-5 max-sm:pb-20 max-sm:pt-10">
        <div class="flex flex-col items-center">
            <div class="pb-4 max-sm:pb-0">
                <p class="m-0 font-cormorant text-[200px] font-medium leading-none tracking-[-8px] text-[#A85D66] max-sm:h-[145px] max-sm:w-[166px] max-sm:text-[120px] max-sm:leading-[145px] max-sm:tracking-normal max-sm:text-[#CD9B97]">
                    404
                </p>
            </div>

            <div class="pb-3.5 max-sm:h-[27px] max-sm:w-[82px] max-sm:pb-3">
                <p class="m-0 text-[11px] font-medium uppercase leading-[17px] tracking-[3.08px] text-[#A98088] max-sm:text-[12px] max-sm:leading-[15px] max-sm:text-[#7A4751]">
                    Помилка
                </p>
            </div>

            <div class="pb-[18px] max-sm:h-[100px] max-sm:w-[174px] max-sm:pb-4">
                <h1 class="m-0 max-w-[400.68px] font-cormorant text-[42px] font-medium leading-[45px] tracking-[-0.63px] text-[#5B2730] max-sm:w-[174px] max-sm:text-[36px] max-sm:leading-[42px] max-sm:tracking-normal">
                    Сторінка не знайдена
                </h1>
            </div>

            <div class="max-w-[481px] pb-10 max-sm:h-[106px] max-sm:w-[373px] max-sm:px-2.5">
                <p class="m-0 px-[11.32px] text-[16px] font-normal leading-[25px] text-[#7A4751] max-sm:w-[353px] max-sm:px-0 max-sm:text-[14px] max-sm:leading-[22px]">
                    Можливо, посилання застаріло або сторінка переїхала. Перевір адресу або повертайся на головну.
                </p>
            </div>

            <div class="flex w-[486px] flex-wrap justify-center gap-x-3 gap-y-3 pb-9 max-sm:h-[182.47px] max-sm:w-[353px] max-sm:flex-col max-sm:items-center max-sm:gap-3 max-sm:pb-0">
                <a class="inline-flex h-[50.15px] w-[152px] items-center justify-center bg-[#5B2730] px-7 py-[13.25px] text-[13px] font-medium uppercase leading-5 tracking-[1.56px] text-[#FDFBF8] max-sm:w-[353px]" href="{{ route('home') }}">
                    На головну
                </a>
                <a class="inline-flex h-[50.15px] w-[161px] items-center justify-center border border-[#5B2730] px-7 py-[13.25px] text-[13px] font-medium uppercase leading-5 tracking-[1.56px] text-[#5B2730] max-sm:h-[54.16px] max-sm:w-[353px] max-sm:px-9 max-sm:py-[15.25px]" href="{{ route('home') }}#catalog">
                    До каталогу
                </a>
                <a class="inline-flex h-[50.15px] w-[149px] items-center justify-center border border-[#5B2730] px-7 py-[13.25px] text-[13px] font-medium uppercase leading-5 tracking-[1.56px] text-[#5B2730] max-sm:h-[54.16px] max-sm:w-[353px] max-sm:px-9 max-sm:py-[15.25px]" href="{{ route('home') }}#sale">
                    До знижок
                </a>
            </div>
        </div>
    </section>
@endsection
