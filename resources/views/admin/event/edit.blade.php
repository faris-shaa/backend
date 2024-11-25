@extends('master')

@section('content')
    <section class="section">
        @include('admin.layout.breadcrumbs', [
            'title' => __('Edit Event'),
            'headerData' => __('Event'),
            'url' => 'events',
        ])

        <div class="section-body">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="section-title"> {{ __('Edit Event') }}</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" class="event-form" action="{{ route('events.update', [$event->id]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group center">
                                            <label>{{ __('Image') }}</label>
                                            <div id="image-preview" class="image-preview"
                                                style="background-image: url({{ url('images/upload/' . $event->image) }})">
                                                <label for="image-upload" id="image-label"> <i
                                                        class="fas fa-plus"></i></label>
                                                <input type="file" name="image" id="image-upload" />
                                            </div>
                                            @error('image')
                                                <div class="invalid-feedback block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Name In English') }}</label>
                                            <input type="text" name="name" value="{{ $event->name }}"
                                                placeholder="{{ __('Name') }}"
                                                class="form-control @error('name')? is-invalid @enderror">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                         <div class="form-group">
                                            <label>{{ __('Name In Arabic') }}</label>
                                            <input type="text" name="name_arabic" value="{{ $event->name_arabic }}"
                                                placeholder="{{ __('Name Arabic') }}"
                                                class="form-control @error('name_arabic')? is-invalid @enderror">
                                            @error('name_arabic')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Category') }}</label>
                                            <select name="category_id" class="form-control select2">
                                                <option value="">{{ __('Select Category') }}</option>
                                                @foreach ($category as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $item->id == $event->category_id ? 'Selected' : '' }}>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Show On Dashboard') }}</label>
                                            <select name="is_show_dashboard" class="form-control select2" >
                                                <option value="0" @if( $event->is_show_dashboard == 0 )selected="selected" @endif>{{ __('No') }}</option>
                                                <option value="1" @if( $event->is_show_dashboard == 1 )selected="selected" @endif>{{ __('Yes') }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Show Start Price') }}</label>
                                            <select name="is_start_price" class="form-control select2" >
                                                <option value="0" @if( $event->is_start_price == 0 )selected="selected" @endif>{{ __('No') }}</option>
                                                <option value="1" @if( $event->is_start_price == 1 )selected="selected" @endif>{{ __('Yes') }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Is Repeat') }}</label>
                                            <select name="is_repeat" class="form-control select2" >
                                                <option value="0" @if( $event->is_repeat == 0 )selected="selected" @endif>{{ __('No') }}</option>
                                                <option value="1" @if( $event->is_repeat == 1 )selected="selected" @endif>{{ __('Yes') }}</option>
                                            </select>
                                        </div>

                                         <div class="form-group">
                                            <label>{{ __('Is One Day Event') }}</label>
                                            <select name="is_one_day" class="form-control select2" id="oneday" onchange="toggleDiv()">
                                                <option value="0" @if( $event->is_one_day == 0 )selected="selected" @endif>{{ __('No') }}</option>
                                                <option value="1" @if( $event->is_one_day == 1 )selected="selected" @endif>{{ __('Yes') }}</option>
                                                 
                                               
                                            </select>
                                           
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Is Private') }}</label>
                                            <select name="is_private" class="form-control select2" id="is_private" onchange="toggleEmailFields()">
                                                <option value="1" @if( $event->is_private == 1 )selected="selected" @endif>{{ __('Yes') }}</option>
                                                <option value="0" @if( $event->is_private == 0 )selected="selected" @endif>{{ __('No') }}</option> 
                                            </select> 
                                        </div>

                                        <div id="email-fields" @if( $event->is_private == 0 ) style="display: none; @endif">
                                            <div id="email-container">
                                                @if(count($event_emails) > 0)
                                                    @foreach($event_emails as $key => $value)
                                                    <div class="form-group">
                                                        <label>{{ __('Email') }}</label>
                                                        <input type="email" name="emails[]" placeholder="{{ __('Email Address') }}" value="{{$value->email}}" class="form-control">
                                                    </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <button type="button" id="add-email" class="btn btn-secondary">{{ __('Add More Emails') }}</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Start Time') }}</label>
                                            <input type="text" name="start_time" id="start_time"
                                                value="{{ $event->start_time }}"
                                                placeholder="{{ __('Choose Start time') }}"
                                                class="form-control date @error('start_time')? is-invalid @enderror">
                                            @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                     
                                        <div class="form-group" id="endtime"   @if( $event->is_one_day == 1 )style="display: none" @endif >
                                            <label>{{ __('End Time') }}</label>
                                            <input type="text" name="end_time" id="end_time"
                                                value="{{ $event->end_time }}" placeholder="{{ __('Choose End time') }}"
                                                class="form-control date @error('end_time')? is-invalid @enderror">
                                            @error('end_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    
                                    </div>
                                </div>
                                @if (Auth::user()->hasRole('admin'))
                                    <div class="form-group">
                                        <label>{{ __('Organizer') }}</label>
                                        <select name="user_id" class="form-control select2" id="org-for-event">
                                            <option value="">{{ __('Choose Organizer') }}</option>
                                            @foreach ($users as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == $event->user_id ? 'Selected' : '' }}>
                                                    {{ $item->first_name . ' ' . $item->last_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                                <div class="scanner {{ $event->type == 'online' ? 'hide' : 'demo' }}">
                                    <div class="form-group">
                                        <label>{{ __('Scanner') }} {{ __('(Requierd)') }}</label>
                                        <select name="scanner_id[]" class="form-control scanner_id select2 selectpicker"
                                            multiple data-live-search="true">
                                            <option value="" disabled>{{ __('Choose Scanner') }}</option>
                                            @foreach ($scanner as $item)
                                                <option value="{{ $item->id }}"
                                                    @if (str_contains($event->scanner_id, $item->id)) @if (preg_match("/\b$item->id\b/", $event->scanner_id))
                                                            selected @endif
                                                    @endif
                                                    >
                                                    {{ $item->first_name . ' ' . $item->last_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('scanner_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Maximum people will join in this event') }}</label>
                                            <input type="number" name="people" id="people"
                                                value="{{ $event->people }}"
                                                placeholder="{{ __('Maximum people will join in this event') }}"
                                                class="form-control @error('people')? is-invalid @enderror">
                                            @error('people')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('status') }}</label>
                                            <select name="status" class="form-control select2">
                                                <option value="1" {{ $event->status == '1' ? 'selected' : '' }}>
                                                    {{ __('Active') }}</option>
                                                <option value="0" {{ $event->status == '0' ? 'Selected' : '' }}>
                                                    {{ __('Inactive') }}</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Tags') }}</label>
                                    <input type="text" name="tags" value="{{ $event->tags }}"
                                        class="form-control inputtags @error('tags')? is-invalid @enderror">
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Description In English') }}</label>
                                    <textarea name="description" Placeholder="{{ __('Description') }}"
                                        class="textarea_editor @error('description')? is-invalid @enderror">
                                {{ $event->description }}
                            </textarea>
                                    @error('description')
                                        <div class="invalid-feedback block">{{ $message }}</div>
                                    @enderror
                                </div>

                                 <div class="form-group">
                                    <label>{{ __('Description In Arabic') }}</label>
                                    <textarea name="description_arabic" Placeholder="{{ __('Description Arabic') }}"
                                        class="textarea_editor @error('description_arabic')? is-invalid @enderror">
                                {{ $event->description_arabic }}
                            </textarea>
                                    @error('description_arabic')
                                        <div class="invalid-feedback block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <h6 class="text-muted mt-4 mb-4">{{ __('Location Detail') }}</h6>
                                <div class="form-group">
                                    <div class="selectgroup">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type"
                                                {{ $event->type == 'offline' ? 'checked' : '' }} checked value="offline"
                                                class="selectgroup-input" checked="">
                                            <span class="selectgroup-button">{{ __('Venue') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" {{ $event->type == 'online' ? 'checked' : '' }}
                                                name="type" value="online" class="selectgroup-input">
                                            <span class="selectgroup-button">{{ __('Online Event') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="location-detail {{ $event->type == 'online' ? 'hide' : '' }}">
                                    <div class="form-group">
                                        <label>{{ __('Event Address') }}</label>
                                        <input type="text" name="address" id="address"
                                            value="{{ $event->address }}" placeholder="{{ __('Event Address') }}"
                                            class="form-control @error('address')? is-invalid @enderror">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                     <div class="form-group">
                                        <label>{{ __('Event Address Url') }}</label>
                                        <input type="text" name="address_url" id="address_url"
                                            value="{{ $event->address_url }}" placeholder="{{ __('Event Address Url') }}"
                                            class="form-control @error('address_url')? is-invalid @enderror" onchange="callMap()">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Latitude') }}</label>
                                                <input type="text" name="lat" id="lat"
                                                    value="{{ $event->lat }}" placeholder="{{ __('Latitude') }}"
                                                    class="form-control @error('lat')? is-invalid @enderror">
                                                @error('lat')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Longitude') }}</label>
                                                <input type="text" name="lang" id="lang"
                                                    value="{{ $event->lang }}" placeholder="{{ __('Longitude') }}"
                                                    class="form-control @error('lang')? is-invalid @enderror lag">
                                                @error('lang')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="url {{ $event->type == 'offline' ? 'hide' : '' }}">
                                    <div class="form-group">
                                        <label>{{ __('Event url') }}</label>
                                        <input type="link" value="{{ $event->url }}" name="url" id="url"
                                            placeholder="{{ __('Event url') }}"
                                            class="form-control @error('url')? is-invalid @enderror">
                                        @error('url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>
                                </div>
                                 <!-- <div id="map-id" style="width:100%;height:400px;">
                                </div> -->
                                <div class="form-group">
                                    <button type="submit"
                                        class="btn btn-primary demo-button">{{ __('Submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @php
        $gmapkey = App\Models\Setting::find(1)->map_key;
    @endphp
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ $gmapkey }}&libraries=places">
    </script>

    <script>
        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var input = document.getElementById('address');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                $('#lat').val(place.geometry['location'].lat());
                $('#lang').val(place.geometry['location'].lng());
            });
        }

        function toggleDiv() {
    let value1 = document.getElementById("oneday").value;
    
    if (value1 === "0") {
        endtime.style.display = "block";
    }
    else {
        endtime.style.display = "none";
    }
 
 
}


/*async function getCoordinatesFromShortLink(url) {
    try {
        const response = await fetch(url, { method: 'HEAD' });
        const redirectUrl = response.url;
        const regex = /@(-?\d+\.\d+),(-?\d+\.\d+)/;
        const match = redirectUrl.match(regex);
        
        if (match) {
            const lat = parseFloat(match[1]);
            const lng = parseFloat(match[2]);
             $('#lat').val(lat)
            $('.lag').val(lng)
   //      initMap() ;
            return { lat, lng };
        } else {
            throw new Error("Latitude and longitude not found.");
        }
    } catch (error) {
        console.error(error.message);
    }
}*/

//const shortUrl =  $('#address_url').val();

//getCoordinatesFromShortLink(shortUrl);

function callMap()
{
    const url = $('#address_url').val();

    try {
       // const { lat, lng } = getCoordinatesFromShortLink(url);
        const { lat, lng } = getLatLngFromURL(url);
        initMap() ;
        console.log(`Latitude: ${lat}, Longitude: ${lng}`);
    } catch (error) {
        console.error(error.message);
    }
}

function getLatLngFromURL(url) {
    const regex = /@(-?\d+\.\d+),(-?\d+\.\d+)/;
    const match = url.match(regex);
    
    if (match) {
        const lat = parseFloat(match[1]);
        const lng = parseFloat(match[2]);
        $('#lat').val(lat)
        $('.lag').val(lng)
        return { lat, lng };
    } else {
        throw new Error("Latitude and longitude not found in the URL.");
    }
}



/*let map;
        let marker;

        function initMap() {
            // Initialize map 24.529969, 46.796223
            var lat = parseFloat($('#lat').val());
            var lag = parseFloat($('.lag').val());
           console.log( "map " ,  lat ,  lag);
            map = new google.maps.Map(document.getElementById('map-id'), {
                center: { lat: lat, lng: lag },
                zoom: 8
            });

            // Initialize marker
            marker = new google.maps.Marker({
                position: { lat:lat, lng:lag },
                map: map,
                draggable: true,
                title: 'Drag me!'
            });

            // Initialize geocoder
            geocoder = new google.maps.Geocoder();
              // Add event listener for marker drag end
            google.maps.event.addListener(marker, 'dragend', function(event) {
                const lat = event.latLng.lat();
                const lng = event.latLng.lng();

             

                // Get address from lat and lng
                const latLng = new google.maps.LatLng(lat, lng);
                geocoder.geocode({ 'location': latLng }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        // Update address input
                        const address = results[0].formatted_address;
                        alert(address);
                        document.getElementById('address').value = address;

                        // Generate map link
                        const mapLink = `https://www.google.com/maps/?q=${lat},${lng}`;
                        document.getElementById('address_url').value = mapLink;
                        document.getElementById('address').value = address;
                        console.log('Address:', address);
                        console.log('Map Link:', mapLink);
                    } else {
                        console.error('Geocoder failed due to:', status);
                    }
                });
            });

            // Add event listener for marker drag end
            google.maps.event.addListener(marker, 'dragend', function(event) {
               console.log( event.latLng.lat() , event.latLng.lng());
                 $('#lat').val(event.latLng.lat());
                $('.lng').val(event.latLng.lng());
                
            });
        }*/
    </script>
    <style>
        .modal-backdrop {
            display: none;
        }
    </style>


<script>
function toggleEmailFields() {
    var isPrivate = document.getElementById('is_private').value;
    var emailFields = document.getElementById('email-fields');
    
    if (isPrivate == '1') {
        emailFields.style.display = 'block';
    } else {
        emailFields.style.display = 'none';
    }
}

// Add new email input field
document.getElementById('add-email').addEventListener('click', function() {
    var emailContainer = document.getElementById('email-container');
    var newEmailInput = document.createElement('div');
    newEmailInput.classList.add('form-group');
    newEmailInput.innerHTML = '<label>{{ __("Email") }}</label>' +
                              '<input type="email" name="emails[]" placeholder="{{ __("Email Address") }}" class="form-control"><br>' +
                              '<button type="button" class="btn btn-danger btn-sm remove-email">{{ __("Remove") }}</button>';
    emailContainer.appendChild(newEmailInput);
});

// Remove email input field
document.getElementById('email-container').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-email')) {
        e.target.parentElement.remove();
    }
});
</script>
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $gmapkey }}&callback=initMap"></script> -->
@endsection
