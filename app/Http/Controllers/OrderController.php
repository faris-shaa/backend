<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use App\Models\AppUser;
use App\Models\Review;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\Setting;
use App\Models\OrderTax;
use App\Models\OrderChild;
use App\Models\User;
use App\Models\Settlement;
use App\Models\EventReport;
use App\Models\Module;
use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Config;
use App\Mail\ResetPassword;
use App\Models\OrganizerPaymentKeys;
use App\Models\Tax;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Stripe;
use Carbon\Carbon;
use Exception;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Modules\BankPayout\Entities\BankDetails;
use Illuminate\Support\Facades\Hash;
use Throwable;
use App\Models\NotificationTemplate;
use App\Rules\UniqueEmailWithStatus;

class OrderController extends Controller
{
    public function index( Request $request )
    {
        //dd($request->all());
        if (Auth::user()->hasRole('admin')) {
            $orders = Order::with(['customer', 'event'])->OrderBy('id', 'DESC');
             $events_dropdown = Event::with(['category:id,name'])
            ->where('event_status', 'Pending')->where('is_deleted',0)->orderBy('id','DESC')->get();
        } elseif (Auth::user()->hasRole('Organizer')) {
            $orders = Order::with(['customer', 'event'])->where('organization_id', Auth::user()->id)->OrderBy('id', 'DESC');
             $events_dropdown = Event::with(['category:id,name'])
            ->where([ ['user_id', Auth::user()->id], ['event_status', 'Pending']])->get();
        }
       
        $selected_event = $selected_event_id = null ;
        if(isset($request->selected_event_id) )
        {

            $selected_event_id = $request->selected_event_id ;
             if (Auth::user()->hasRole('admin')) {
                $orders = Order::with(['customer', 'event'])->where('event_id',$selected_event_id)->OrderBy('id', 'DESC');
             }
             else
             {
                    $orders = Order::with(['customer', 'event'])->where('event_id',$selected_event_id)->where('organization_id', Auth::user()->id)->OrderBy('id', 'DESC');
             }
             
             $selected_event = Event::where('id',$selected_event_id)->first();
        }
        
        $selected_payment = null ;
        if( isset($request->selected_payment_id))
        {
            $selected_payment = $request->selected_payment_id ; 
            if($request->selected_payment_id == "Edfapay")
            {
                $edfapy_status = array( "EDAFPAY");
                $orders = $orders->whereIn('payment_type',$edfapy_status);
            }
            if($request->selected_payment_id == "ApplePay")
            {
                $edfapy_status = array( "ApplePay");
                $orders = $orders->whereIn('payment_type',$edfapy_status);
            }
            if($request->selected_payment_id == "Tabby")
            {
                $tabby_status = array("TABBY");
                $orders = $orders->whereIn('payment_type',$tabby_status);
            }
        }

        $orders = $orders->get();
        
        return view('admin.order.index', compact('orders','events_dropdown','selected_event_id','selected_event','selected_payment'));
    }

    public function show($order_id, $id)
    {
        $order = Order::with(['customer', 'event', 'organization', 'ticket'])->find($order_id);
        $noti = Notification::find($id);
        if (isset($noti) && $noti->status == 1) {
            DB::table('notification')->where('id', $id)->update(['status' => 0]);
        }
        return view('admin.order.view', compact('order'));
    }

    public function orderInvoice($id)
    {
        $order = Order::with(['customer', 'event', 'organization', 'ticket'])->find($id);
        $order->tax_data = OrderTax::where('order_id', $id)->get();
        $order->ticket_data = OrderChild::where('order_id', $order->id)->get();
        return view('admin.order.invoice', compact('order'));
    }

    public function userReview()
    {
        $data = Review::orderBy('id', 'DESC')->get();
        return view('admin.review', compact('data'));
    }
    public function eventReports()
    {
        $data = EventReport::orderBy('id', 'DESC')->get();
        return view('admin.report', compact('data'));
    }
    public function changeReviewStatus($id)
    {
        Review::find($id)->update(['status' => 1]);
        return redirect()->back()->withStatus(__('Review is published successfully.'));
    }

    public function deleteReview($id)
    {
        $data = Review::find($id);
        $data->delete();
        return redirect()->back()->withStatus(__('Review is deleted successfully.'));
    }

    public function customerReport(Request $request)
    {
        $data = AppUser::orderBy('id', 'DESC');
        if (isset($request->duration) && $request->duration != null) {
            $start_date = explode(' to ', $request->duration)[0];
            $end_date = count(explode(' to ', $request->duration)) == 1 ? explode(' to ', $request->duration)[0] : explode(' to ', $request->duration)[1];
            $data->whereBetween('created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
        }
        $data = $data->get();

        foreach ($data as $value) {
            $value->buy_tickets = Order::where('customer_id', $value->id)->sum('quantity');
        }
        return view('admin.report.org_customer_report', compact('data', 'request'));
    }

    public function ordersReport(Request $request)
    {
        $data = Order::where([['organization_id', Auth::user()->id], ['payment_status', 1]]);
        if (isset($request->customer) && $request->customer >= 1) {
            $data->where('customer_id', $request->customer);
        }
        if (isset($request->duration) && $request->duration != null) {
            $start_date = explode(' to ', $request->duration)[0];
            $end_date = count(explode(' to ', $request->duration)) == 1 ? explode(' to ', $request->duration)[0] : explode(' to ', $request->duration)[1];
            $data->whereBetween('created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
        }

        $data = $data->orderBy('id', 'DESC')->get();

        return view('admin.report.org_orders_report', compact('data', 'request'));
    }

    public function orgRevenueReport(Request $request)
    {
        $data = Settlement::where('user_id', Auth::user()->id)->orderBy('id', 'DESC');
        if (isset($request->duration) && $request->duration != null) {
            $start_date = explode(' to ', $request->duration)[0];
            $end_date = count(explode(' to ', $request->duration)) == 1 ? explode(' to ', $request->duration)[0] : explode(' to ', $request->duration)[1];
            $data->whereBetween('created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
        }
        $data = $data->get();

        foreach ($data as $value) {
            $value->user = User::find($value->user_id);
        }
        return view('admin.report.org_revenue', compact('data', 'request'));
    }

    public function adminCustomerReport(Request $request)
    {
        $data = AppUser::orderBy('id', 'DESC');

        if (isset($request->duration) && $request->duration != null) {
            $start_date = explode(' to ', $request->duration)[0];
            $end_date = count(explode(' to ', $request->duration)) == 1 ? explode(' to ', $request->duration)[0] : explode(' to ', $request->duration)[1];
            $data->whereBetween('created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
        }
        $data = $data->get();
        foreach ($data as $value) {
            $value->buy_tickets = Order::where('customer_id', $value->id)->sum('quantity');
        }
        return view('admin.report.admin_customer_report', compact('data', 'request'));
    }

    public function adminOrgReport(Request $request)
    {
        $data = User::role('Organizer')->orderBy('id', 'DESC');
        if (isset($request->duration) && $request->duration != null) {
            $start_date = explode(' to ', $request->duration)[0];
            $end_date = count(explode(' to ', $request->duration)) == 1 ? explode(' to ', $request->duration)[0] : explode(' to ', $request->duration)[1];
            $data->whereBetween('created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
        }
        $data = $data->get();
        foreach ($data as $value) {
            $value->total_events = Event::where('user_id', $value->id)->count();
            $value->total_tickets = Ticket::where([['user_id', $value->id], ['is_deleted', 0]])->sum('quantity');
            $value->sold_tickets = Order::where('organization_id', $value->id)->sum('quantity');
        }
        return view('admin.report.admin_org_report', compact('data', 'request'));
    }

    public function adminRevenueReport(Request $request)
    {
        $data = Order::with(['customer:id,name,last_name,email', 'event:id,name'])->where('payment_status', 1);
        if (isset($request->organizer) && $request->organizer >= 1) {
            $data->where('organization_id', $request->organizer);
        }
        if (isset($request->customer) && $request->customer >= 1) {
            $data->where('customer_id', $request->customer);
        }
        if (isset($request->duration) && $request->duration != null) {
            $start_date = explode(' to ', $request->duration)[0];
            $end_date = count(explode(' to ', $request->duration)) == 1 ? explode(' to ', $request->duration)[0] : explode(' to ', $request->duration)[1];
            $data->whereBetween('created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
        }

        $data = $data->orderBy('id', 'DESC')->get();

        return view('admin.report.admin_revenue_report', compact('data', 'request'));
    }

    public function getStatistics($month)
    {
        $day = Carbon::parse(Carbon::now()->year . '-' . Carbon::now()->month . '-01')->daysInMonth;

        if (Auth::user()->hasRole('admin')) {
            $master['total_order'] = Order::whereBetween('created_at', [Carbon::now()->year . "-" . $month . "-01 00:00:00",  Carbon::now()->year . "-" . $month . "-" . $day . " 23:59:59"])->count();
            $master['pending_order'] = Order::where('order_status', 'Pending')->whereBetween('created_at', [Carbon::now()->year . "-" . $month . "-01 00:00:00",  Carbon::now()->year . "-" . $month . "-" . $day . " 23:59:59"])->count();
            $master['complete_order'] = Order::where('order_status', 'Complete')->whereBetween('created_at', [Carbon::now()->year . "-" . $month . "-01 00:00:00",  Carbon::now()->year . "-" . $month . "-" . $day . " 23:59:59"])->count();
            $master['cancel_order'] = Order::where('order_status', 'Cancel')->whereBetween('created_at', [Carbon::now()->year . "-" . $month . "-01 00:00:00",  Carbon::now()->year . "-" . $month . "-" . $day . " 23:59:59"])->count();
        } elseif (Auth::user()->hasRole('Organizer')) {
            $master['total_order'] = Order::where('organization_id', Auth::user()->id)->whereBetween('created_at', [Carbon::now()->year . "-" . $month . "-01 00:00:00",  Carbon::now()->year . "-" . $month . "-" . $day . " 23:59:59"])->count();
            $master['pending_order'] = Order::where([['order_status', 'Pending'], ['organization_id', Auth::user()->id]])->whereBetween('created_at', [Carbon::now()->year . "-" . $month . "-01 00:00:00",  Carbon::now()->year . "-" . $month . "-" . $day . " 23:59:59"])->count();
            $master['complete_order'] = Order::where([['order_status', 'Complete'], ['organization_id', Auth::user()->id]])->whereBetween('created_at', [Carbon::now()->year . "-" . $month . "-01 00:00:00",  Carbon::now()->year . "-" . $month . "-" . $day . " 23:59:59"])->count();
            $master['cancel_order'] = Order::where([['order_status', 'Cancel'], ['organization_id', Auth::user()->id]])->whereBetween('created_at', [Carbon::now()->year . "-" . $month . "-01 00:00:00",  Carbon::now()->year . "-" . $month . "-" . $day . " 23:59:59"])->count();
        }

        return response()->json(['success' => true, 'data' => $master], 200);
    }

    public function settlementReport()
    {
        $data = User::role('Organizer')->orderBy('id', 'DESC')->get();
        foreach ($data as $value) {
            $value->total_orders = Order::where('organization_id', $value->id)->count();
            $value->total_commission = Order::where([['organization_id', $value->id], ['payment_status', 1]])
                ->sum(DB::raw('org_commission + tax'));
            $value->pay_commission = Order::where([['organization_id', $value->id], ['payment_status', 1], ['org_pay_status', 1]])
                ->sum(DB::raw('org_commission + tax'));
            $value->organization_commission = Order::where([['organization_id', $value->id], ['payment_status', 1], ['org_pay_status', 0]])
                ->sum(DB::raw('org_commission + tax'));
        }
        $bankModule = Module::where('module', 'BankPayout')->first();
        return view('admin.report.admin_settlement_report', compact('data', 'bankModule'));
    }

    public function viewSettlement($id)
    {
        $data = Settlement::where('user_id', $id)->orderBy('id', 'DESC')->get();
        foreach ($data as $value) {
            $value->user = User::find($value->user_id);
        }
        return view('admin.report.view_settlement', compact('data'));
    }

    public function payToUser(Request $request)
    {
        $data = $request->all();
        if ($request->payment_type == "STRIPE") {
            $currency = Setting::find(1)->currency;
            $stripe_secret = OrganizerPaymentKeys::find(1)->stripeSecretKey;
            Stripe\Stripe::setApiKey($stripe_secret);
            $stripeDetail =  Stripe\Charge::create([
                "amount" => intval($request->payment) * 100,
                "currency" => $currency,
                "source" => $request->stripeToken,
            ]);
            $data['payment_token'] = $stripeDetail->id;
            $data['payment_status'] = 1;
        }
        Settlement::create($data);
        Order::where([['organization_id', $request->user_id], ['payment_status', 1], ['org_pay_status', 0]])->update(['org_pay_status' => 1]);
        return redirect()->back()->withStatus(__('Your Payment done successfully.'));
    }

    public function payToOrganization(Request $request)
    {
        if ($request->payment_type == 'BANK') {
            $bankDetails = BankDetails::where('organizer_id', $request->user_id)->first();
            if (!$bankDetails) {
                return response()->json(['msg' => 'Please add bank details', 'success' => false], 200);
            }
        }
        Settlement::create($request->all());
        Order::where([['organization_id', $request->user_id], ['payment_status', 1], ['org_pay_status', 0]])->update(['org_pay_status' => 1]);
        return response()->json(['msg' => null, 'success' => true], 200);
    }

    public function getQrCode($id)
    {
        $ticket = OrderChild::find($id);
        $ticket->order = Order::with(['customer:id,name,last_name', 'event:id,start_time,end_time,name,type,address', 'organization:id,image,first_name,last_name'])->find($ticket->order_id);
        $ticket->qrCode = QrCode::size(200)->generate($ticket->ticket_number);
        return view('admin.order.printTicket', compact('ticket'));
    }

    public function changeStatus(Request $request)
    {
        Order::find($request->id)->update(['order_status' => $request->order_status]);
        return response()->json(['success' => true, 'msg' => 'Status Changed'], 200);
    }

    public function changePaymentStatus(Request $request)
    {
        Order::find($request->id)->update(['payment_status' => 1]);
        return response()->json(['success' => true, 'msg' => 'Status Changed'], 200);
    }

    public function orderInvoicePrint($order_id)
    {
        $order = Order::with(['customer', 'event', 'organization', 'ticket'])->find($order_id);
        $order->tax_data = OrderTax::where('order_id', $order->id)->get();
        $order->ticket_data = OrderChild::where('order_id', $order->id)->get();
        $order->maintax = array();
        foreach ($order->tax_data as $item) {
            $tax = Tax::find($item->tax_id)->get();
            $order->maintax = $tax;
        }
        return view('admin.order.invoicePrint', compact('order'));
    }

    public function sendMail($id)
    {

        $order = Order::with(['customer', 'event', 'organization', 'ticket'])->find($id);
        $order->tax_data = OrderTax::where('order_id', $order->id)->get();
        $order->ticket_data = OrderChild::where('order_id', $order->id)->get();
        $customPaper = array(0, 0, 720, 1440);
        $pdf = FacadePdf::loadView('ticketmail', compact('order'))->save(public_path("ticket.pdf"))->setPaper($customPaper, $orientation = 'portrait');
        $data["email"] = $order->customer->email;
        $data["title"] = "Ticket PDF";
        $data["body"] = "";
        $tempp = $pdf->output();
        $pathToFile = public_path("ticket.pdf");
        $sender = Setting::select('sender_email', 'app_name')->first();
        try {
            Mail::send('mail', $data, function ($message) use ($data, $tempp, $sender) {
                $message->from($sender->sender_email, $sender->app_name)
                    ->to($data["email"])
                    ->subject($data["title"])
                    ->attachData($tempp, "ticket.pdf");
            });
        } catch (Throwable $th) {
            Log::info($th->getMessage());
        }
        return redirect()->back()->withStatus(__('Mail Send Successfully.'));
    }
    public function showTicket($id)
    {
        $order = Order::with(['customer', 'event', 'organization', 'ticket'])->find($id);
        $orderchild = OrderChild::where('order_id', $order->id)->get();
        return view('frontend.singleticket', compact('order', 'orderchild'));
    }
    public function ticketDownload($id)
    {
        $order = Order::with(['customer', 'event', 'organization', 'ticket'])->find($id);
        $order->tax_data = OrderTax::where('order_id', $order->id)->get();
        $order->ticket_data = OrderChild::where('order_id', $order->id)->get();
        $customPaper = array(0, 0, 720, 1440);
        $pdf = FacadePdf::loadView('ticketmail', compact('order'))->save(public_path("ticket.pdf"))->setPaper($customPaper, $orientation = 'portrait');
        $tempp = $pdf->output();
        $response = response($tempp, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="ticket.pdf"',
        ]);
        return $response;
    }

    public function checkoutEdfapay(Request $request)
    {
        $data = [
            'body' => $request->body,

        ];
        $url  = $request->url;
        // Redirect to a view that will handle the POST request
        return view('frontend.edfapay_redirect', compact('data', 'url'));
    }

    public function dataTesting()
    {


        // Data to pass to the view
        $data = [
            'body' => 'dFRUQTRid1JsS25sT1RoNXBHUjBzeFp6RzlYdkR4Ymw0M0FZSUVFVElMb3NjUmVXcXpka2pIcEpNWUg1SXU5aFRvRDFNWGlYT3pXc0xNQi9iM0ZEM0o1OENMQXJ4Q0Z6dDBUb3owWEZIdDFuNCtMTVBqeHd2N1FyNG1ZZTlXejZFTGdNSjlFbHhCWGNOZzZDc3FNTG1nPT06OkU2amxWSjJIYytjTXY2SmxZZkVjYXc9PQ==:',

        ];

        // Redirect to a view that will handle the POST request
        return view('frontend.edfapay_redirect', compact('data'));

        $password = "3a282e78e09e33a2063919b7b42d290c";

        $cardNumber = "5123450000000008";
        $email = "Abdulrhman.a@edfapay.com";

        function reverseString($str)
        {
            return strrev($str);
        }



        $final = strtoupper(reverseString($email) . $password . reverseString(substr($cardNumber, 0, 6) . substr($cardNumber, -4)));
        $sha1Hash = md5($final);
        return response()->json(['hash' => $sha1Hash]);
        $order = Order::find(6);
        $merchantPass = "3a282e78e09e33a2063919b7b42d290c";

        $description = "Description";

        $currency = "SAR";

        // Concatenate the values
        $stringToHash =  "12" . "1" . "SAR" . "Description" . $merchantPass;
        //$stringToHash = "#35332" . "0.11". "SAR" . "dummy" . "3a282e78e09e33a2063919b7b42d290c";

        // Convert the concatenated string to uppercase
        $uppercaseString = strtoupper($stringToHash);

        // Generate the MD5 hash
        $md5Hash = md5($uppercaseString);

        // Generate the SHA1 hash of the MD5 hash
        $sha1Hash = sha1($md5Hash);

        // Return or use the SHA1 hash as needed
        return response()->json(['hash' => $sha1Hash]);
    }


    public function testingCode($data)
    {
        $data = array('name' => "TicketBy", 'email' => 'hivasavada@gmail.com', "otp" => '1234');
        Mail::send(['html' => 'frontend.email.otp'], $data, function ($message) use ($data) {
            $message->to($data['email'])->subject('OTP Verification');
            $message->from('ticketbyksa@gmail.com', 'TicketBy');
        });

        dd("j");
    }

    public function userRegister(Request $request)
    {
        $verify = 0; //Setting::first()->user_verify == 1 ? 0 : 1;
        $data = $request->all();
        // if($data['user_type'] == 'user') {
        //     $request->validate([
        //         'name' => 'bail|required',
        //     ]);
        // }
        // $request->validate([
        //    // 'last_name' => 'bail|required',
        //     'email' => 'bail|required|email|unique:app_user|unique:users',
        //     'phone' => 'bail|required|numeric',
        //    // 'password' => 'bail|required|min:6',
        //     'Countrycode' => 'bail|required',
        // ]);


        // if($data['user_type'] == 'user') {
        //     $request->validate([
        //         'name' => 'bail|required',
        //     ]);
        // }
        // $request->validate([
        //    // 'last_name' => 'bail|required',
        //     'email' => 'bail|required|email|unique:app_user|unique:users',
        //     'phone' => 'bail|required|numeric',
        //    // 'password' => 'bail|required|min:6',
        //     'Countrycode' => 'bail|required',
        // ]);


        // Validation rules based on user type
        if ($data['user_type'] == 'user') {
            $request->validate([
                'name' => 'bail|required',
            ]);
        }

        // Additional validation
        $validator = \Validator::make($data, [
            'email' => 'bail|required|email|unique:app_user|unique:users',
            'phone' => 'bail|required|numeric',
            'Countrycode' => 'bail|required',
        ]);

        // If validation fails, return response conditionally based on 'checkout'
        if ($validator->fails()) {
            if (isset($request->checkout)) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }



        $data['password'] = Hash::make($request->password);
        $data['image'] = "defaultuser.png";
        $data['status'] = 1;
        $data['provider'] = "LOCAL";
        $data['language'] = Setting::first()->language;
        $data['phone'] = "+" . $request->Countrycode . $request->phone;
        $data['is_verify'] = $verify;
        if ($data['user_type'] == 'organizer') {
            $request->validate([
                'first_name' => 'bail|required',
            ]);
            $user = User::create($data);
            $user->assignRole('Organizer');
            OrganizerPaymentKeys::create([
                'organizer_id' => $user->id,
            ]);
        } else {

            $user = AppUser::create($data);
        }

        // if(!isset($request->otp) || is_null($request->otp) )
        // {
        //     $this->sendOtp($request , $user);
        // }

        if ($user->is_verify == 0) {

            // if (Setting::first()->verify_by == 'email' && Setting::first()->mail_host != NULL) {
            //     if ($data['user_type'] == 'organizer') {
            //         $details = [
            //             'url' => url('organizer/VerificationConfirm/' .  $user->id)
            //         ];
            //     } else {
            //         $details = [
            //             'url' => url('user/VerificationConfirm/' .  $user->id)
            //         ];
            //     }
            //     Mail::to($user->email)->send(new \App\Mail\VerifyMail($details));
            //     return redirect('user/login')->with(['success' => "Verification link has been sent to your email. Please visit that link to complete the verification"]);
            // }
            // if (Setting::first()->verify_by == 'phone') {
            if (true) {
                $setting = Setting::first();

                $otp = rand(100000, 999999);
                $to = $user->phone;

                $message = "Your phone verification code is $otp for $setting->app_name.";
                if (true) {

                    //try {
                    // $curl = curl_init();

                    // curl_setopt_array($curl, array(
                    //   CURLOPT_URL => 'https://api.taqnyat.sa/v1/messages',
                    //   CURLOPT_RETURNTRANSFER => true,
                    //   CURLOPT_ENCODING => '',
                    //   CURLOPT_MAXREDIRS => 10,
                    //   CURLOPT_TIMEOUT => 0,
                    //   CURLOPT_FOLLOWLOCATION => true,
                    //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    //   CURLOPT_CUSTOMREQUEST => 'POST',
                    //   CURLOPT_POSTFIELDS =>'{
                    //     "recipients": [
                    //         '.$user->phone.'
                    //     ],
                    //     "body":"'.$message.'",
                    //     "sender":"DARLANA"
                    // }',
                    //   CURLOPT_HTTPHEADER => array(
                    //     'Content-Type: application/json',
                    //     'Authorization: Bearer 647c5f992f7202a353e21ca58ecfa788'
                    //   ),
                    // ));

                    // $response = curl_exec($curl);

                    // curl_close($curl);
                    // $responseData = json_decode($response, true);

                    //    if(isset($responseData['statusCode']) && $responseData['statusCode'] == 201)
                    if (true) {
                        if ($data['user_type'] == 'organizer') {

                            $user = User::find($user->id);
                            $dataemail['name'] = $user->first_name . " " . $user->last_name;
                            $dataemail['email'] = $user->email;
                            $dataemail['otp'] = $otp;
                            $language = "عربي";
                            if (session('direction') == 'ltr') {
                                $language = "English";
                            }
                            if (isset($request->language) && $request->language == "English") {
                                $language = "English";
                            }

                            $dataemail['language'] = $language;
                            Mail::send(['html' => 'frontend.email.otp'], $dataemail, function ($message) use ($dataemail) {
                                $message->to($dataemail['email'])->subject('OTP Verification');
                                $message->from('ticketbyksa@gmail.com', 'TicketBy');
                            });
                            $user->otp = $otp;
                            $user->update();
                            return redirect('organizer/otp-verify/' . $user->id)->with(['success' => "Phone verification code sent via SMS."]);
                        } else {
                            $user = AppUser::find($user->id);
                            $dataemail['name'] = $user->name;
                            $dataemail['email'] = $user->email;
                            $dataemail['otp'] = $otp;
                            $language = "عربي";
                            if (session('direction') == 'ltr') {
                                $language = "English";
                            }
                            if (isset($request->language) && $request->language == "English") {
                                $language = "English";
                            }
                            $dataemail['language'] = $language;

                            if (!isset($request->checkout)) {

                                Mail::send(['html' => 'frontend.email.otp'], $dataemail, function ($message) use ($dataemail) {
                                    $message->to($dataemail['email'])->subject('OTP Verification');
                                    $message->from('ticketbyksa@gmail.com', 'TicketBy');
                                });
                            }
                            $user->otp = $otp;
                            $user->update();

                            if (isset($request->checkout)) {
                                Auth::guard('appuser')->login($user);
                                //return redirect()->to('/' . $request->checkout);
                                return response()->json([
                                    'success' => true,
                                    'redirect_url' => url('/' . $request->checkout),
                                    'message' => 'Registration successful, redirecting...'
                                ]);
                            } else {
                                return redirect('user/otp-verify/' . $user->id)->with(['success' => "Phone verification code sent via SMS."]);
                            }
                        }
                    }
                    // else{
                    //     return redirect()->back()->with('error', 'Somthing Went Wrong');
                    // }
                    // } catch (\Throwable $th) {
                    //     return redirect()->back()->with('error', 'Somthing Went Wrong');
                    // }
                }
                if ($setting->enable_twillio == 1) {
                    $twilio_sid = $setting->twilio_account_id;
                    $twilio_token = $setting->twilio_auth_token;
                    $twilio_phone_number = $setting->twilio_phone_number;
                    try {
                        $twilio = new Clients($twilio_sid, $twilio_token);
                        $twilio->messages->create(
                            $to,
                            [
                                'from' => $twilio_phone_number,
                                'body' => $message,
                            ]
                        );
                    } catch (\Throwable $th) {
                        return redirect()->back()->with('error', 'Somthing Went Wrong');
                    }
                }
                if ($setting->enable_vonage == 1) {
                    $apiKey = $setting->vonege_api_key;
                    $apiSecret = $setting->vonage_account_secret;
                    $virtualNumber = $setting->vonage_sender_number;
                    $response = Http::post('https://rest.nexmo.com/sms/json', [
                        'api_key' => $apiKey,
                        'api_secret' => $apiSecret,
                        'to' => $to,
                        'from' => $virtualNumber,
                        'text' => $message,
                    ]);
                    dd($response);
                }
                if ($data['user_type'] == 'organizer') {
                    $user = User::find($user->id);
                    $user->otp = $otp;
                    $user->update();
                    return redirect('organizer/otp-verify/' . $user->id)->with(['success' => "Phone verification code sent via SMS."]);
                } else {
                    $user = AppUser::find($user->id);
                    $user->otp = $otp;
                    $user->update();
                    if (isset($request->checkout)) {
                        return redirect()->to('/' . $request->checkout);
                    } else {
                        return redirect('user/otp-verify/' . $user->id)->with(['success' => "Phone verification code sent via SMS."]);
                    }
                }
            }
        }
        return redirect('user/login')->with(['success' => "Congratulations! Your account registration was successful. You can now log in to your account and start using our services. Thank you for choosing our platform"]);
    }


    // login for user 
    public function login()
    {
        if (Auth::guard('appuser')->check() || Auth::check()) {
            return redirect()->back();
        }

        $setting = Setting::first(['app_name', 'logo']);
        SEOMeta::setTitle($setting->app_name . ' - Login' ?? env('APP_NAME'))
            ->setDescription('This is login page')
            ->setCanonical(url()->current())
            ->addKeyword(['login page', $setting->app_name, $setting->app_name . ' Login', 'sign-in page', $setting->app_name . ' sign-in']);

        OpenGraph::setTitle($setting->app_name . ' - Login' ?? env('APP_NAME'))
            ->setDescription('This is login page')
            ->setUrl(url()->current());

        JsonLdMulti::setTitle($setting->app_name . ' - Login' ?? env('APP_NAME'));
        JsonLdMulti::setDescription('This is login page');
        JsonLdMulti::addImage($setting->imagePath . $setting->logo);

        SEOTools::setTitle($setting->app_name . ' - Login' ?? env('APP_NAME'));
        SEOTools::setDescription('This is login page');
        SEOTools::opengraph()->addProperty(
            'keywords',
            [
                'login page',
                $setting->app_name,
                $setting->app_name . ' Login',
                'sign-in page',
                $setting->app_name . ' sign-in'
            ]
        );
        SEOTools::opengraph()->addProperty('image', $setting->imagePath . $setting->logo);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);
        return view('frontend.auth.login');
    }

    public function sendLoginOTP(Request $request)
    {

        $setting = Setting::first();
        $user = User::where('email', $request->email)->first();
        if ($user == null) {
            $user = User::where('email', $request->email)->first();
        }

        $user = AppUser::where('email', $request->email)->first();
        if ($user == null) {
            $user = AppUser::where('email', $request->email)->first();
        }
        if ($user == null) {
            return redirect()->back()->with('error', 'Invalid Email or Phone Number');
        }
        $otp = rand(100000, 999999);
        $to = $user->phone;

        $message = "Your phone verification code is $otp for $setting->app_name.";
        if (true) {

            //try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.taqnyat.sa/v1/messages',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "recipients": [
                    ' . $user->phone . '
                ],
                "body":"' . $message . '",
                "sender":"DARLANA"
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer 647c5f992f7202a353e21ca58ecfa788'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $responseData = json_decode($response, true);

            // if(isset($responseData['statusCode']) && $responseData['statusCode'] == 201)
            if (true) {


                if ($request->type == 'organizer') {

                    $user = User::find($user->id);
                    $dataemail['name'] = $user->first_name . " " . $user->last_name;
                    $dataemail['email'] = $user->email;
                    $dataemail['otp'] = $otp;

                    Mail::send(['html' => 'frontend.email.otp'], $dataemail, function ($message) use ($dataemail) {
                        $message->to($dataemail['email'])->subject('OTP Verification');
                        $message->from('ticketbyksa@gmail.com', 'TicketBy');
                    });
                    $user->otp = $otp;
                    $user->update();
                    return redirect('organizer/otp-verify/' . $user->id)->with(['success' => "Phone verification code sent via SMS."]);
                } else {
                    $user = AppUser::find($user->id);
                    $dataemail['name'] = $user->name;
                    $dataemail['email'] = $user->email;
                    $dataemail['otp'] = $otp;

                    // $data = array('name'=>"TicketBy" , 'email'=>'hivasavada@gmail.com',"otp"=>$otp);
                    Mail::send(['html' => 'frontend.email.otp'], $dataemail, function ($message) use ($dataemail) {
                        $message->to($dataemail['email'])->subject('OTP Verification');
                        $message->from('ticketbyksa@gmail.com', 'TicketBy');
                    });
                    $user->otp = $otp;
                    $user->update();
                    return redirect('user/otp-verify/' . $user->id)->with(['success' => "Phone verification code sent via SMS."]);
                }
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong');
            }
            // } catch (\Throwable $th) {
            //     return redirect()->back()->with('error', 'Somthing Went Wrong');
            // }
        }
    }
    public function userRegisterApp(Request $request)
    {

        // $request->validate([
        //     'first_name' => 'bail|required',
        //     'last_name' => 'bail|required',
        //     'email' => 'bail|required|email|unique:app_user|unique:users',
        //     'password' => 'bail|required|min:6',
        //     'phone' => 'bail|required',
        //     'Countrycode' => 'bail|required',
        // ]);
        $check =  AppUser::where('email', $request->email)->where('status', 1)->first();
        if ($check) {
            return response()->json(['success' => false, 'msg' => 'Email Address Already taken', 'data' => null], 200);
        }

        $data = $request->all();
        $verify = Setting::first()->user_verify == 1 ? 0 : 1;
        $data['password'] =  Hash::make($request->password);
        $data['image'] = "defaultuser.png";
        $data['status'] = 1;
        $data['name'] = $request->first_name;
        $data['provider'] = "LOCAL";
        $data['language'] = Setting::first()->language;
        $data['is_verify'] = $verify;
        $data['phone'] = $request->Countrycode . $request->phone;
        $data['is_verify'] = $verify;
        $otp = rand(100000, 999999);
        $data['otp'] = $otp;
        $user = AppUser::create($data);

        $dataemail['name'] = $user->first_name . " " . $user->last_name;
        $dataemail['email'] = $user->email;
        $dataemail['otp'] = $otp;

        Mail::send(['html' => 'frontend.email.otp'], $dataemail, function ($message) use ($dataemail) {
            $message->to($dataemail['email'])->subject('OTP Verification');
            $message->from('ticketbyksa@gmail.com', 'TicketBy');
        });
        if ($user->is_verify == 0) {

            if (Setting::first()->verify_by == 'email' && Setting::first()->mail_host != NULL) {
                $details = [
                    'id' => $user->id,
                ];
                $setting = Setting::first();
                $config = array(
                    'driver'     => $setting->mail_mailer,
                    'host'       => $setting->mail_host,
                    'port'       => $setting->mail_port,
                    'encryption' => $setting->mail_encryption,
                    'username'   => $setting->mail_username,
                    'password'   => $setting->mail_password
                );
                Config::set('mail', $config);

                $details = [
                    'url' => url('user/VerificationConfirm/' .  $user->id)
                ];
                Mail::to($user->email)->send(new \App\Mail\VerifyMail($details));
                return response()->json(['msg' => 'Verification link has been sent to your email. Please visit that link to complete the verification.', 'data' => $user, 'success' => true], 200);
            }
            if (Setting::first()->verify_by == 'phone') {
                $setting = Setting::first();
                $otp = rand(100000, 999999);
                $to = $user->phone;
                $message = "Your phone verification code is $otp for $setting->app_name.";
                if ($setting->enable_twillio == 1) {
                    $twilio_sid = $setting->twilio_account_id;
                    $twilio_token = $setting->twilio_auth_token;
                    $twilio_phone_number = $setting->twilio_phone_number;
                    try {
                        $twilio = new Clients($twilio_sid, $twilio_token);
                        $twilio->messages->create(
                            $to,
                            [
                                'from' => $twilio_phone_number,
                                'body' => $message,
                            ]
                        );
                    } catch (\Throwable $th) {
                        return redirect()->back()->with('error', 'Somthing Went Wrong');
                    }
                }
                if ($setting->enable_vonage == 1) {
                    $apiKey = $setting->vonege_api_key;
                    $apiSecret = $setting->vonage_account_secret;
                    $virtualNumber = $setting->vonage_sender_number;
                    $response = Http::post('https://rest.nexmo.com/sms/json', [
                        'api_key' => $apiKey,
                        'api_secret' => $apiSecret,
                        'to' => $to,
                        'from' => $virtualNumber,
                        'text' => $message,
                    ]);
                }
                $user = AppUser::find($user->id);
                $user->otp = $otp;
                $user->update();
                return response()->json(['msg' => 'Phone verification code sent via SMS.', 'data' => $user, 'success' => true, 'otp' => $otp,], 200);
            }
        } else {
            $user['token'] = $user->createToken('eventRight')->accessToken;
        }
        return response()->json(['msg' => 'Registered successfully', 'data' => $user, 'success' => true], 200);
    }
    public function forgetPasswordOrganizer(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email',
        ]);
        $user = User::where('email', $request->email)->first();

        $password = rand(100000, 999999);
        if ($user) {
            $content = NotificationTemplate::where('title', 'Reset Password')->first()->mail_content;
            $detail['user_name'] = $user->name;
            $detail['password'] = $password;
            $detail['app_name'] = Setting::find(1)->app_name;
            try {
                $setting = Setting::first();
                // $config = array(
                //     'driver'     => $setting->mail_mailer,
                //     'host'       => $setting->mail_host,
                //     'port'       => $setting->mail_port,
                //     'encryption' => $setting->mail_encryption,
                //     'username'   => $setting->mail_username,
                //     'password'   => $setting->mail_password
                // );
                // Config::set('mail', $config);
                Mail::to($user)->send(new ResetPassword($content, $detail));
            } catch (\Throwable $th) {
                Log::info($th->getMessage());
            }
            User::find($user->id)->update(['password' => Hash::make($password)]);
            return response()->json(['success' => true, 'msg' => 'Please check your email new password will send on it.', 'data' => null], 200);
        } else {
            return response()->json(['success' => false, 'msg' => 'Invalid email ID', 'data' => null], 200);
        }
    }
    public function forgetPassword(Request $request)
    {

        $request->validate([
            'email' => 'bail|required|email',
        ]);
        $user = AppUser::where('email', $request->email)->first();
        $password = rand(100000, 999999);

        if ($user) {
            $content = NotificationTemplate::where('title', 'Reset Password')->first()->mail_content;
            $detail['user_name'] = $user->name;
            $detail['password'] = $password;
            $detail['app_name'] = Setting::find(1)->app_name;

            try {
                $setting = Setting::first();
                // $config = array(
                //     'driver'     => $setting->mail_mailer,
                //     'host'       => $setting->mail_host,
                //     'port'       => $setting->mail_port,
                //     'encryption' => $setting->mail_encryption,
                //     'username'   => $setting->mail_username,
                //     'password'   => $setting->mail_password
                // );
                // Config::set('mail', $config);
                Mail::to($user->email)->send(new ResetPassword($content, $detail));
            } catch (\Throwable $th) {
                Log::info($th->getMessage());
            }
            AppUser::find($user->id)->update(['password' => Hash::make($password)]);
            return response()->json(['success' => true, 'msg' => 'New password send in your email', 'data' => null], 200);
        } else {
            return response()->json(['success' => false, 'msg' => 'Invalid email ID', 'data' => null], 200);
        }
    }

    public function organizationRegister(Request $request)
    {

        $check =  User::where('email', $request->email)->where('status', 1)->first();
        if ($check) {
            return response()->json(['success' => false, 'msg' => 'Email Address Already taken', 'data' => null], 200);
        }
        // $request->validate([
        //     //'email' => ['required', 'email', new UniqueEmailWithStatus],
        //     'confirm_email' => 'bail|required|email|same:email',
        //     'first_name' => 'bail|required',
        //     'last_name' => 'bail|required',
        //     'phone' => 'bail|required',
        //     'password' => 'bail|required|min:6',
        //     'Countrycode' => 'bail|required',
        // ]);

        $verify = Setting::first()->user_verify == 1 ? 0 : 1;
        $data = $request->all();
        $otp = rand(100000, 999999);
        $data['image'] = "defaultuser.png";
        $data['phone'] = $request->Countrycode . $request->phone;
        $data['password'] =  Hash::make($request->password);
        $data['language'] = Setting::first()->language;
        $data['is_verify'] = $verify;
        $data['otp'] = $otp;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Move the image to the public directory
            $image->move(public_path('upload/user'), $imageName);

            $data['image'] = $imageName;
        }

        $user = User::create($data);
        $user->assignRole('Organizer');

        $dataemail['name'] = $user->first_name . " " . $user->last_name;
        $dataemail['email'] = $user->email;
        $dataemail['otp'] = $otp;

        Mail::send(['html' => 'frontend.email.otp'], $dataemail, function ($message) use ($dataemail) {
            $message->to($dataemail['email'])->subject('OTP Verification');
            $message->from('ticketbyksa@gmail.com', 'TicketBy');
        });
        if ($user->is_verify == 0) {
            if (Setting::first()->verify_by == 'email' && Setting::first()->mail_host != NULL) {
                $details = [
                    'url' => url('organizer/VerificationConfirm/' .  $user->id)
                ];
                $setting = Setting::first();
                $config = array(
                    'driver'     => $setting->mail_mailer,
                    'host'       => $setting->mail_host,
                    'port'       => $setting->mail_port,
                    'encryption' => $setting->mail_encryption,
                    'username'   => $setting->mail_username,
                    'password'   => $setting->mail_password
                );
                Config::set('mail', $config);
                Mail::to($user->email)->send(new \App\Mail\VerifyMail($details));
                return response()->json(['msg' => 'Verification link has been sent to your email. Please visit that link to complete the verification.', 'data' => $user, 'success' => true], 200);
            }
            if (Setting::first()->verify_by != 'email' && Setting::first()->twilio_auth_token != NULL) {
                $setting = Setting::first();
                $otp = rand(100000, 999999);
                $to = $user->phone;
                $message = "Your phone verification code is $otp for $setting->app_name.";
                $twilio_sid = $setting->twilio_account_id;
                $twilio_token = $setting->twilio_auth_token;
                $twilio_phone_number = $setting->twilio_phone_number;
                $twilio = new Clients($twilio_sid, $twilio_token);
                $twilio->messages->create(
                    $to,
                    [
                        'from' => $twilio_phone_number,
                        'body' => $message,
                    ]
                );
                $user = User::find($user->id);
                $user->otp = $otp;
                $user->update();
                return response()->json(['msg' => 'Phone verification code sent via SMS.', 'data' => $user, 'success' => true, 'otp' => $otp,], 200);
            }
        } else {
            $user['token'] = $user->createToken('eventRight')->accessToken;
        }
        return response()->json(['msg' => null, 'data' => $user, 'success' => true, 'Countrycode' => $request->Countrycode], 200);
    }

    public function tamara(Request  $request)
    {
        // Set the URL
        $url = 'https://api-sandbox.tamara.co/checkout/payment-types?country=SA';

        // Create the data array
        $data = array(
            "order_id" => "<tamaraOrderId>",
            "order_reference_id" => "<merchantRefOrderId>",
            "order_number" => "<merchantOrderNumber>",
            "event_type" => "order_approved",
            "data" => array()
        );

        // Encode the data array to JSON
        $jsonData = json_encode($data);

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhY2NvdW50SWQiOiI2ZmNmZjFmYS1iMzg5LTQ5MTctYjI3YS1iYjdlMzAxZjk2OTciLCJ0eXBlIjoibWVyY2hhbnQiLCJzYWx0IjoiNzY4YWIwZmY3YzhkYWUxYThjMDI2NDFhMDdjNzMxNmEiLCJyb2xlcyI6WyJST0xFX01FUkNIQU5UIl0sImlhdCI6MTcwMTgwODYzOCwiaXNzIjoiVGFtYXJhIn0.RYGdibYsCDAVrnZG5InagT4Whg_A7ZUqO5zhzzo5Fnqm7wbWCTkADLqN_r_ZyIuKXcYcLbiNo0im8xx7p5guCcCN6XovBRm59gfYhRd9eybNrGrLBkrB-g2E0I5lntyPeNHM0W1XWVgOXGEcz58DSl7hrB8e-TkFAa6xIXO4-XQlOXb_598Xp8LLKlBFzr8v5YfM4V05bGwmziXb15lZjSwpBqp5jUhIQgrdp0z1vG4X1zj7qlQDVQaEBS-ffd9mGkYxpN5Udu0WCCKdtbRZKZIKVnpeSj_pvdCYUYcc4kF3GX5JSxNxKdOCrysbfIuvjylfiv41fUPcUgx9GlVtQQ"
        ));
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        // Execute cURL request
        $response = curl_exec($ch);
        dd($response);
        // Check for errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            // Print the response
            echo $response;
        }

        // Close cURL session
        curl_close($ch);
    }

    public function appleCallback(Request $request)
    {
        Log::info($th->getMessage());
        return $request;
    }

    public function testingLogic(Request $request)
    {
        $dataemail['order'] = Order::find(249);
        $dataemail['event'] = Event::find($dataemail['order']->event_id);
        $dataemail['user'] = AppUser::find($dataemail['order']->customer_id);
        $dataemail['email'] = $dataemail['user']->email;

        Mail::send(['html' => 'emails.ticketbooked'], $dataemail, function ($message) use ($dataemail) {
            $message->to($dataemail['email'])->subject('Ticket Booked');
            $message->from('ticketbyksa@gmail.com', 'TicketBy');
        });

        Mail::to($user->email)->send(new TicketBookOrg($new_ticket->mail_content, $details1, $new_ticket->subject));

        // faris and groom mail 


        Mail::send(['html' => 'emails.notifyticket'], $dataemail, function ($message) use ($dataemail) {
            $message->to($dataemail['email'])->subject('Ticket Booked');
            $message->from('ticketbyksa@gmail.com', 'TicketBy');
        });

        // faris and groom mail 
    }

    // api side api shifted here

    public function createOrder(Request $request)
    {

        // $request->validate([
        //     'event_id' => 'bail|required',
        //     'ticket_id' => 'bail|required',
        //     'quantity' => 'bail|required',
        //     'coupon_discount' => 'bail|required',
        //     'payment' => 'bail|required|numeric',
        //     'tax' => 'bail|required|numeric',
        //     'payment_type' => 'bail|required',
        //     'payment_token' => 'required_if:payment_type,STRIPE,PAYPAL,RAZOR',
        // ]);

        if (
            !isset($request->event_id) || !isset($request->ticket_id) ||  !isset($request->quantity) ||  !isset($request->coupon_discount) ||  !isset($request->payment) ||  !isset($request->tax)
            ||  !isset($request->payment_type)
        ) {
            return response()->json(['success' => false, 'msg' => "missing requried fields ", 'data' => null], 200);
        }
        // dd("jj");
        $data = $request->all();
        $data['order_id'] = '#' . rand(9999, 100000);
        $data['organization_id'] = Event::findOrFail($request->event_id)->user_id;
        $data['customer_id'] = Auth::user()->id;
        if ($request->payment_type == "LOCAL") {
            $data['payment_status'] = 0;
            $data['order_status'] = 'Pending';
        } else {
            $data['payment_status'] = 1;
            $data['order_status'] = 'Complete';
        }
        if ($request->payment_type == 'WALLET') {
            $user = $request->user();
            $user = AppUser::find($user->id);
            if ($user->balance >= $request->payment) {
                $user->withdraw($request->payment, ['event_id' => $request->ticket_id]);
            } else {
                return response()->json(['success' => false, 'message' => 'Insufficient balance']);
            }
        }
        $com = Setting::findOrFail(1, ['org_commission_type', 'org_commission']);
        $p =   $request->payment - $request->tax;
        if ($request->payment_type == "FREE") {
            $data['org_commission']  = 0;
        } else {
            if ($com->org_commission_type == "percentage") {
                $data['org_commission'] =  $p * $com->org_commission / 100;
            } else if ($com->org_commission_type == "amount") {
                $data['org_commission']  = $com->org_commission;
            }
        }

        if ($request->coupon_id != null) {
            $coupon = Coupon::find($request->coupon_id);
            $count = $coupon->use_count + 1;
            $coupon->update(['use_count' => $count]);
            CouponUsageHistory::create([
                'coupon_id' => $request->coupon_id,
                'appuser_id' =>  Auth::guard('userApi')->user()->id
            ]);
            $data['coupon_discount'] = $coupon->discount;
            $data['coupon_id'] = $coupon->id;
        }
        if ($request->payment_type == "STRIPE") {
            $currency_code = Setting::first()->currency;
            $stripe_payment = $currency_code == "USD" || $currency_code == "EUR" || $currency_code == "INR" ? $request->payment * 100 : $request->payment;

            $cur = Setting::find(1)->currency;
            $stripe_secret =  PaymentSetting::find(1)->stripeSecretKey;

            Stripe\Stripe::setApiKey($stripe_secret);
            $stripeDetail =  Stripe\PaymentIntent::create([
                "amount" => $stripe_payment,
                "currency" => $cur,
                // "source" => $request->payment_token,
            ]);
            $data['payment_token'] = $stripeDetail->id;
        }

        $data['book_seats'] = isset($request->book_seats) ? $request->book_seats : null;
        $data['seat_details'] = isset($request->seat_details) ? $request->seat_details : null;
        $data = Order::create($data);
        $seats = explode(',', $data['book_seats']);
        $module = Module::where('module', 'seatmap')->first();
        if ($module && $module->is_enable == 1) {
            foreach ($seats as $key => $value) {
                $seat = \Modules\Seatmap\Entities\Seats::find($value);
                if ($seat) {
                    $seat->update(['type' => 'occupied']);
                }
            }
        }
        $ticket = Ticket::find($request->ticket_id);
        for ($i = 1; $i <= $request->quantity; $i++) {
            $child['ticket_number'] = uniqid();
            $child['ticket_id'] = $request->ticket_id;
            $child['order_id'] = $data->id;
            $child['customer_id'] = Auth::User()->id;
            $child['checkin'] = $ticket->maximum_checkins ?? null;
            $child['paid'] = $request->payment_type == 'LOCAL' ? 0 : 1;
            OrderChild::create($child);
        }

        if (isset($request->tax_data)) {
            foreach (json_decode($request->tax_data) as $value) {
                $tax['order_id'] = $data->id;
                $tax['tax_id'] = $value->tax_id;
                $tax['price'] = $value->price;
                OrderTax::create($tax);
            }
        }

        $user = AppUser::find($data->customer_id);
        $setting = Setting::find(1);

        // for user notification
        $message = NotificationTemplate::where('title', 'Book Ticket')->first()->message_content;
        $detail['user_name'] = $user->name;
        $detail['quantity'] = $request->quantity;
        $detail['event_name'] = Event::find($request->event_id)->name;
        $detail['date'] = Event::find($request->event_id)->start_time->format('d F Y h:i a');
        $detail['app_name'] = $setting->app_name;
        $noti_data = ["{{user_name}}", "{{quantity}}", "{{event_name}}", "{{date}}", "{{app_name}}"];
        $message1 = str_replace($noti_data, $detail, $message);
        $notification = array();
        $notification['organizer_id'] = null;
        $notification['user_id'] = $user->id;
        $notification['order_id'] = $data->id;
        $notification['title'] = 'Ticket Booked';
        $notification['message'] = $message1;
        Notification::create($notification);
        if ($setting->push_notification == 1) {
            (new AppHelper)->sendOneSignal('user', $user->device_token, $message1);
        }
        // for user mail
        $ticket_book = NotificationTemplate::where('title', 'Book Ticket')->first();
        $details['user_name'] = $user->name . ' ' . $user->last_name;
        $details['quantity'] = $request->quantity;
        $details['event_name'] = Event::find($request->event_id)->name;
        $details['date'] = Event::find($request->event_id)->start_time->format('d F Y h:i a');
        $details['app_name'] = $setting->app_name;
        if ($setting->mail_notification == 1) {
            try {
                $setting = Setting::first();
                $config = array(
                    'driver'     => $setting->mail_mailer,
                    'host'       => $setting->mail_host,
                    'port'       => $setting->mail_port,
                    'encryption' => $setting->mail_encryption,
                    'username'   => $setting->mail_username,
                    'password'   => $setting->mail_password
                );
                Config::set('mail', $config);
                $qrcode = $data->order_id;
                Mail::to($user->email)->send(new TicketBook($ticket_book->mail_content, $details, $ticket_book->subject, $qrcode));
            } catch (\Throwable $th) {
                Log::info($th->getMessage());
            }
        }

        // for Organizer notification
        $org =  User::find($data->organization_id);
        $or_message = NotificationTemplate::where('title', 'Organizer Book Ticket')->first()->message_content;
        $or_detail['organizer_name'] = $org->first_name . ' ' . $org->last_name;
        $or_detail['user_name'] = $user->name . ' ' . $user->last_name;
        $or_detail['quantity'] = $request->quantity;
        $or_detail['event_name'] = Event::find($request->event_id)->name;
        $or_detail['date'] = Event::find($request->event_id)->start_time->format('d F Y h:i a');
        $or_detail['app_name'] = $setting->app_name;
        $or_noti_data = ["{{organizer_name}}", "{{user_name}}", "{{quantity}}", "{{event_name}}", "{{date}}", "{{app_name}}"];
        $or_message1 = str_replace($or_noti_data, $or_detail, $or_message);
        $or_notification = array();
        $or_notification['organizer_id'] =  $data->organization_id;
        $or_notification['user_id'] = null;
        $or_notification['order_id'] = $data->id;
        $or_notification['title'] = 'New Ticket Booked';
        $or_notification['message'] = $or_message1;
        Notification::create($or_notification);
        if ($setting->push_notification == 1) {
        }
        // for Organizer mail
        $new_ticket = NotificationTemplate::where('title', 'Organizer Book Ticket')->first();
        $details1['organizer_name'] = $org->first_name . ' ' . $org->last_name;
        $details1['user_name'] = $user->name . ' ' . $user->last_name;
        $details1['quantity'] = $request->quantity;
        $details1['event_name'] = Event::find($request->event_id)->name;
        $details1['date'] = Event::find($request->event_id)->start_time->format('d F Y h:i a');
        $details1['app_name'] = $setting->app_name;
        // if ($setting->mail_notification == 1) {
        //     try {
        //         $setting = Setting::first();
        //         $config = array(
        //             'driver'     => $setting->mail_mailer,
        //             'host'       => $setting->mail_host,
        //             'port'       => $setting->mail_port,
        //             'encryption' => $setting->mail_encryption,
        //             'username'   => $setting->mail_username,
        //             'password'   => $setting->mail_password
        //         );
        //         Config::set('mail', $config);
        //         Mail::to($user->email)->send(new TicketBookOrg($new_ticket->mail_content, $details1, $new_ticket->subject));
        //     } catch (\Throwable $th) {
        //         Log::info($th->getMessage());
        //     }
        // }

        // $message = "Your order has been placed on TicketBy.";
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => 'https://api.taqnyat.sa/v1/messages',
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => '',
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 0,
        //   CURLOPT_FOLLOWLOCATION => true,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => 'POST',
        //   CURLOPT_POSTFIELDS =>'{
        //     "recipients": [
        //         '.$user->phone.'
        //     ],
        //     "body":"'.$message.'",
        //     "sender":"DARLANA"
        // }',
        //   CURLOPT_HTTPHEADER => array(
        //     'Content-Type: application/json',
        //     'Authorization: Bearer 647c5f992f7202a353e21ca58ecfa788'
        //   ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);
        // $responseData = json_decode($response, true);

        $dataemail['order'] = Order::find(249);
        $dataemail['event'] = Event::find($dataemail['order']->event_id);
        $dataemail['user'] = AppUser::find($dataemail['order']->customer_id);
        $dataemail['email'] = $dataemail['user']->email;

        Mail::send(['html' => 'emails.ticketbooked'], $dataemail, function ($message) use ($dataemail) {
            $message->to($dataemail['email'])->subject('Ticket Booked');
            $message->from('ticketbyksa@gmail.com', 'TicketBy');
        });


        // faris and groom mail 


        Mail::send(['html' => 'emails.notifyticket'], $dataemail, function ($message) use ($dataemail) {
            $message->to($dataemail['email'])->subject('Ticket Booked');
            $message->from('ticketbyksa@gmail.com', 'TicketBy');
        });

        // faris and groom mail 

        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    }
    public function delete($id)
    {
        $remove_child = OrderChild::where('order_id', $id)->forceDelete();
        $remove_order = Order::where('id', $id)->forceDelete();
        return true;
    }

     public function webUserLogin ( Request $request )
    {
        $username = $request->user_name ; 
         $setting = Setting::first();
        $user = AppUser::where('email',$request->user_name)->where('status',1)->first();
        if(is_null($user))
        {
            $user = AppUser::where('phone',$request->user_name)->where('status',1)->first();
        }
        if(is_null($user))
        {
            return response()->json("invalid user_name");
        }
        
         if($user)
        {
            $otp = rand(1000, 9999);
            
            $to = str_replace('+966', '', $user->phone);
            $message = "Your phone verification code is $otp for $setting->app_name.";
            $user = AppUser::find($user->id);
            $dataemail['name'] = $user->name;
            $dataemail['email'] = $user->email;
            $dataemail['otp'] = $otp;

           
            if (true) {

                
                $dataemail['name'] = $user->name;
                $dataemail['email'] = $user->email;
                $dataemail['otp'] = $otp;
                try {
            $otp = rand(1000, 9999);

         
            $message = "Your phone verification code is $otp for $setting->app_name.";
                $curl = curl_init();
                            
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://api.taqnyat.sa/v1/messages',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>'{
                    "recipients": [
                        '.$to.'
                    ],
                    "body":"'.$message.'",
                    "sender":"TICKETBY"
                }',
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer 17bcd048f6bad60a6812030bd1c1c5c2'
                  ),
                ));
                
                $response = curl_exec($curl);
                
                curl_close($curl);
                $responseData = json_decode($response, true);
                 //  var_dump($responseData);
                } catch (\Throwable $th) {
                    Log::info("thaqniyat error");
                    Log::info($th->getMessage());
                }

                 $data = array('name' => "TicketBy", 'email' => $user->email, "otp" => $otp);
                Mail::send(['html' => 'frontend.email.otp'], $data, function ($message) use ($data) {
                    $message->to($data['email'])->subject('OTP Verification');
                    $message->from('ticketbyksa@gmail.com', 'TicketBy');
                });
                $user->otp = $otp;
                $user->update();
       
            }    
        }

         return response()->json(['msg' => 'Otp sent', 'data' => $user, 'success' => true], 200);
    }
      public function webUserApp ( Request $request )
    {
        $username = $request->user_name ; 
         $setting = Setting::first();
        $user = AppUser::where('email',$request->user_name)->where('status',1)->first();
        if(!is_null($user))
        {
             return response()->json(['msg' => 'email is entered', 'success' => false], 200);
        }
        if(is_null($user))
        {
            $user = AppUser::where('phone',$request->user_name)->where('status',1)->first();
        }
        if(is_null($user))
        {
            return response()->json(['msg' => 'invalid user', 'success' => false], 200);
            
        }
        
        if($user)
        {
            $otp = rand(1000, 9999);
            
            $to = str_replace('+966', '', $user->phone);
            $message = "Your phone verification code is $otp for $setting->app_name.";
            $user = AppUser::find($user->id);
            $dataemail['name'] = $user->name;
            $dataemail['email'] = $user->email;
            $dataemail['otp'] = $otp;

           
            if (true) {

                
                $dataemail['name'] = $user->name;
                $dataemail['email'] = $user->email;
                $dataemail['otp'] = $otp;
                try {
            $otp = rand(1000, 9999);

         
            $message = "Your phone verification code is $otp for $setting->app_name.";
                $curl = curl_init();
                            
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://api.taqnyat.sa/v1/messages',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>'{
                    "recipients": [
                        '.$to.'
                    ],
                    "body":"'.$message.'",
                    "sender":"TICKETBY"
                }',
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer 17bcd048f6bad60a6812030bd1c1c5c2'
                  ),
                ));
                
                $response = curl_exec($curl);
                
                curl_close($curl);
                $responseData = json_decode($response, true);
                   
                } catch (\Throwable $th) {
                    Log::info("thaqniyat error");
                    Log::info($th->getMessage());
                }

                 $data = array('name' => "TicketBy", 'email' => $user->email, "otp" => $otp);
                Mail::send(['html' => 'frontend.email.otp'], $data, function ($message) use ($data) {
                    $message->to($data['email'])->subject('OTP Verification');
                    $message->from('ticketbyksa@gmail.com', 'TicketBy');
                });
                $user->otp = $otp;
                $user->update();
       
            }    
        }

        return response()->json(['msg' => 'OTP sent', 'data' =>$user , 'success' => true], 200);
    }
}
