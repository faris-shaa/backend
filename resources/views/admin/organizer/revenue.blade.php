@extends('master')

@section('content')
<section class="section">
    @include('admin.layout.breadcrumbs', [
        'title' => __('Revenue Report'),
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
                  <div class="row">
                      <div class="col-lg-8 p-3 ml-3"><h2 class="section-title"> {{__('Revenue Report')}}</h2></div>
                  </div>
                    <form method="POST" action="{{ url('/organization/income') }}" id="filter-form" class="form-group col-lg-10" style="display:flex;"> 
              @csrf 
    <div class="card-stats-title" style="width:40%; margin-right: 20px">{{ __('Events') }} 

        <div class="dropdown d-inline">

         <select id="event" name="selected_event_id" class="select2 z-20 w-full form-group"  onchange="submit()">
                  <option class="font-poppins font-normal text-sm text-black leading-6" @if($selected_event_id == null ) selected="selected" @endif
                     value="" >
                  {{ __('All') }}</option>
                  @foreach($events_dropdown as $event)
                  <option class="font-poppins font-normal text-sm text-black leading-6"
                     value="{{ $event->id }}" @if($selected_event_id == $event->id) selected="selected" @endif >
                      {{ $event->name }}</option>
                   @endforeach
                  </select>
          
        </div>
    </div>

    <div class="card-stats-title" style="width:40%; margin-right: 20px">{{ __('Payment Mode') }} 
        <div class="dropdown d-inline">
         
              <select id="payment" name="selected_payment_id" class="select2 z-20 w-full form-group" onchange="submit()">
                  <option class="font-poppins font-normal text-sm text-black leading-6" selected
                     value="">
                  {{ __('All') }}</option>
            
                  <option class="font-poppins font-normal text-sm text-black leading-6"
                     value="Edfapay" @if( $selected_payment ==  "Edfapay") selected="selected"  @endif>
                      Edfapay</option>
                      <option class="font-poppins font-normal text-sm text-black leading-6"
                     value="Tabby" @if( $selected_payment ==  "Tabby") selected="selected"  @endif>
                      Tabby</option>
                  
                  </select>
        </div>
    </div>

</form>
                  <div class="card-body">
                  <div class="table-responsive">
                    <table class="table" id="revenue_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{__('Order Id')}}</th>
                                <th>{{__('Customer')}}</th>
                                <th>{{__('Event Name')}}</th>
                                <th>{{__('Quantity')}}</th>
                                <th>{{__('Payment Gateway')}}</th>
                                <th>{{__('Order Total')}}</th>
                                <th>{{__('Total tax')}}</th>
                                <th>{{__('Coupon Discout')}}</th>
                                <th>{{__('TicketBy Commission')}}</th>
                                <th>{{__('Total Payment')}}</th>
                                
                                
                                <th>{{__('Created at')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <?php $org =$item->org_commission+$item->tax;  ?>
                                <tr>
                                    <td></td>
                                    <td>{{$item->order_id}}</td>
                                    <td>
                                       <h6>{{$item->customer->name.' '.$item->customer->last_name}}</h6>
                                       <p>{{$item->customer->email}}</p>
                                    </td>
                                    <td>{{$item->event->name}}</td>
                                    <td>{{$item->quantity}}</td>
                                    <td>{{$item->payment_type}}</td>
                                    <td>{{$item->ticket_price_total}}</td>
                                    <td>{{$item->tax}}</td>
                                    <td>{{$item->coupon_discount}}</td>
                                    <td>0</td>
                                    <td>{{$item->payment}}</td>
                                    
                                    
                                    <td>{{$item->created_at->format('Y-m-d')}}</td>
                                </tr>
                            @endforeach

                        </tbody>
                        {{-- <tfoot class="">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>{{ __('Page Total:') }} <br><br>{{ __('Main Total:') }} </th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot> --}}
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

   function submit (){
  
       $('#filter-form').submit();
   }
   
});
</script>
@endsection
