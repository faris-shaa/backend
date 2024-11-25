@extends('front.master', ['activePage' => 'event'])
@section('title', __('All Events'))
@section('content')


@php
use Illuminate\Support\Facades\Http;


$citys = null;
try {
// Use environment variable for the base URL
$baseUrl = env('API_BASE_URL', 'https://ticketby.pixicard.com');
$response = Http::withOptions(['verify' => false])->get("{$baseUrl}/api/city");

if ($response->successful()) {
$citys = $response->json();
} else {
$error = $response->json();
}
} catch (\Exception $e) {
// Handle the exception
// Log the exception message or display an error message
}

$lang = session('direction') == 'rtl' ? 'ar' : 'en';


@endphp



<input type="hidden" value="{{request('categoty_id')}}" id="category_id_input">
<input type="hidden" value="{{request('city_id')}}" id="city_id_input">

<div class="container mt-32">
    <div class="col-span-12 md:col-span-8 xl:col-span-9">
        <div class="flex  justify-start  placeholder-white gap-2 flex-wrap mb-8">
            <div class="flex justify-end gap-2  w-full md:w-auto">
                <div class="flex items-center bg-gray_f   h-12 p-1 px-1 md:px-4 rounded-5xl flex-1 @if($lang == 'ar')  text-right @endif">
                    <select name="" id="" style="width: 100%;" data-minimum-results-for-search="Infinity"
                        class=" select2 h-12 w-full focus:border-primary_color_6 outline-0 bg-transparent   ">
                        <option value="Price">{{__('Price')}}</option>
                    </select>
                </div>
                <div class="flex items-center bg-gray_f   h-12 p-1 px-1 md:px-4 rounded-5xl flex-1  @if($lang == 'ar')  text-right @endif">
                    <select name="" id="SearchEventDate" style="width: 100%;" data-minimum-results-for-search="Infinity"
                        class=" select2 h-12 w-full focus:border-primary_color_6 outline-0 bg-transparent   ">
                        <option value=""> {{__('all')}}</option>
                        <option value="Today"> {{__('Today')}}</option>
                        <option value="Tommorow"> {{__('Tommorow')}}</option>
                        <option value="This Week"> {{__('This Week')}}</option>
                        <option value="choose_date"> {{__('choose date')}}</option>
                    </select>
                </div>
                <div class="flex items-center bg-gray_f   h-12 p-1 px-1 md:px-4 rounded-5xl flex-1  @if($lang == 'ar')  text-right @endif">
                    <select name="" id="SearchEventCity" style="width: 100%;" data-minimum-results-for-search="Infinity"
                        class=" select2 h-12 w-full focus:border-primary_color_6 outline-0 bg-transparent   ">
                        <option value=""> {{__('Any place')}}</option>
                        @foreach ($citys['city'] as $city)
                        <option value="{{$city['id']}}">{{ $lang == 'ar' ? $city['arabic_name'] : $city['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="relative flex items-center bg-gray_f   h-12 p-1 px-3 md:px-4 rounded-5xl   flex items-end gap-1 items-center flex-1 md:flex-initial">
                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.375 6.4375C11.375 7.69531 10.9648 8.87109 10.2812 9.80078L13.7266 13.2734C14.082 13.6016 14.082 14.1758 13.7266 14.5039C13.3984 14.8594 12.8242 14.8594 12.4961 14.5039L9.02344 11.0312C8.09375 11.7422 6.91797 12.125 5.6875 12.125C2.54297 12.125 0 9.58203 0 6.4375C0 3.32031 2.54297 0.75 5.6875 0.75C8.80469 0.75 11.375 3.32031 11.375 6.4375ZM5.6875 10.375C7.84766 10.375 9.625 8.625 9.625 6.4375C9.625 4.27734 7.84766 2.5 5.6875 2.5C3.5 2.5 1.75 4.27734 1.75 6.4375C1.75 8.625 3.5 10.375 5.6875 10.375Z" fill="#666666" />
                </svg>
                <input type="text" name="" id="SearchEventName" placeholder="{{__('Search')}}" class=" w-14 focus:border-primary_color_6 outline-0 bg-transparent">
                <div class="ms-auto hidden absolute @if($lang == 'ar') left-2 @else right-2 @endif  bottom-1/2 transform translate-y-1/2 " id="Searchbtn">
                    <svg class="@if($lang == 'ar') rotate-180 @endif" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.25 1.21875L13.75 6.46875C13.9062 6.625 14 6.8125 14 7.03125C14 7.21875 13.9062 7.40625 13.75 7.5625L8.25 12.8125C7.96875 13.0938 7.46875 13.0938 7.1875 12.7812C6.90625 12.5 6.90625 12 7.21875 11.7188L11.375 7.78125H0.75C0.3125 7.78125 0 7.4375 0 7.03125C0 6.59375 0.3125 6.28125 0.75 6.28125H11.375L7.21875 2.3125C6.90625 2.03125 6.90625 1.53125 7.1875 1.25C7.46875 0.9375 7.9375 0.9375 8.25 1.21875Z" fill="#A986BF" />
                    </svg>
                </div>
                <div class="ms-auto hidden absolute @if($lang == 'ar') left-2 @else right-2 @endif  bottom-1/2 transform translate-y-1/2 " id="clear_search">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 16C3.5625 16 0 12.4375 0 8C0 3.59375 3.5625 0 8 0C12.4062 0 16 3.59375 16 8C16 12.4375 12.4062 16 8 16ZM5.46875 5.46875C5.15625 5.78125 5.15625 6.25 5.46875 6.53125L6.9375 8L5.46875 9.46875C5.15625 9.78125 5.15625 10.25 5.46875 10.5312C5.75 10.8438 6.21875 10.8438 6.5 10.5312L7.96875 9.0625L9.4375 10.5312C9.75 10.8438 10.2188 10.8438 10.5 10.5312C10.8125 10.25 10.8125 9.78125 10.5 9.46875L9.03125 8L10.5 6.53125C10.8125 6.25 10.8125 5.78125 10.5 5.46875C10.2188 5.1875 9.75 5.1875 9.4375 5.46875L7.96875 6.9375L6.5 5.46875C6.21875 5.1875 5.75 5.1875 5.46875 5.46875Z" fill="#999999" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="mt-4 hidden text-center" id="empty_search">
            <svg class="mx-auto" width="128" height="120" viewBox="0 0 128 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.2766 14.0379C10.4503 13.4008 9.47195 13.0909 8.50501 13.0909C7.16908 13.0909 5.84495 13.6759 4.96014 14.7939C3.42508 16.7288 3.76483 19.5265 5.72195 21.0414L6.73287 21.8247C6.80973 21.8204 6.88673 21.8182 6.96377 21.8182C7.83402 21.8182 8.71455 22.0996 9.45823 22.6781L109.358 100.779C109.879 101.186 110.273 101.696 110.533 102.256L116.721 107.051C118.69 108.572 121.514 108.224 123.038 106.295C124.573 104.361 124.233 101.563 122.276 100.048L119.726 98.0716C123.216 95.6312 125.499 91.5822 125.499 87V70.125C119.907 70.125 115.374 65.5919 115.374 60C115.374 54.408 119.907 49.875 125.499 49.875V33C125.499 25.5438 119.455 19.5 111.999 19.5H18.3256L11.2766 14.0379ZM95.867 100.5L87.233 93.75H17.499C13.777 93.75 10.749 90.722 10.749 87V75.4655C16.7042 72.8568 20.874 66.9065 20.874 60C20.874 53.0935 16.7042 47.1427 10.749 44.534V33.9551L4.56478 29.1203C4.19675 30.349 3.99902 31.6514 3.99902 33V49.875C9.59098 49.875 14.124 54.408 14.124 60C14.124 65.5919 9.59098 70.125 3.99902 70.125V87C3.99902 94.4558 10.0432 100.5 17.499 100.5H95.867ZM30.999 49.7865V73.5C30.999 77.2277 34.0213 80.25 37.749 80.25H69.9651L61.3311 73.5H37.749V55.0636L30.999 49.7865ZM53.1702 46.5L88.0148 73.5H91.749V46.5H53.1702ZM98.499 73.5C98.499 75.8959 97.2507 78.0004 95.3688 79.1983L113.825 93.4992C116.663 92.701 118.749 90.0896 118.749 87V75.4655C112.794 72.8568 108.624 66.9065 108.624 60C108.624 53.0935 112.794 47.1427 118.749 44.534V33C118.749 29.278 115.721 26.25 111.999 26.25H27.0368L44.4591 39.75H91.749C95.4771 39.75 98.499 42.7719 98.499 46.5V73.5Z" fill="#312C35"></path>
            </svg>
            <p class="text-h6 mb-1 mt-4">{{__('No tickets found')}} </p>
        </div>
        <div class=" grid grid-cols-12 gap-2 xl:gap-20" id="event"></div>
        <div class="flex justify-center">
            <button id="load_more" class=" mt-16  rounded-5xl border border-light   text-center    py-2 px-4 lg:px-p32    lg:w-48 f-bri l leading-5  inline-block">
                {{__("Load More")}}</button>
        </div>
    </div>
</div>



@if (session('direction') == 'rtl')
<script>
    var lang = 'ar';
</script>
@else
<script>
    var lang = 'en';
</script>
@endif

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script script>
    $(document).ready(function() {

        $(document).ready(function() {
            $('#SearchEventName').on('focus', function() {
                $(this).css('width', '100%');
            });

            $('#SearchEventName').on('blur', function() {
                $(this).css('width', '3.5rem');
            });
        }); 
        var SearchEventCat = $("#category_id_input").val();
        var SearchEventCity = $("#city_id_input").val();
        let limit = -1;
        $("#load_more").click(function() {
            limit += 3
            fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);
        });



        var SearchEventName = ''
        $('#SearchEventName').on('keyup', function() {
            $("#Searchbtn").removeClass('hidden');
            SearchEventName = $(this).val();
            if (SearchEventName == '') {
                $("#Searchbtn").addClass('hidden');
                fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);
            }
        });
        $("#Searchbtn").on("click", function() {
            $("#clear_search").removeClass('hidden');
            $("#Searchbtn").addClass('hidden');
            fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);

        })
       
        $('#SearchEventCity').on('change', function() {
            SearchEventCity = $(this).val();
            console.log(SearchEventCity);
            fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);

        });
        var SearchEventDate = ''
        $('#SearchEventDate').on('change', function() {
            SearchEventDate = $(this).val();
            fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);

        });
     
        $('#SearchEventCat').on('change', function() {
            SearchEventCat = $(this).val();
            fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);
        });

        fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);

        function fetchEvents(query = '', date = '', city_id = '', category_id = '') {
            let url = `{{url('api/user/search-event/web')}}`;
            $.ajax({
                url: url,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    search: query,
                    date: date,
                    city_id: city_id,
                    category_id: category_id,
                    limit: limit
                },
                success: function(data) {
                    $('#event').html('');
                    let events = [];
                    if (Array.isArray(data)) {
                        events = data;
                    } else if (data.data && Array.isArray(data.data)) {
                        events = data.data;
                    }

                    if (events.length === 0) {
                        $('#empty_search').removeClass("hidden");
                    } else {
                        $('#empty_search').addClass("hidden");
                    }
                    events.forEach(item => {
                        let gallery = '';
                        if (item.gallery && item.gallery.length > 0) {
                            gallery = item.gallery.split(',').map(image => {
                                return `<div class="swiper-slide h-32 md:h-48"> <img class='w-full h-full object-cover' src="${item.imagePath}${image}" alt="${item.name}"> </div>`;
                            }).join('');
                        }
                        let day = dateFormat(item.time).day;
                        let month = dateFormat(item.time).shortMonth;
                        let eventHtml = `
                          <div class="bg-light bg-opacity-5 rounded-2xl border border-primary_color_o10_1 overflow-hidden  col-span-6 md:col-span-4  xl:col-span-4 ">
                <a href="/event/${item.id}/${item.slug}" class="swiper-slide">
                            <div class="ticket-wahlist h-full bg-light hover:bg-primary_color_o25_9 bg-opacity-5 rounded-2xl border border-primary_color_o10_1 hover:border-gray_9 overflow-hidden">
                            <div class="h-32 md:h-48">
                                 ${item.gallery && item.gallery.length > 0 ? 
                                    `<div class="swiper-devent2">
                                          <div class="swiper-wrapper">
                                            ${gallery}
                                          </div>
                                    </div>` 
                                    : 
                                    `<img class="w-full h-full object-cover" src="${item.imagePath}${item.image}" alt="${item.name}">`
                                 }
                              </div>
                                <div class="relative flex gap-1 md:gap-4 p-1 md:p-4 flex-wrap md:flex-nowrap flex-col lg:flex-row">
                                    <div class="text-center flex  items-baseline gap-1 md:gap-0 md:flex-col">
                                        <span class="text-primary_color_7 text-h7 font-bold uppercase f-bri">${month}
                                        </span>
                                        <span class="font-bold text-h7 lg:text-h3 f-bri text-primary_color_7 lg:text-white">${day}
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="text-h6 md:text-h5 font-medium  mb-1 md:mb-2">
                                    ${ lang == 'ar' ? item.name_arabic : item.name }
                                        </h5>
                                        <p class="pline2 f-bri text-gray_6 text-h6">
                                        ${ lang == 'ar' ? item.short_description : item.short_description }
                                        </p>
                                    </div>
                                    <div class="wahlist  lg:hidden" id="${item.id}">
                                    <svg width="25" height="22" viewBox="0 0 25 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                   <path d="M12.4531 3.39014L13.0156 2.87451C14.5625 1.32764 16.7656 0.624512 18.875 0.999512C22.1094 1.51514 24.5 4.32764 24.5 7.60889V7.84326C24.5 9.81201 23.6562 11.687 22.25 12.9995L13.7656 20.9214C13.4375 21.2495 12.9688 21.3901 12.5 21.3901C11.9844 21.3901 11.5156 21.2495 11.1875 20.9214L2.70312 12.9995C1.29688 11.687 0.5 9.81201 0.5 7.84326V7.60889C0.5 4.32764 2.84375 1.51514 6.07812 0.999512C8.1875 0.624512 10.3906 1.32764 11.9375 2.87451L12.4531 3.39014ZM12.4531 5.54639L10.8594 3.90576C9.6875 2.73389 8 2.17139 6.3125 2.45264C3.82812 2.87451 1.95312 5.03076 1.95312 7.60889V7.84326C1.95312 9.39014 2.60938 10.8433 3.73438 11.8745L12.2188 19.7964C12.2656 19.8901 12.3594 19.8901 12.4531 19.8901C12.5938 19.8901 12.6875 19.8901 12.7344 19.7964L21.2188 11.8745C22.3438 10.8433 23 9.39014 23 7.84326V7.60889C23 5.03076 21.125 2.87451 18.6406 2.45264C16.9531 2.17139 15.2656 2.73389 14.0938 3.90576L12.4531 5.54639Z" fill="#FBF9FD" fill-opacity="0.32"/>
                                   </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
            </div>
              `;
                        $('#event').append(eventHtml);
                    });
                    setTimeout(initializeSwiper2, 1000);


                }
            });
        }

        function dateFormat(date) {
            let dateObject = new Date(date);
            let dayOptions = {
                day: 'numeric'
            };
            let day = dateObject.toLocaleDateString('en-US', dayOptions);
            let monthOptions = {
                month: 'long'
            };
            let month = dateObject.toLocaleDateString('en-US', monthOptions);
            let shortMonth = month.substring(0, 3);

            return {
                day,
                shortMonth
            }
        }
    });
</script>