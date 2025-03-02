@extends('master')
@section('content')
@php
$currency = \App\Models\Setting::first()->currency;
@endphp
<style>
   table.dataTable tbody td.select-checkbox::before, table.dataTable tbody td.select-checkbox::after, table.dataTable tbody th.select-checkbox::before, table.dataTable tbody th.select-checkbox::after
   {
      margin-top:25px !important;
   }
   </style>
<section class="section">
   @include('admin.layout.breadcrumbs', [
   'title' => __('View Orders'),
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
                     <div class="col-lg-2">
                        <h2 class="section-title mt-0"> {{__('Order List')}}</h2>
                     </div>
          <form method="POST" action="{{ url('/orders') }}" id="filter-form" class="form-group col-lg-10" style="display:flex;"> 
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
                      {{ $event->name }} - {{Carbon\Carbon::parse($event->start_time)->format('d-m')}}</option>
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
                     value="ApplePay" @if( $selected_payment ==  "ApplePay") selected="selected"  @endif>
                      ApplePay</option>
                      <option class="font-poppins font-normal text-sm text-black leading-6"
                     value="Tabby" @if( $selected_payment ==  "Tabby") selected="selected"  @endif>
                      Tabby</option>
                  
                  </select>
        </div>
    </div>

</form>
                   
                  </div>

                  <div class="table-responsive">
                     <table class="table" id="order_table" style="width: 100%;">
                        <thead>
                           <tr>
                              <th></th>
                              <th>{{__('Order Id')}}</th>
                              <th>{{__('Customer Name')}}</th>
                              <th>{{__('Event Name')}}</th>
                              <th>{{__('Date')}}</th>
                              <th>{{__('Sold Ticket')}}</th>
                              <th>{{__('Tickets')}}</th>
                              <th>{{__('Phone Number')}}</th>
                              <th>{{__('Payment')}}</th>

                              <th>{{__('Payment Gateway')}}</th>
                              <th class="d-none">{{ __('Order Status') }}</th>
                              {{-- for print and pdf only --}}
                              <th>{{__('Order Status')}}</th>
                              <th class="d-none">{{ __('Payment Status') }}</th>
                              {{-- for print and pdf only --}}
                              <th>{{__('Payment Status')}}</th>
                              <th>{{__('Action')}}</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($orders as $item)
                           @php
                           $orderChild = App\Models\OrderChild::where('order_id', $item->id)->get();
                           $ticket_details = array();
                           foreach($orderChild as $child)
                           {
                              $ticket_name =  App\Models\Ticket::where('id', $child->ticket_id)->first();
                              if(isset($ticket_name->name))
                              {
                                 $ticket_details[$ticket_name->name] = isset($ticket_details[$ticket_name->name]) ? $ticket_details[$ticket_name->name] + 1  :  1 ;     
                              }
                             
                              
                              
                           }  
                           @endphp
                           <tr>
                              <td></td>
                              <td>{{$item->order_id}} </td>
                              @if (isset($item->customer))
                              <td>{{$item->customer->name.' '.$item->customer->last_name}}</td>
                              @else
                              <td></td>
                              @endif
                              <td>
                                 <h6 class="mb-0">{{$item->event->name}}</h6>
                                 <p class="mb-0">{{$item->event->start_time}}</p>
                              </td>
                              <td>
                                 <p class="mb-0">{{$item->created_at->format('Y-m-d')}}</p>
                                 <p class="mb-0">{{$item->created_at->setTimezone('Asia/Riyadh')->format('h:i a')}}</p>
                              </td>
                              <td>{{$item->quantity}}
                                </td>
                                <td> @if(is_array($ticket_details) )
                                 @foreach($ticket_details as $key => $details)
                                 <br>
                                 {{$details}} * {{$key}} 
                                 @endforeach
                                 @endif </td>
                               @if (isset($item->customer))
                              <td>{{$item->customer->phone}}</td>
                              @else
                              <td></td>
                              @endif
                              <td>{{$item->payment}}
                                
                              </td>
                              <td>{{$item->payment_type}}</td>
                              <td class="d-none">{{ $item->order_status }}</td>
                              {{-- for print and pdf only --}}
                              <td>
                                 <select name="order_status" id="status-{{ $item->id }}" class="form-control p-2" onchange="changeOrderStatus({{$item->id}})" {{ $item->order_status == "Complete" || $item->order_status == "Cancel"? 'disabled':'' }}>
                                 <option  value="Pending" {{ $item->order_status == "Pending"? 'selected':''}}> {{ __('Pending') }} </option>
                                 <option  value="Complete" {{ $item->order_status == "Complete"? 'selected':''}}> {{ __('Complete') }} </option>
                                 <option  value="Cancel" {{ $item->order_status == "Cancel"? 'selected':''}}> {{ __('Cancel') }} </option>
                                 @if(Auth::user()->hasRole("admin"))
                                 <option value="1" {{ $item->order_status == "Hide"? 'selected':''}}> {{ __('Hide') }} </option>
                                 @endif
                                 </select>
                              </td>
                              @if ($item->payment_status == 0)
                              <td class="d-none">{{ $item->payment_status == 0? 'Pending':'' }}</td>
                              {{-- for print and pdf only --}}
                              @else
                              <td class="d-none">{{ $item->payment_status == 1? 'Complete':'' }}</td>
                              {{-- for print and pdf only --}}
                              @endif
                              <td>
                                 <select name="payment_status" id="payment-{{ $item->id }}" class="form-control p-2" onchange="changePaymentStatus({{$item->id}})" {{ $item->payment_status == 1? 'disabled':'' }}>
                                 <option value="0" {{ $item->payment_status == 0? 'selected':''}}> {{ __('Pending') }} </option>
                                 <option value="1" {{ $item->payment_status == 1? 'selected':''}}> {{ __('Complete') }} </option>
                                 @if(Auth::user()->hasRole("admin"))
                                 <option value="1" {{ $item->order_status == "Hide"? 'selected':''}}> {{ __('Hide') }} </option>
                                 @endif
                                 </select>
                              </td>
                              <td class="dropdown-parent">
                                 <div class="dropdown">
                                    <a href="#" data-toggle="dropdown"
                                       class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                       {{ __('Action') }}
                                       <div class="d-sm-none d-lg-inline-block"></div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                       <a href="{{url('order-invoice/'.$item->id)}}" title="View  " class="dropdown-item has-icon">
                                       <i class="far fa-eye"></i>
                                       {{__('View')}}
                                       </a>
                                        <a href="#" onclick="deleteData('order','{{ $item->id }}');" title="Delete Order"  class="dropdown-item has-icon">
                                       <i class="fas fa-trash-alt "></i> 
                                       {{ __('Delete') }}
                                       </a>
                                    </div>
                                 </div>
                              </td>
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

   function submit (){
  
       $('#filter-form').submit();
   }
   
});
</script>
@endsection
