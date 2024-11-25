@extends('front.master', ['activePage' => 'checkout'])
@section('title', __('All categories'))
@section('content')
@php
$lang = session('direction') == 'rtl' ? 'ar' : 'en';

@endphp

<div class="container mt-12 md:mt-32">
    @if (count($data) == 0)
    <div class="text-center ">
        {{ __('There are no Categories added yet') }}
    </div>
    @endif
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-4 ">
        @foreach ($data as $item)
        <div class=" border-hover  h-36 md:h-40  xl:h-64 relative overflow-hidden flex items-center justify-center opacity-65 hover:opacity-100   transition-all duration-500    rounded-2xl md:rounded-3xl">
            <img src="{{ asset('images/upload/' . $item->image) }}" class="h-full w-full hover:scale-110 transition-all duration-500 transform" alt="">
            <p class=" text-h3  md:text-h2 xl:text-h1  text-center  absolute font-bold">
                @if ($lang == 'ar')
                {{ $item->ar_name }}
                @else
                {{ $item->name }}
                @endif
            </p>
        </div>
        @endforeach
    </div>
</div>
@endsection