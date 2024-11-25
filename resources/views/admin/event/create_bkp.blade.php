@extends('master')

@section('content')
 @php
        $gmapkey = App\Models\Setting::find(1)->map_key;
        $empty_array = [] ; 
        $endTime = old('scanner_id', $empty_array); 
        $user_id = old('user_id', 'null'); 
    @endphp
<script type="text/javascript">
    $(document).ready(function () {
        var scanner_ids =  @json($endTime);

        // Function to populate scanner options
    function populateScanners(data) {
        var $scannerSelect = $('.scanner_id');

        $scannerSelect.html('<option value="">Choose Scanner</option>');
   
        // Loop through the data and add options
        data.forEach(e => {
             var selected =  scanner_ids.includes(e.id) ? ' selected="selected"' : '';
            scanner_ids.forEach(i => {
                if(i == e.id)
                {
                   selected =  ' selected="selected"';
                }
                
            })
           
            console.log(selected, "selected" );
            $scannerSelect.append('<option value="' + e.id + '" ' + selected + ' >' + e.first_name + ' ' + e.last_name + '</option>');
        });
    }
    // Trigger change event on page load to populate the select box initially
    call();
    function call ()
    {

        $.ajax({
            type: "GET",
            url: base_url + '/getScanner/' + $('#org-for-change').val(),
            success: function (result) {
                populateScanners(result.data);
            },
            error: function (err) {
                console.log('Error: ', err);
            }
        });
    }
    $('#org-for-change').trigger('change');
    
    // Change event for organization selection
    $('#org-for-change').change(function () {
        call();
       /* console.log($('#org-for-change').val() , "user_iiids");
        $.ajax({
            type: "GET",
            url: base_url + '/getScanner/' + $(this).val(),
            success: function (result) {
                populateScanners(result.data);
            },
            error: function (err) {
                console.log('Error: ', err);
            }
        });*/
    });

    });

</script>
    <section class="section">
        @include('admin.layout.breadcrumbs', [
            'title' => __('Add Event'),
            'headerData' => __('Event'),
            'url' => 'events',
        ])

        <div class="section-body">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="section-title"> {{ __('Add Event') }}</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" class="event-form" action="{{ url('events') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group center">
                                            <label>{{ __('Image') }} {{ __('Upload a squar image for better fit')}}</label>
                                            <div id="image-preview" class="image-preview">
                                                <label for="image-upload" id="image-label"> <i
                                                        class="fas fa-plus"></i></label>
                                                <input type="file" name="image" id="image-upload" />
                                            </div>
                                            @if ($errors->any())
                                                @foreach ($errors->all() as $error)
                                                <div class="invalid-feedback block">{{$error}}</div>
                                                @endforeach
                                            @endif
                                            @error('image')
                                                <div class="invalid-feedback block">Image upload failed. Please check size is smaller then 2MB.</div>
                                            @enderror
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Name In English') }}</label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                placeholder="{{ __('Name') }}"
                                                class="form-control @error('name')? is-invalid @enderror">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                         <div class="form-group">
                                            <label>{{ __('Name In Arabic') }}</label>
                                            <input type="text" name="name_arabic" value="{{ old('name_arabic') }}"
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
                                                        {{ $item->id == old('category_id') ? 'Selected' : '' }}>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Show On Dashboard') }}</label>
                                            <select name="is_show_dashboard" class="form-control select2">
                                                <option value="1">{{ __('Yes') }}</option>
                                                 <option value="0">{{ __('No') }}</option>
                                               
                                            </select>
                                           
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Show Start Price') }}</label>
                                            <select name="is_start_price" class="form-control select2">
                                                <option value="1">{{ __('Yes') }}</option>
                                                 <option value="0">{{ __('No') }}</option>
                                               
                                            </select>
                                           
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Is One Day Event') }}</label>
                                            <select name="is_one_day" class="form-control select2" id="oneday" onchange="toggleDiv()">
                                                <option value="1">{{ __('Yes') }}</option>
                                                 <option value="0">{{ __('No') }}</option>
                                               
                                            </select>
                                           
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Start Time') }}</label>
                                            <input type="text" name="start_time" id="start_time"
                                                value="{{ old('start_time') }}"
                                                placeholder="{{ __('Choose Start time') }}"
                                                class="form-control date @error('start_time')? is-invalid @enderror">
                                            @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group" id="endtime" style="display: none" >
                                            <label>{{ __('End Time') }}</label>
                                            <input type="text" name="end_time" id="end_time"
                                                value="{{ old('end_time') }}" placeholder="{{ __('Choose End time') }}"
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
                                        <select name="user_id" required class="form-control select2" id="org-for-change">
                                            <option value="">{{ __('Choose Organizer') }}</option>
                                            @foreach ($users as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == old('user_id') ? 'Selected' : '' }}>
                                                    {{ $item->first_name . ' ' . $item->last_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                                <div class="scanner">
                                <div class="form-group">
                                    <label>{{ __('Scanner') }} {{ __('(Requierd)')}} {{__('(Choose Multiple if required.)')}}</label>
                                    <select name="scanner_id[]"  class="form-control scanner_id select2 selectpicker" multiple data-live-search="true">
                                        <option value="" disabled>{{ __('Choose Scanner') }}</option>
                                        @foreach ($scanner as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->id == old('scanner_id') ? 'Selected' : '' }}>
                                                {{ $item->first_name . ' ' . $item->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('scanner_id[]')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Maximum people will join in this event') }}</label>
                                            <input type="number" min='1' name="people" id="people"
                                                value="{{ old('people') }}"
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
                                                <option value="1">{{ __('Active') }}</option>
                                                <option value="0">{{ __('Inactive') }}</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Tags') }}</label>
                                    <input type="text" name="tags" value="{{ old('tags') }}"
                                        class="form-control inputtags @error('tags')? is-invalid @enderror">
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Description In English') }}</label>
                                    <textarea name="description" Placeholder="Description"
                                        class="textarea_editor @error('description')? is-invalid @enderror">
                                {{ old('description') }}
                            </textarea>
                                    @error('description')
                                        <div class="invalid-feedback block">{{ $message }}</div>
                                    @enderror
                                </div>

                                 <div class="form-group">
                                    <label>{{ __('Description In Arabic') }}</label>
                                    <textarea name="description_arabic" Placeholder="Description Arabic"
                                        class="textarea_editor @error('description_arabic')? is-invalid @enderror">
                                {{ old('description_arabic') }}
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
                                                {{ old('type') == 'online' ? '' : 'checked' }} checked value="offline"
                                                class="selectgroup-input" checked="">
                                            <span class="selectgroup-button">{{ __('Venue') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" {{ old('type') == 'online' ? 'checked' : '' }}
                                                name="type" value="online" class="selectgroup-input">
                                            <span class="selectgroup-button">{{ __('Online Event') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="location-detail {{ old('type') == 'online' ? 'hide' : '' }}">
                                    <div class="form-group">
                                        <label>{{ __('Event Address') }}</label>
                                        <input type="text" name="address" id="address"
                                         value="{{ old('address') }}"
                                            placeholder="{{ __('Event Address') }}"
                                            class="form-control @error('address')? is-invalid @enderror">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Event Address Url') }}</label>
                                        <input type="text" name="address_url" id="address_url"
                                            placeholder="{{ __('Event Address Url') }}"
                                            value="{{ old('address_url') }}"
                                            class="form-control @error('address_url')? is-invalid @enderror">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Latitude') }}</label>
                                                <input type="text" name="lat" id="lat"
                                                    value="{{ old('lat') }}" placeholder="{{ __('Latitude') }}"
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
                                                    value="{{ old('lang') }}" placeholder="{{ __('Longitude') }}"
                                                    class="form-control @error('lang')? is-invalid @enderror">
                                                @error('lang')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="url hide  {{ old('type') == 'online' ? 'block' : '' }}">
                                    <div class="form-group">
                                        <label>{{ __('Event url') }}</label>
                                        <input type="link" name="url" id="url"
                                            placeholder="{{ __('Event url') }}"
                                            class="form-control @error('url')? is-invalid @enderror">
                                        @error('url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>
                                </div>
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
        <style>
            .modal-backdrop {
               display: none;
            }
        </style>
    </section>


    @php
        $gmapkey = App\Models\Setting::find(1)->map_key;
        $empty_array = [] ; 
        $endTime = old('scanner_id', $empty_array); 
        $user_id = old('user_id', 'null'); 
    @endphp
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{$gmapkey}}&libraries=places">
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
    </script>
@endsection
