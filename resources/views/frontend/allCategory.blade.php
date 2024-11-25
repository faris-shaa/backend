@extends('frontend.master', ['activePage' => 'category'])
@section('title', __('All categories'))
@section('content')
    <div class="pb-20 bg-scroll min-h-screen" style="background-image: url('images/events.png')">
        {{-- scroll --}}
        <div class="mr-4 flex justify-end z-30">
            <a type="button" href="{{ url('#') }}"
                class="scroll-up-button bg-primary rounded-full p-4 fixed z-20 mt-[30%]">
                <img src="{{ asset('images/downarrow.png') }}" alt="" class="w-3 h-3 z-20">
            </a>
        </div>
        <div
            class="mt-5 3xl:mx-52 2xl:mx-28 1xl:mx-28 xl:mx-36 xlg:mx-32 lg:mx-36 xxmd:mx-24 xmd:mx-32 md:mx-28 sm:mx-20 msm:mx-16 xsm:mx-10 xxsm:mx-5 z-10 relative">
            <div
                class="absolute bg-success blur-3xl opacity-10 s:bg-opacity-10 3xl:w-[370px] 3xl:h-[370px] 2xl:w-[300px] 2xl:h-[300px] 1xl:w-[300px] xmd:w-[300px] xmd:h-[300px] sm:w-[200px] sm:h-[300px] xxsm:w-[300px] xxsm:h-[300px] rounded-full -mt-5 2xl:-ml-20 1xl:-ml-20 sm:ml-2 xxsm:-ml-7">
            </div>

            <div class="flex justify-start pt-5 z-10">
                <p
                    class="font-poppins font-semibold md:text-5xl xxsm:text-2xl xsm:text-2xl sm:text-2xl text-success leading-10 ">
                    {{ __('Categories') }}</p>
            </div>
            @if (count($data) == 0)
                <div class="font-poppins font-medium text-lg leading-4 text-black mt-10  capitalize">
                    {{ __('There are no Categories added yet') }}
                </div>
            @endif
            <div
                class="grid gap-x-7 3xl:grid-cols-4 xl:grid-cols-4 xlg:grid-cols-2 xxmd:grid-cols-2 xxmd:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 msm:gapy-7 xxsm:gap-y-7 justify-between pt-10 z-10 relative">
                @foreach ($data as $item)
                    <div
                        class="shadow-2xl bg-white p-5 rounded-lg hover:scale-110 transition-all duration-500 cursor-pointer">
                        <img src="{{ asset('images/upload/' . $item->image) }}" alt=""
                            class="h-40 rounded-lg w-full object-cover">
                        <a href="{{ url('events-category/' . $item->id) . '/' . $item->name }}">
                            <p class="font-popping font-semibold text-xl leading-8 text-center pt-3">{{ $item->name }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
