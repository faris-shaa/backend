@extends('master')

@section('content')
<script src="{{url('/admin/js/admin-user.js')}}"></script>
<section class="section">
    @include('admin.layout.breadcrumbs', [
        'title' => __('Edit User'),
        'headerData' => __('Users') ,
        'url' => 'users' ,
    ])

    <div class="section-body">
        <div class="row">
            <div class="col-lg-8"><h2 class="section-title"> {{__('Edit User')}}</h2></div>
        </div>
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route("users.update", [$user->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{__('First Name')}}</label>
                                    <input type="text" name="first_name" value="{{$user->first_name}}" class="form-control @error('first_name')? is-invalid @enderror">
                                    @error('first_name')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{__('Last Name')}}</label>
                                    <input type="text" name="last_name" value="{{$user->last_name}}" class="form-control @error('last_name')? is-invalid @enderror">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{__('Email')}}</label>
                            <input type="email"  name="email" value="{{$user->email}}" class="form-control @error('email')? is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>{{__('Phone')}}</label>
                            <input type="text" name="phone" value="{{$user->phone}}" class="form-control @error('phone')? is-invalid @enderror">
                            @error('phone')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>{{__('Type')}}</label>
                            <select name="roles[]" class="form-control select2" id="role">
                                @foreach ($roles as $roles)
                                    <option value="{{$roles['id']}}" {{ (in_array($roles->id, old('roles', [])) || isset($user) && $user->roles->contains($roles->id)) ? 'selected' : '' }}>
                                    {{$roles['name']}}</option>
                                @endforeach
                            </select>
                            @error('roles')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>

                        <div class="row  ">
                         <div class="col-lg-6 @if(!$user->roles->contains(2)) display-none  @endif organizer-class  ">
                            <div class="form-group">
                                    <label>{{__('Arabic Name')}}</label>
                                    <input type="text" name="arabic_name" placeholder="{{__('Arabic Name')}}" value="@if(isset($details->arabic_name)) {{$details->arabic_name}} @endif " class="form-control @error('arabic_name')? is-invalid @enderror">
                                    @error('arabic_name')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @endif
                            </div>
                        </div>

                         <div class="col-lg-6  @if(!$user->roles->contains(2)) display-none  @endif  organizer-class">
                            <div class="form-group">
                                    <label>{{__('Facebook')}}</label>
                                    <input type="text" name="facebook" placeholder="{{__('Facebook')}}" value="@if(isset($details->facebook)) {{$details->facebook}} @endif" class="form-control @error('facebook')? is-invalid @enderror">
                                    @error('facebook')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @endif
                            </div>
                        </div>

                        <div class="col-lg-6  @if(!$user->roles->contains(2)) display-none  @endif  organizer-class ">
                            <div class="form-group">
                                    <label>{{__('Twitter')}}</label>
                                    <input type="text" name="twitter" placeholder="{{__('Twitter')}}" value="@if(isset($details->twitter)) {{$details->twitter}} @endif" class="form-control @error('twitter')? is-invalid @enderror">
                                    @error('twitter')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @endif
                            </div>
                        </div>

                        <div class="col-lg-6  @if(!$user->roles->contains(2)) display-none  @endif  organizer-class ">
                            <div class="form-group">
                                    <label>{{__('Instagram')}}</label>
                                    <input type="text" name="instagram" placeholder="{{__('Instagram')}}" value="@if(isset($details->instagram)) {{$details->instagram}} @endif" class="form-control @error('instagram')? is-invalid @enderror">
                                    @error('instagram')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @endif
                            </div>
                        </div>

                           <div class="col-lg-6  @if(!$user->roles->contains(2)) display-none  @endif  organizer-class ">
                            <div class="form-group">
                                    <label>{{__('Whatsapp')}}</label>
                                    <input type="text" name="whatsapp" placeholder="{{__('Whatsapp')}}" value="{{old('whatsapp')}}" class="form-control @error('whatsapp')? is-invalid @enderror">
                                    @error('whatsapp')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @endif
                            </div>
                        </div>
                          <div class="col-lg-6  @if(!$user->roles->contains(2)) display-none  @endif  organizer-class ">
                        </div>

                          <div class="col-lg-6  @if(!$user->roles->contains(2)) display-none  @endif  organizer-class ">
                            <div class="form-group">
                                    <label>{{ __('Terms In English') }}</label>
                                    <textarea name="terms_english" Placeholder="Terms and Condition"
                                        class="textarea_editor @error('terms_english')? is-invalid @enderror">
                                        {{ old('terms_english') }}
                                    </textarea>
                                    @error('terms_english')
                                        <div class="invalid-feedback block">{{ $message }}</div>
                                    @enderror
                            </div>
                        </div>

                          <div class="col-lg-6  @if(!$user->roles->contains(2)) display-none  @endif  organizer-class ">
                            <div class="form-group">
                                    <label>{{ __('Terms In Arabic') }}</label>
                                    <textarea name="terms_arabic" Placeholder="Terms and Condition"
                                        class="textarea_editor @error('terms_arabic')? is-invalid @enderror">
                                        {{ old('terms_arabic') }}
                                    </textarea>
                                    @error('terms_arabic')
                                        <div class="invalid-feedback block">{{ $message }}</div>
                                    @enderror
                            </div>
                        </div>

                        <div class="col-lg-6  @if(!$user->roles->contains(2)) display-none  @endif  organizer-class">
                            <div class="form-group">
                                    <label>{{ __('Short Description In English') }}</label>
                                    <textarea name="short_description_english" Placeholder="Description"
                                        class="textarea_editor @error('short_description_english')? is-invalid @enderror">
                                        @if(isset($details->short_description_english)) {{$details->short_description_english}} @endif
                                    </textarea>
                                    @error('short_description_english')
                                        <div class="invalid-feedback block">{{ $message }}</div>
                                    @enderror
                            </div>
                        </div>


                        <div class="col-lg-6  @if(!$user->roles->contains(2)) display-none  @endif  organizer-class">
                            <div class="form-group">
                                   <label>{{ __('Short Description In Arabic') }}</label>
                                    <textarea name="short_description_arabic" Placeholder="Description"
                                        class="textarea_editor @error('short_description_arabic')? is-invalid @enderror">
                                        @if(isset($details->short_description_arabic)) {{$details->short_description_arabic}} @endif
                                    </textarea>
                                    @error('short_description_arabic')
                                        <div class="invalid-feedback block">{{ $message }}</div>
                                    @enderror
                            </div>
                        </div>
                     

                        <div class="col-lg-6  @if(!$user->roles->contains(2)) display-none  @endif  organizer-class">
                            <div class="form-group">
                                   <label>{{ __('Long Description In English') }}</label>
                                    <textarea name="long_description_english" Placeholder="Description"
                                        class="textarea_editor @error('long_description_english')? is-invalid @enderror">
                                        @if(isset($details->long_description_english)) {{$details->long_description_english}} @endif
                                    </textarea>
                                    @error('long_description_english')
                                        <div class="invalid-feedback block">{{ $message }}</div>
                                    @enderror
                            </div>
                        </div>

                        <div class="col-lg-6  @if(!$user->roles->contains(2)) display-none  @endif  organizer-class">
                            <div class="form-group">
                                   <label>{{ __('Long Description In Arabic') }}</label>
                                    <textarea name="long_description_arabic" Placeholder="Description"
                                        class="textarea_editor @error('long_description_arabic')? is-invalid @enderror">
                                        {{ old('long_description_arabic') }}
                                    </textarea>
                                    @error('long_description_arabic')
                                        <div class="invalid-feedback block">{{ $message }}</div>
                                    @enderror
                            </div>
                        </div>


                        </div>
                        <div class="form-group {{ $user->org_id == null? 'display-none':'' }}" id="org">
                            <label>{{__('Organization')}}</label>
                            <select name="organization" class="form-control select2">
                                @foreach ($orgs as $org)
                                    <option value="{{$org['id']}}" {{$user->org_id == $org['id']? 'selected':''}}>{{$org['first_name'] .' '. $org['last_name']}}</option>
                                @endforeach
                            </select>
                            @error('organization')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary demo-button">{{__('Submit')}}</button>

                        </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>
@endsection
