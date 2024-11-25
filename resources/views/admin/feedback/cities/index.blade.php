@extends('master')

@section('content')
    <section class="section">
        @include('admin.layout.breadcrumbs', [
            'title' => __('Cities'),
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
                                    <h2 class="section-title mt-0"> {{ __('View Cities') }}</h2>
                                </div>
                                <div class="col-lg-4 text-right">
                                    @can('city_create')
                                        <button class="btn btn-primary add-button"><a href="{{ url('city/create') }}"><i
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
                                            <th>{{ __('Status') }}</th>
                                            @if (Gate::check('city_edit') || Gate::check('city_delete'))
                                                <th>{{ __('Action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($city as $item)
                                            <tr>
                                                <td></td>
                                                <th> <img class="table-img" src="{{ url('images/upload/' . $item->image) }}">
                                                </th>
                                                @if (session('direction') == 'rtl')
                                                <td>{{ $item->ar_name }}</td>
                                                @else
                                                <td>{{ $item->name }}</td>
                                                @endif 
                                                <td>
                                                    <h5><span
                                                            class="badge {{ $item->status == '1' ? 'badge-success' : 'badge-warning' }}  m-1">{{ $item->status == '1' ? 'Active' : 'Inactive' }}</span>
                                                    </h5>
                                                </td>
                                                @if (Gate::check('city_edit') || Gate::check('city_delete'))
                                                    <td class="dropdown-parent">
                                                    <div class="dropdown"><a href="#" data-toggle="dropdown"
                                                        class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                                        {{ __('Action') }}
                                                        <div class="d-sm-none d-lg-inline-block"></div>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @can('city_edit')
                                                        <a href="{{ route('city.edit', $item->id) }}" title="Event city" class="dropdown-item has-icon">
                                                        <i class="fas fa-edit"></i>
                                                        {{ __('Edit') }}
                                                    </a>
                                                          
                                                        @endcan

                                                        <a href="#" title="Event city" class="dropdown-item has-icon"  onclick="deleteData('Cities','{{ $item->id }}');" title="Delete city" >
                                                        <i class="fas fa-trash-alt "></i> 
                                                        {{ __('Delete') }}
                                                        </a>
                                              
                                                        </div>
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
@endsection
