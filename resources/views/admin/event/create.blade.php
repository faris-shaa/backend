@extends('master')

@section('content')
 @php
        $gmapkey = App\Models\Setting::find(1)->map_key;
        $empty_array = [] ; 
        $endTime = old('scanner_id', $empty_array); 
        $user_id = old('user_id', 'null'); 
    @endphp

    @if (Auth::user()->hasRole('admin'))
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

@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
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
                                            <label>{{ __('Is Repeat') }}</label>
                                            <select name="is_repeat" class="form-control select2" id="is_repeat">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option value="0" selected>{{ __('No') }}</option> 
                                            </select> 
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Is Private') }}</label>
                                            <select name="is_private" class="form-control select2" id="is_private" onchange="toggleEmailFields()">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option value="0" selected>{{ __('No') }}</option> 
                                            </select> 
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Is One Day Event') }}</label>
                                            <select name="is_one_day" class="form-control select2" id="oneday" onchange="toggleDiv()">
                                                <option value="1">{{ __('Yes') }}</option>
                                                 <option value="0">{{ __('No') }}</option>
                                               
                                            </select>
                                           
                                        </div>
                                        

                                        <div id="email-fields" style="display: none;">
                                            <div id="email-container">
                                                <div class="form-group">
                                                    <label>{{ __('Email') }}</label>
                                                    <input type="email" name="emails[]" placeholder="{{ __('Email Address') }}" class="form-control">
                                                </div>
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

                                <div class="form-group">
                                    <label>{{ __('Terms & Condition In English') }}</label>
                                    <textarea name="terms_and_condition" Placeholder="Terms & Condition"
                                        class="textarea_editor @error('terms_and_condition')? is-invalid @enderror">
                                {{ old('terms_and_condition') }}
                            </textarea>
                                    @error('terms_and_condition')
                                        <div class="invalid-feedback block">{{ $message }}</div>
                                    @enderror
                                </div>

                                 <div class="form-group">
                                    <label>{{ __('Terms & Condition In Arabic') }}</label>
                                    <textarea name="terms_and_condition_arabic" Placeholder="Terms & Condition Arabic"
                                        class="textarea_editor @error('terms_and_condition_arabic')? is-invalid @enderror">
                                {{ old('terms_and_condition_arabic') }}
                            </textarea>
                                    @error('terms_and_condition_arabic')
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
                                            class="form-control @error('address_url')? is-invalid @enderror " onchange="callMap()">
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
                                                    class="form-control lng @error('lang')? is-invalid @enderror lag">
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
                               <!--  <div id="map-id" style="width:100%;height:400px;">
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
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Image Cropper</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="img-container">
            <div class="row">
                <div class="col-md-8">
                    <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                </div>
                <div class="col-md-4">
                    <div class="preview"></div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="crop">Crop</button>
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
           var $modal = $('#modal');
var image = document.getElementById('image');
var cropper;

$("body").on("change", ".image", function(e){
    var files = e.target.files;
    var done = function (url) {
        
      image.src = url;
      $modal.modal('show');
    };
    var reader;
    var file;
    var url;
    if (files && files.length > 0) {
      file = files[0];
      if (URL) {
        done(URL.createObjectURL(file));
      } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function (e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
});

$modal.on('shown.bs.modal', function () {
    cropper = new Cropper(image, {
      aspectRatio: 1.4,
      viewMode: 1,
      preview: '.preview'
    });
}).on('hidden.bs.modal', function () {
   cropper.destroy();
   cropper = null;
});

$("#crop").click(function(){
    var canvas = cropper.getCroppedCanvas({
        width: 1000,
        height: 800,
    });
    canvas.toBlob(function(blob) {
        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function() {
            var base64data = reader.result;
             // Set the base64 data to the hidden input field
            // Example Base64 text string (this is a Base64-encoded "Hello, World!")
            console.log(base64data , "base64data");
            const objectURL = URL.createObjectURL(blob);
            const img = document.createElement('img');
            img.src = objectURL;

            // Get the div element
            const imagePreviewDiv = document.getElementById('image-preview');
            $('#cropped_image').val(base64data);
            // Append the img to the div
            imagePreviewDiv.appendChild(img);
            $modal.modal('hide');
            
        }
    });
});



        /*google.maps.event.addDomListener(window, 'load', initialize);*/

        /*function initialize() {
            var input = document.getElementById('address');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                $('#lat').val(place.geometry['location'].lat());
                $('#lang').val(place.geometry['location'].lng());
            });
        }*/
          function toggleDiv() {
    let value1 = document.getElementById("oneday").value;
    
    if (value1 === "0") {
        endtime.style.display = "block";
    }
    else {
        endtime.style.display = "none";
    }
 
}


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


// gogole map code 
/*let map;
        let marker;

        function initMap() {
            // Initialize map 24.529969, 46.796223
            map = new google.maps.Map(document.getElementById('map-id'), {
                center: { lat: 24.529969, lng: 46.796223 },
                zoom: 8
            });

            // Initialize marker
            marker = new google.maps.Marker({
                position: { lat: 24.529969, lng: 46.796223 },
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
                        document.getElementById('address').value = address;

                        // Generate map link
                        const mapLink = `https://www.google.com/maps/?q=${lat},${lng}`;
                        document.getElementById('address_url').value = mapLink;

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

<!--      <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $gmapkey }}&callback=initMap"></script> -->
@endsection
