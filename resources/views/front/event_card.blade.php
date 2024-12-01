<a href="/event/{{ $event["id"] }}/{{ $event["slug"] }}" class="swiper-slide">
    <div class="ticket-wahlist h-full bg-light hover:bg-primary_color_o25_9 bg-opacity-5 rounded-2xl border border-primary_color_o10_1 hover:border-gray_9 overflow-hidden">
        <div class="h-32 md:h-48">
            @if(isset($event["gallery"]) and !empty($event["gallery"]))
                <div class="swiper-event">
                    <div class="swiper-wrapper">
                        @foreach(explode(",", $event["gallery"]) as $image)
                            <div class="swiper-slide h-32 md:h-48"><img class='w-full h-full object-cover'
                                                                        loading="lazy"
                                                                        src="{{ $event["imagePath"]. $image }}"
                                                                        {{--                                                                        src="{{ "https://ticketby.co/images/upload/". $image }}"--}}
                                                                        alt="{{ $event["name"] }}"></div>
                        @endforeach
                    </div>
                </div>
            @else
                <img class="w-full h-full object-cover"
                     src="{{$event["imagePath"].$event["image"]}}"
                     {{--                     src="{{ "https://ticketby.co/images/upload/".$event["image"]}}"--}}
                     alt="{{ $event["name"] }}">
            @endif
        </div>
        <div class="relative flex gap-1 md:gap-4 p-1 md:p-4 flex-wrap md:flex-nowrap flex-col lg:flex-row">
            <div class="text-center flex  items-baseline gap-1 md:gap-0 md:flex-col">
                @if($event["is_repeat"] == 0)
                    <span class="text-primary_color_7 text-h7 font-bold uppercase f-bri">
                            {{ $event->start_time->format("M") }}
                    </span>
                    <span class="font-bold text-h7 lg:text-h3 f-bri text-primary_color_7 lg:text-white">
                        {{ $event->start_time->format("d") }}
                    </span>
                @endif
            </div>
            <div>
                <h5 class="text-h6 md:text-h5 font-medium  md:mb-2">
                    {{ $event["translated_name"] }}
                </h5>
            </div>
        </div>
    </div>
</a>