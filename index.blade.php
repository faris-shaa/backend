@extends('master')

@section('content')

    <section class="section">
        @include('admin.layout.breadcrumbs', [
            'title' => __('Events'),
        ])

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-4 mt-2">
                                <div class="col-lg-8">
                                    <h2 class="section-title mt-0"> {{ __('All Events') }}</h2>
                                </div>
                                <div class="col-lg-4 text-right">
                                    @can('event_create')
                                        <button class="btn btn-primary add-button"><a href="{{ url('event/create') }}"><i
                                                    class="fas fa-plus"></i> {{ __('Add New') }}</a></button>
                                    @endcan
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table" id="report_table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>{{ __('Image') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Start Date') }}</th>
                                            <th>{{ __('Number of People') }}</th>
                                            <th>{{ __('Category') }}</th>
                                            @if (Auth::user()->hasRole('admin'))
                                                <th>{{ __('Organization') }}</th>
                                            @endif
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Order') }}</th>
                                            @if (Gate::check('event_edit') || Gate::check('event_delete'))
                                                <th>{{ __('Action') }}</th>
                                            @endif
                                            @if (Gate::check('ticket_access'))
                                                <th>{{ __('Tickets') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($events as $item)
                                            <tr>
                                                <td></td>
                                                <th> <img class="table-img"
                                                        src="{{ url('images/upload/' . $item->image) }}">
                                                </th>
                                                <td>
                                                    <h6 class="mb-0">{{ $item->name }}</h6>
                                                </td>
                                                <td>
                                                    <p class="mb-0">
                                                        {{ Carbon\Carbon::parse($item->start_time)->format('Y-m-d h:i a') . ', ' . $item->start_time->format('l') }}
                                                    </p>
                                                </td>
                                                <td>{{ $item->people }}</td>
                                                <td>@if(isset($item->category->name)) {{ __($item->category->name) }}   @else -  @endif </td>
                                                @if (Auth::user()->hasRole('admin'))
                                                    <td>{{ $item->organization->first_name . ' ' . $item->organization->last_name }}
                                                    </td>
                                                @endif
                                                <td>
                                                    <h5><span
                                                            class="badge {{ $item->status == '1' ? 'badge-success' : 'badge-warning' }}  m-1">{{ $item->status == '1' ? 'Publish' : 'Draft' }}</span>
                                                    </h5>
                                                </td>
                                                <td>
                                                    <select class="mySelect" data-id="{{$item->id}}" >
                                                        <option   value="0">{{ __('Remove') }}</option>
                                                        <option @if( $item->orderby == 1) selected="selected" @endif value="1">1</option>
                                                        <option @if( $item->orderby == 2) selected="selected" @endif value="2">2</option>
                                                        <option @if( $item->orderby == 3) selected="selected" @endif value="3">3</option>
                                                    </select>   
                                                </td>
                                                @if (Gate::check('event_edit') || Gate::check('event_delete'))
                                                    <td class="dropdown-parent">
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                        class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                                        {{ __('Action') }}
                                                        <div class="d-sm-none d-lg-inline-block"></div>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        
                                                            <a href="{{ url('/events_details', $item->id) }}" title="View Event" class="dropdown-item has-icon">
                                                            <i class="fas fa-eye"></i>
                                                            
                                                            </a>
                                                            <a href="{{ url('event-gallery/' . $item->id) }}" title="Event Gallery" class="dropdown-item has-icon">
                                                                <i class="far fa-images"></i>
                                                                Edit Gallery
                                                            </a>
                                                            @can('event_edit')
                                                            <a href="{{ route('events.edit', $item->id) }}" title="Event Event" class="dropdown-item has-icon">
                                                                <i class="fas fa-edit"></i>
                                                                {{ __('Edit') }}
                                                            </a>
                                                          
                                                                @endcan
                                                                @can('event_delete')
                                                            <a href="#" onclick="deleteData('events','{{ $item->id }}');" title="Delete Event"  class="dropdown-item has-icon">
                                                            <i class="fas fa-trash-alt "></i> 
                                                            {{ __('Delete') }}
                                                            </a>
                                                        @endcan
                                                        
                                                    </div>
                                                </div>
                                                </td>
                                                @endif
                                                @if (Gate::check('ticket_access'))
                                                    <td>
                                                        <a href="{{ url($item->id . '/' . Str::slug($item->name) . '/tickets') }}"
                                                            class=" btn btn-primary">{{ __('Manage Tickets') }}</a>
                                                            <a href="{{ url('/event/duplicate?id='.$item->id) }}"
                                                            class=" btn btn-primary" style="margin-top:10px;">{{ __('Duplicate') }}</a>   
                                                    </td>
                                                    
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
 <script>
        $(document).ready(function() {
            $('.mySelect').on('change', function() {
                 var selectedValue = $(this).val();
                var dataId = $(this).data('id');
                console.log(selectedValue  ,dataId  , "test")
                
                $.ajax({  
                    url: 'change/event/order',
                    type: 'GET',
                    data: { orderby: selectedValue , id : dataId },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Request failed:', error);
                    }
                });
            });
        });
    </script>
@endsection
