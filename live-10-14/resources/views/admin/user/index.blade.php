@extends('master')

@section('content')
    <section class="section">
        @include('admin.layout.breadcrumbs', [
            'title' => __('Users'),
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
                    @if (session('statusblock'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('statusblock') }}
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
                                    <h2 class="section-title mt-0"> {{ __('View Users') }}</h2>
                                </div>
                                <div class="col-lg-4 text-right">
                                    @can('user_create')
                                        <button class="btn btn-primary add-button"><a href="{{ url('users/create') }}"><i
                                                    class="fas fa-plus"></i> {{ __('Add New') }}</a></button>
                                    @endcan
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table" id="report_table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>{{ __('User') }}</th>
                                            <th>{{ __('First Name') }}</th>
                                            <th>{{ __('Last Name') }}</th>
                                            <th>{{ __('Phone') }}</th>
                                            <th>{{ __('Role') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{__('Verified')}}</th>
                                            @if ($debugMode == true  && Auth::user()->hasRole('admin'))
                                                <th>{{__('Login')}}</th>
                                            @endif
                                            @if (Gate::check('user_edit') || Gate::check('user_delete'))
                                                <th>{{ __('Action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $item)
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <div class="media">
                                                        <img alt="image" class="mr-3 avatar"
                                                            src="{{ url('images/upload/' . $item->image) }}">
                                                        <div class="media-body">
                                                            <div class="media-title mb-0">
                                                                {{ $item->first_name . ' ' . $item->last_name }}
                                                            </div>
                                                            <div class="media-description text-muted"> {{ $item->email }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{$item->first_name}}</td>
                                                <td>{{$item->last_name}}</td>

                                                <td>{{ $item->phone }}</td>
                                                <td>
                                                    @forelse ($item->roles as $roles)
                                                        <span class="badge badge-primary  m-1">{{ $roles->name }}</span>
                                                    @empty
                                                        <span class="badge badge-warning  m-1">{{ __('No Data') }}</span>
                                                    @endforelse
                                                </td>
                                                <td>
                                                    <h5><span
                                                            class="badge {{ $item->status == '1' ? 'badge-success' : 'badge-danger' }}  m-1">{{ $item->status == '1' ? 'Active' : 'Block' }}</span>
                                                    </h5>
                                                </td>
                                                <td>
                                                    <h5><span
                                                        class="badge {{ $item->is_verify == '1' ? 'badge-success' : 'badge-danger' }}  m-1">{{ $item->is_verify == '1' ? 'Verified' : 'Unverified' }}</span>
                                                </h5>
                                                </td>
                                                @if ($debugMode == true  && Auth::user()->hasRole('admin'))
                                                    <td>
                                                        @if ($item->hasRole('Organizer'))
                                                            <a href="{{route('loginAsOrganizer',$item->id)}}" class="btn btn-primary">
                                                                {{__('Login As')}}
                                                            </a>
                                                        @endif
                                                    </td>
                                                @endif
                                                @if(Auth::user()->email == "info@wasltec.com" )
                                                <td class="dropdown-parent">
                                                <div class="dropdown"><a href="#" data-toggle="dropdown"
                                                        class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                                        {{ __('Action') }}
                                                        <div class="d-sm-none d-lg-inline-block"></div>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('users.edit', $item->id) }}" title="Event Event" class="dropdown-item has-icon">
                                                        <i class="fas fa-edit"></i>
                                                        {{ __('Edit') }}
                                                    </a>
                                                   <a href="#" onclick="deleteData('user','{{ $item->id }}');" title="Delete User"  class="dropdown-item has-icon">
                                                            <i class="fas fa-trash-alt "></i> 
                                                            {{ __('Delete') }}
                                                            </a>
                                                    @if ($item->hasRole('Organizer') && $item->is_organizer_approve == 0 )
                                                        <a href="{{ route('users.approve', $item->id) }}" title="Event Event" class="dropdown-item has-icon">
                                                        <i class="fa fa-check" aria-hidden="true"></i>

                                                            {{ __('Approve') }}
                                                        </a>
                                                        @endif
                                                             
                                                            @if($item->email != "info@wasltec.com")
                                                            @if ($item->status == 1)
                                                            <a onclick="return confirm('Blocked person will not be able to Login, They will stay hidden from the public, including their events & bookings.\nAre you sure to block?')"
                                                                    href="{{ url('main_user_block/' . $item->id) }}" class="dropdown-item has-icon">
                                                            <i class="fas fa-ban"></i> 
                                                            {{ __('Block') }}
                                                            </a>
                                                                <!-- <a onclick="return confirm('Blocked person will not be able to Login, They will stay hidden from the public, including their events & bookings.\nAre you sure to block?')"
                                                                    href="{{ url('main_user_block/' . $item->id) }}"
                                                                    class="btn-icon text-danger"><i
                                                                        class="fas fa-ban text-danger" data-toggle="tooltip"
                                                                        data-placement="top" title="Block"></i></a> -->
                                                            @else
                                                            <a onclick="return confirm('Are you sure to Unblock!!')"
                                                                    href="{{ url('main_user_block/' . $item->id) }}" class="dropdown-item has-icon">
                                                            <i class="fa fa-unlock-alt"></i> 
                                                            {{ __('Unblock') }}
                                                            </a>
                                                                <!-- <a onclick="return confirm('Are you sure to Unblock!!')"
                                                                    href="{{ url('main_user_block/' . $item->id) }}"
                                                                    class="btn-icon text-danger"><i
                                                                        class="fa fa-unlock-alt text-danger"
                                                                        aria-hidden="true" data-toggle="tooltip"
                                                                        data-placement="top" title="Unblock"></i>
                                                                </a> -->
                                                            @endif
                                                            @endif
                                                            </div>
                                                            </div>
                                                </td>
                                                @else
                                
                                                @if (Gate::check('user_edit') || Gate::check('user_delete') || Auth::user()->email == "info@wasltec.com")
                                                <td class="dropdown-parent">
                                                <div class="dropdown"><a href="#" data-toggle="dropdown"
                                                        class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                                        Action
                                                        <div class="d-sm-none d-lg-inline-block"></div>
                                                        </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if (!$item->hasRole('admin') && Auth::user()->email == "info@wasltec.com")
                                                            @can('user_edit')
                                                                <a href="{{ route('users.edit', $item->id) }}"
                                                                    class="btn-icon"><i class="fas fa-edit"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="Edit"></i></a>
                                                            @endcan
                                                            @if ($item->status == 1)
                                                                <a onclick="return confirm('Blocked person will not be able to Login, They will stay hidden from the public, including their events & bookings.\nAre you sure to block?')"
                                                                    href="{{ url('main_user_block/' . $item->id) }}"
                                                                    class="btn-icon text-danger"><i
                                                                        class="fas fa-ban text-danger" data-toggle="tooltip"
                                                                        data-placement="top" title="Block"></i></a>
                                                            @else
                                                                <a onclick="return confirm('Are you sure to Unblock!!')"
                                                                    href="{{ url('main_user_block/' . $item->id) }}"
                                                                    class="btn-icon text-danger"><i
                                                                        class="fa fa-unlock-alt text-danger"
                                                                        aria-hidden="true" data-toggle="tooltip"
                                                                        data-placement="top" title="Unblock"></i>
                                                                </a>
                                                            @endif
                                                        @endif
                                                        </div>
                                                        </div>
                                                    </td>
                                                    @endif
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
@endsection
