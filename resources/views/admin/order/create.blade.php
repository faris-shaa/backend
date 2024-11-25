@extends('master')

@section('content')
    @php
        $currency = \App\Models\Setting::first()->currency;
    @endphp
    <section class="section">
        @include('admin.layout.breadcrumbs', [
            'title' => __('Create Order'),
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
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
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
                                    <h2 class="section-title mt-0"> {{ __('Create Order Behalf of User') }}</h2>
                                </div>
                            </div>
                            <div class="">
                                <form method="post" action="{{ route('orderCreateForUser') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">{{ __('Email address') }}</label>
                                        <input name="email" type="email"
                                            class="form-control @error('email') ? is-invalid @enderror"
                                            id="exampleFormControlInput1" placeholder="" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="ticketId">{{ __('Select Ticket') }}</label>
                                        <select class="form-control select2" id="ticketId" name="ticket_id" required
                                            onchange="checkDateValidation()">
                                            <option value="" disabled selected>{{ __('Please Select Ticket') }}
                                            </option>
                                            @foreach ($ticket as $item)
                                                <option value="{{ $item->id }}">{{ 'Ticket : ' . $item->name }} |
                                                    {{ 'From Event : ' . __($item->event->name ?? '') }}</option>
                                            @endforeach
                                        </select>
                                        @error('ticket_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group d-none ticketdiv">
                                        <label>{{ __('Select Date') }}</label>
                                        <input type="text" name="ticket_date" id="start_time"
                                            value="{{ old('ticket_date') }}" placeholder="{{ __('Choose Date') }}"
                                            class="form-control date @error('ticket_date')? is-invalid @enderror">
                                        @error('ticket_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity">{{ __('Quantity') }}</label>
                                        <input type="number" name="quantity" step="1" min="1"
                                            class="form-control  @error('quantity')? is-invalid @enderror" value="1" required id="quantity" placeholder="">
                                        @error('quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">{{ __('Book a Ticket') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
