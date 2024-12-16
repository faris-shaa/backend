<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Models\AppUser;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderTax;
use App\Models\PosOrder;
use App\Models\Tax;
use App\Models\City;
use App\Models\Review;
use App\Models\OrderChild;
use App\Http\Controllers\AppHelper;
use App\Models\PaymentSetting;
use App\Models\Ticket;
use App\Models\EventTime;
use App\Models\Currency;
use App\Models\Setting;
use App\Mail\ResetPassword;
use App\Mail\TicketBook;
use App\Mail\TicketBookOrg;
use App\Models\CouponUsageHistory;
use App\Models\NotificationTemplate;
use App\Models\Notification;
use App\Models\EventReport;
use App\Models\Module;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Twilio\Rest\Client as Clients;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Stripe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\Banner;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use stdClass ;
use Illuminate\Support\Str;


class ApiController extends Controller
{
    public function __construct()
    {
       // (new AppHelper)->mailConfig();
    }

    public function userLogin(Request $request)
    {
    
        $request->validate([
            'email' => 'bail|required|email',
            'provider' => 'bail|required',
            'password' => 'bail|required',
        ]);
        if ($request->provider == "LOCAL") {
            $userdata = array('email' => $request->email, 'status' => 1, 'password' => $request->password);
            if (Auth::guard('appuser')->attempt($userdata)) {
                $user = Auth::guard('appuser')->user();
                $check_user = AppUser::findOrFail($user->id) ; 
                if($check_user->status == 0 )
                {
                    Auth::logout();
                    return response()->json(['msg' => 'User not found', 'data' => null, 'success' => false], 200);
                    
                } 
               
                if ($user->is_verify == 0 &&  Setting::first()->user_verify == 1) {
                    if (Setting::first()->verify_by == 'email' && Setting::first()->mail_host != NULL) {
                        $details = [
                            'id' => $user->id,
                        ];
                        // $setting = Setting::first();
                        // $config = array(
                        //     'driver'     => $setting->mail_mailer,
                        //     'host'       => $setting->mail_host,
                        //     'port'       => $setting->mail_port,
                        //     'encryption' => $setting->mail_encryption,
                        //     'username'   => $setting->mail_username,
                        //     'password'   => $setting->mail_password
                        // );
                        // Config::set('mail', $config);
                        $details = [
                            'url' => url('user/VerificationConfirm/' .  $user->id)
                        ];
                        //dd($user->email);
                        Mail::to($user->email)->send(new \App\Mail\VerifyMail($details));
                        return response()->json(['msg' => 'Verification link has been sent to your email. Please visit that link to complete the verification.', 'data' => $user, 'success' => true], 200);
                    }
                    if (Setting::first()->verify_by != 'email') {
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
                        $user = AppUser::find($user->id);
                        $user->otp = $otp;
                        $user->update();
                        return response()->json(['msg' => 'Phone verification code sent via SMS.', 'data' => $user, 'success' => true, 'otp' => $otp], 200);
                    }
                } else {
                    AppUser::find($user->id)->update(['device_token' => $request->device_token]);
                    $user['token'] = $user->createToken('eventRight')->accessToken;
                    $user['is_verify'] = 1;
                    return response()->json(['msg' => 'Login successfully', 'data' => $user, 'success' => true], 200);
                }
            } else {
                return response()->json(['msg' => 'Invalid Username or password', 'data' => null, 'success' => false]);
            }
        }
    }

    public function userRegister(Request $request)
    {
        
        $request->validate([
            'first_name' => 'bail|required',
            'last_name' => 'bail|required',
            'email' => 'bail|required|email|unique:app_user|unique:users',
            'password' => 'bail|required|min:6',
            'phone' => 'bail|required',
            'Countrycode' => 'bail|required',
        ]);
        
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
     
        $user = AppUser::create($data);
        $otp = rand(100000, 999999);
        $dataemail['name'] = $user->first_name." ".$user->last_name;
        $dataemail['email'] = $user->email;
        $dataemail['otp'] = $otp;  
        
        
        
        Mail::send(['html'=>'frontend.email.otp'], $dataemail, function($message) use ($dataemail) {
        $message->to($dataemail['email'])->subject
            ('OTP Verification');
        $message->from('ticketbyksa@gmail.com','TicketBy');
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

                if($dataemail['email'] != "hitarth.rc@gmail.com")
                {
                    $user->otp = $otp;     
                }
                
                $user->update();
                return response()->json(['msg' => 'Phone verification code sent via SMS.', 'data' => $user, 'success' => true, 'otp' => $otp,], 200);
            }
        } else {
            $user['token'] = $user->createToken('eventRight')->accessToken;
        }
        return response()->json(['msg' => 'Registered successfully', 'data' => $user, 'success' => true], 200);
    }

    public function organization()
    {
        $users = User::role('Organizer')->where('is_delete',0)->where('status', 1)->where('is_organizer_approve',1)->orderBy('id', 'DESC')->get()->makeHidden(['created_at', 'updated_at']);

        foreach ($users as $value) {
            
            $value->event_count =   Event::with(['ticket'])
        ->where([['status', 1], ['is_deleted', 0], ['user_id',$value->id]])
        ->count();
            if (Auth::check()) {
                if (in_array(Auth::user()->id, $value->followers)) {
                    $value->isFollow = true;
                } else {
                    $value->isFollow = false;
                }
            } else {
                $value->isFollow = false;
            }
        }
        return response()->json(['msg' => null, 'data' => $users, 'success' => true], 200);
    }

    public function events(Request $request)
    {
        $event = array();
        $timezone = Setting::findOrFail(1)->timezone;
        $date = Carbon::now($timezone);

        if(isset($request->city_id))
        {
           $data['events'] = Event::with(['ticket'=> function($query) {
    $query->where('is_deleted', 0);
}])->where('city_id',$request->city_id)
            ->where([['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d H:i:s')]])
            ->orderBy('created_at', 'DESC')->get()->makeHidden(['created_at', 'updated_at']);
        }
        else
        {
             $data['events'] = Event::with(['ticket'=> function($query) {
    $query->where('is_deleted', 0);
}])
            ->where([['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d H:i:s')]])
            ->orderBy('created_at', 'DESC')->get()->makeHidden(['created_at', 'updated_at']);
        }

       
        foreach ($data['events'] as $value) {
            $value->description =  str_replace("&nbsp;", " ", strip_tags($value->description));
            $value->time = $value->start_time->format('d F Y h:i a');
            $value->date = $value->start_time->format('d F Y');
            $value->endDate = $value->end_time->format('d F Y');
            $value->startTime = $value->start_time->format('h:i a');
            $value->endTime = $value->end_time->format('h:i a');
            $value->share_url = url('/event/' . $value->id. '/' . str_replace(' ', '', $value->name));
            if (Auth::guard('userApi')->check()) {
                if (in_array($value->id, array_filter(explode(',', Auth::guard('userApi')->user()->favorite)))) {
                    $value->isLike = true;
                } else {
                    $value->isLike = false;
                }
            } else {
                $value->isLike = false;
            }
        }
        if ($request->searchString != null && $request->searchDate == null) {
            $events  = Event::with(['category:id,name'])
                ->where([['address', 'LIKE', "%$request->searchString%"], ['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d')]])
                ->orWhere([['name', 'LIKE', "%$request->searchString%"], ['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d')]])
                ->orWhere([['description', 'LIKE', "%$request->searchString%"], ['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d')]])->get();
            foreach ($events as $key => $value) {
                $value->share_url = url('/event/' . $value->id. '/' . str_replace(' ', '', $value->name));
                array_push($event, $value->id);
            }
        }
        if ($request->searchDate != null && $request->searchString == null) {
            $events  = Event::where('start_time', '<=', Carbon::parse($request->searchDate)->format('Y-m-d'))
                ->where('end_time', '>=', Carbon::parse($request->searchDate)->format('Y-m-d'))
                ->where(['status', 1], ['is_deleted', 0], ['event_status', 'Pending'])
                ->get();
            foreach ($events as $key => $value) {
                $value->share_url = url('/event/' . $value->id. '/' . str_replace(' ', '', $value->name));
                array_push($event, $value->id);
            }
        }
        if ($request->searchString != null && $request->searchDate != null) {
            $events  = Event::with(['category:id,name'])
                ->where([['address', 'LIKE', "%$request->searchString%"], ['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d')], ['start_time', '<', $date->format('Y-m-d')]])
                ->orWhere([['name', 'LIKE', "%$request->searchString%"], ['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d')], ['start_time', '<', $date->format('Y-m-d')]])
                ->orWhere([['description', 'LIKE', "%$request->searchString%"], ['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d')], ['start_time', '<', $date->format('Y-m-d')]])->get();
            foreach ($events as $key => $value) {
                $value->share_url = url('/event/' . $value->id. '/' . str_replace(' ', '', $value->name));
                array_push($event, $value->id);
            }
        }

        if ($request->searchString != null || $request->searchDate != null) {
            $events = Event::with(['ticket'=> function($query) {
    $query->where('is_deleted', 0);
}])->whereIn('id', $event)->where([['status', 1], ['is_deleted', 0]])->orderBy('start_time', 'ASC')->get();
            if ($events != null) {
                foreach ($events as $key => $value) {
                    $value->description =  str_replace("&nbsp;", " ", strip_tags($value->description));
                    $value->time = $value->start_time->format('d F Y h:i a');
                    $value->share_url = url('/event/' . $value->id. '/' . str_replace(' ', '', $value->name));
                    $value->date = $value->start_time->format('d F Y');
            $value->endDate = $value->end_time->format('d F Y');
            $value->startTime = $value->start_time->format('h:i a');
            $value->endTime = $value->end_time->format('h:i a');
                    if (Auth::guard('userApi')->check()) {
                        if (in_array($value->id, array_filter(explode(',', Auth::guard('userApi')->user()->favorite)))) {
                            $value->isLike = true;
                        } else {
                            $value->isLike = false;
                        }
                    } else {
                        $value->isLike = false;
                    }
                }
                $data['events'] = $events;
                return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
            }
        }
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }

    public function EventFrmCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'bail|required',
        ]);
        $timezone = Setting::findOrFail(1)->timezone;
        $date = Carbon::now($timezone);

        $data =  Event::where([['status', 1], ['is_deleted', 0], ['category_id', $request->category_id], ['end_time', '>=', $date->format('Y-m-d H:i:s')]])->get();
        
        
        return $data;
        if ($request->lat != null &&  $request->lang != null) {
            $lat = $request->lat;
            $lang = $request->lang;
            $event = array();
            $radius = 50;
            $results = DB::select(DB::raw('SELECT id,name, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(' . $lang . ') ) + sin( radians(' . $lat . ') ) * sin( radians(lat) ) ) ) AS distance FROM events HAVING distance < ' . $radius . '  ORDER BY distance'));
            if (count($results) > 0) {
                foreach ($results as $q) {
                    array_push($event, $q->id);
                }
            }
            $data = $data->whereIn('id', $event);
        }

        $data = $data->orderBy('id', 'DESC')->get();
        foreach ($data as $value) {
            $value->description =  str_replace("&nbsp;", " ", strip_tags($value->description));
            $value->time = $value->start_time->format('d F Y h:i a');
            if (Auth::guard('userApi')->check()) {
                if (in_array($value->id, array_filter(explode(',', Auth::guard('userApi')->user()->favorite)))) {
                    $value->isLike = true;
                } else {
                    $value->isLike = false;
                }
            } else {
                $value->isLike = false;
            }
        }
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }

    public function searchFreeEvent(Request $request)
    {
        $request->validate([
            'free_event' => 'bail|required|numeric',
            'category_id' => 'bail|required|numeric',
        ]);
        $timezone = Setting::findOrFail(1)->timezone;
        $date = Carbon::now($timezone);
        $data = Event::where([['status', 1], ['is_deleted', 0], ['category_id', $request->category_id], ['start_time', '>=', $date->format('Y-m-d H:i:s')]]);
        if ($request->free_event == 1) {
            $ar_event = array();
            $ar = Event::where([['status', 1], ['is_deleted', 0], ['start_time', '>=', $date->format('Y-m-d H:i:s')]])->get();
            foreach ($ar as $value) {
                $ticket = Ticket::where([['status', 1], ['is_deleted', 0], ['event_id', $value->id], ['type', 'free']])->get();
                if (count($ticket) > 0) {
                    array_push($ar_event, $value->id);
                }
            }
            $data = $data->whereIn('id', $ar_event);
        }
        if ($request->lat != null &&  $request->lang != null) {
            $lat = $request->lat;
            $lang = $request->lang;
            $event = array();
            $radius = 50;
            $results = DB::select(DB::raw('SELECT id,name, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(' . $lang . ') ) + sin( radians(' . $lat . ') ) * sin( radians(lat) ) ) ) AS distance FROM events HAVING distance < ' . $radius . '  ORDER BY distance'));
            if (count($results) > 0) {
                foreach ($results as $q) {
                    array_push($event, $q->id);
                }
            }
            $data = $data->whereIn('id', $event);
        }
        $data = $data->orderBy('id', 'DESC')->get();
        foreach ($data as $value) {
            $value->description =  str_replace("&nbsp;", " ", strip_tags($value->description));
            $value->time = $value->start_time->format('d F Y h:i a');
            if (Auth::guard('userApi')->check()) {
                if (in_array($value->id, array_filter(explode(',', Auth::guard('userApi')->user()->favorite)))) {
                    $value->isLike = true;
                } else {
                    $value->isLike = false;
                }
            } else {
                $value->isLike = false;
            }
        }
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }

    public function category()
    {
        $data = Category::where('status', 1)->orderBy('id', 'DESC')->get();
        $categorylist = array();
        foreach ($data as $key => $datacat) {
            $datacat->event_count = Event::where('category_id',$datacat->id)->count();

            $categorylist[] = $datacat;
        }

        return response()->json(['msg' => null, 'data' => $categorylist, 'success' => true], 200);
    }

    public function organizationDetail($id)
    {
        $data = User::findOrFail($id);
        $data->event = Event::where([['user_id', $id], ['is_deleted', 0], ['status', 1]])->orderBy('id', 'DESC')->get();
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }

    public function eventDetail($id)
    {

        $data = Event::with(['ticket' => function($query) {
    $query->where('is_deleted', 0);
}, 'organization:id,first_name,last_name,image,email'])->findOrFail($id);
        $data->hasTag = explode(',', $data->tags);
        $data->description =  str_replace("&nbsp;", " ", strip_tags($data->description));
        $data->recent_event = Event::where([['category_id', $data->category_id], ['is_deleted', 0], ['status', 1] , ['is_show_dashboard',1]])->orderBy('start_time', 'ASC')->get();
        $data->date = $data->start_time->format('d F Y');
        $data->endDate = $data->end_time->format('d F Y');
        $data->startTime = $data->start_time->format('h:i a');
        $data->endTime = $data->end_time->format('h:i a');
        $data->gallery = array_filter(explode(',', $data->gallery));
        
        $data->share_url = url('/event/' . $data->id. '/' . str_replace(' ', '', $data->name));
        foreach ($data->recent_event  as $value) {
            $value->time = $value->start_time->format('d F Y h:i a');
            if (Auth::guard('userApi')->check()) {
                if (in_array($value->id, array_filter(explode(',', Auth::guard('userApi')->user()->favorite)))) {
                    $value->isLike = true;
                } else {
                    $value->isLike = false;
                }
            } else {
                $value->isLike = false;
            }
        }
        if (Auth::guard('userApi')->check()) {
            if (in_array(Auth::guard('userApi')->user()->id, $data->organization->followers)) {
                $data->organization->isFollow = true;
            } else {
                $data->organization->isFollow = false;
            }
            if (in_array($id, array_filter(explode(',', Auth::guard('userApi')->user()->favorite)))) {
                $data->isLike = true;
            } else {
                $data->isLike = false;
            }
        } else {
            $data->organization->isFollow = false;
            $data->isLike = false;
        }
        $all_ticket = Ticket::where([['event_id', $id], ['is_deleted', 0], ['status', 1]])->sum('quantity');
        $ticket_array = array();
        foreach ($data->ticket as $key => $value) {

            $order_id = Order::where('event_id', $value->event_id)->where('payment_status',1)->where('order_status','Complete')->pluck('id')->toArray();
            $value->sold = OrderChild::whereIn('order_id',$order_id)->count();
            $ticket_array[] = $value ; 
        }
        $data->ticket= $ticket_array;
        $use_ticket = Order::where('event_id', $id)->sum('quantity');
        if ($all_ticket == 0) {
            $data->sold_out = false;
        } else {
            if ($all_ticket == $use_ticket) {
                $data->sold_out = true;
            } else {
                $data->sold_out = false;
            }
        }

        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }

    public function ticketDetail($id)
    {
        $data = Ticket::findOrFail($id)->makeHidden(['created_at', 'updated_at']);
        $event = Event::findOrFail($data->event_id);
        $data->event_name = $event->name;
        $data->event_image_url = asset('/images/upload/' . $event->image);
        $data->organization = User::findOrFail($event->user_id);
        $data->use_ticket = (int)Order::where('ticket_id', $id)->sum('quantity');
        $data->startTime = $data->start_time->format('Y-m-d h:i a');
        $data->endTime = $data->end_time->format('Y-m-d h:i a');
        if ($data->quantity <= $data->use_ticket) {
            $data->sold_out = true;
        } else {
            $data->sold_out = false;
        }
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }

    // Multiple ticket details 

    public function ticketDetailMultiple(Request $request)
    {   
        $request->validate([
            'ticket_id' => 'bail|required'
        ]);

        $data_array = array();
        foreach ($request->ticket_id as $key => $id) {
           $data = Ticket::findOrFail($id)->makeHidden(['created_at', 'updated_at']);
            $event = Event::findOrFail($data->event_id);
            $data->event_name = $event->name;
            $data->event_image_url = asset('/images/upload/' . $event->image);
            $data->organization = User::findOrFail($event->user_id);
            $data->use_ticket = (int)Order::where('ticket_id', $id)->sum('quantity');
            $data->startTime = $data->start_time->format('Y-m-d h:i a');
            $data->endTime = $data->end_time->format('Y-m-d h:i a');
            if ($data->quantity <= $data->use_ticket) {
                $data->sold_out = true;
            } else {
                $data->sold_out = false;
            }
            $data_array[] = $data ; 
        }
        
        return response()->json(['msg' => null, 'data' => $data_array, 'success' => true], 200);
    }


    public function eventTickets($id)
    {
        $event = Event::findOrFail($id);
        $data['event_name'] = $event->name;
        $data['event_image_url'] = asset('/images/upload/' . $event->image);
        $data['organization'] = User::findOrFail($event->user_id);
        $timezone = Setting::findOrFail(1)->timezone;
        $date = Carbon::now($timezone);
        // , ['end_time', '>=', $date->format('Y-m-d H:i:s')], ['start_time', '<=', $date->format('Y-m-d H:i:s')]
        $data['ticket'] = Ticket::where([['event_id', $id], ['is_deleted', 0], ['status', 1]])
            ->orderBy('id', 'DESC')->get();

        foreach ($data['ticket'] as $value) {
            $value->use_ticket = Order::where('ticket_id', $value->id)->sum('quantity');
            $value->startTime = $value->start_time->format('Y-m-d h:i a');
            $value->endTime = $value->end_time->format('Y-m-d h:i a');
            if ($value->use_ticket == $value->quantity) {
                $value->sold_out = true;
            } else {
                $value->sold_out = false;
            }
            $value->soldTickets =  (string)$value->use_ticket ; 
        }
        $data['module'] = Module::where('module', 'Seatmap')->first();

        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }



    public function reportEvent(Request $request)
    {
        $request->validate([
            'event_id' => 'bail|required',
            'email' => 'bail|required|email',
            'reason' => 'bail|required',
            'message' => 'bail|required',
        ]);
        $report = EventReport::create($request->all());
        return response()->json(['msg' => null, 'data' => $report, 'success' => true], 200);
    }

    public function categoryEvent()
    {
        $data = Category::where('status', 1)->orderBy('id', 'DESC')->get();
        foreach ($data as $value) {
            $value->events = Event::where([['status', 1], ['is_deleted', 0], ['category_id', $value->id]])->orderBy('id', 'DESC')->get();
        }
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }

    public function userProfile()
    {
        (new AppHelper)->eventStatusChange();
        $data = Auth::user();
        $data->likeCount = count(array_filter(explode(',', $data->favorite)));
        $data->totalTicket = Order::where('customer_id', $data->id)->count();
        $data->followingCount = count(array_filter(explode(',', $data->following)));
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }

    public function userLikes()
    {

        $data = Event::whereIn('id', array_filter(explode(',', Auth::user()->favorite)))->where([['status', 1], ['is_deleted', 0]])->orderBy('id', 'DESC')->get();
        foreach ($data as $value) {
            $value->description =  str_replace("&nbsp;", " ", strip_tags($value->description));
            $value->time = $value->start_time->format('d F Y h:i a');
        }
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }

    public function userFollowing()
    {
        $data = User::whereIn('id', array_filter(explode(',', Auth::user()->following)))
                ->with(['events' => function ($query) {
                    $query->where('event_status', '!=', 'Cancel')
                          ->where('is_deleted', 0);
                }])->orderBy('id', 'DESC')->get();
        foreach ($data as $value) {
            foreach ($value->events as $event) {
                $event->description =  str_replace("&nbsp;", " ", strip_tags($event->description));
                $event->time = $event->start_time->format('d F Y h:i a');
                $event->share_url = url('/event/' . $event->id. '/' . str_replace(' ', '', $event->name));
                if (Auth::guard('userApi')->check()) {
                    if (in_array($event->id, array_filter(explode(',', Auth::guard('userApi')->user()->favorite)))) {
                        $event->isLike = true;
                    } else {
                        $event->isLike = false;
                    }
                } else {
                    $event->isLike = false;
                }
            }
            if (Auth::check()) {
                if (in_array(Auth::user()->id, $value->followers)) {
                    $value->isFollow = true;
                } else {
                    $value->isFollow = false;
                }
            } else {
                $value->isFollow = false;
            }
        }
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }

    public function editUserProfile(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'last_name' => 'bail|required',
        ]);
        AppUser::findOrFail(Auth::user()->id)->update($request->all());
        $user = AppUser::findOrFail(Auth::user()->id);
        return response()->json(['msg' => 'Profile Update successfully', 'data' => $user, 'success' => true], 200);
    }

    public function editImage(Request $request)
    {
        $request->validate([
            'image' => 'bail|required',
        ]);

        if (isset($request->image)) {
            $image_name = (new AppHelper)->saveApiImage($request);
            AppUser::findOrFail(Auth::user()->id)->update(['image' => $image_name]);
            $user = AppUser::findOrFail(Auth::user()->id) ; 
            return response()->json(['msg' => null, 'data' => $user, 'success' => true], 200);
        } else {
            return response()->json(['msg' => null, 'data' => $user, 'success' => false], 200);
        }
    }

    public function addFavorite(Request $request)
    {
        $request->validate([
            'event_id' => 'bail|required',
        ]);
        $users = AppUser::findOrFail(Auth::user()->id);
        $likes = array_filter(explode(',', $users->favorite));
        if (count(array_keys($likes, $request->event_id)) > 0) {
            if (($key = array_search($request->event_id, $likes)) !== false) {
                unset($likes[$key]);
            }
            $msg = "Remove from Bookmark!";
        } else {
            array_push($likes, $request->event_id);
            if($users->language == "English")
            {
                $msg = "Add in Bookmark!";
            }
            else
            {
                $msg = "أضف في الإشارة المرجعية!";
            }
            
        }
        $client = AppUser::findOrFail(Auth::user()->id);
        $client->favorite = implode(',', $likes);
        $client->update();

        return response()->json(['msg' => $msg, 'data' => null, 'success' => true], 200);
    }

    public function addFollowing(Request $request)
    {
        $request->validate([
            'user_id' => 'bail|required',
        ]);
        $users = AppUser::findOrFail(Auth::user()->id);
        $likes = array_filter(explode(',', $users->following));
        if (count(array_keys($likes, $request->user_id)) > 0) {
            if (($key = array_search($request->user_id, $likes)) !== false) {
                unset($likes[$key]);
            }
            $msg = "Remove from following list!";
        } else {
            array_push($likes, $request->user_id);
            $msg = "Add in following!";
        }
        $client = AppUser::findOrFail(Auth::user()->id);
        $client->following = implode(',', $likes);
        $client->update();
        return response()->json(['msg' => $msg, 'data' => null, 'success' => true], 200);
    }

    public function checkCode(Request $request)
    {
   
        $request->validate([
            'coupon_code' => 'bail|required',
            'event_id' => 'bail|required',
            'amount' => 'bail|required',
        ]);
        
        //New Code
        $total = $request->amount;
        $date = Carbon::now()->format('Y-m-d');
        $coupon = Coupon::where([['coupon_code', $request->coupon_code], ['status', 1], ['event_id', $request->event_id]])->first();
        if ($coupon) {
            $couponHistory = CouponUsageHistory::where([['coupon_id', $coupon->id], ['appuser_id', Auth::guard('userApi')->user()->id]])->get();
            
            if (count($couponHistory) >= $coupon->max_use ) {
                return response([
                    'success' => false,
                    'message' => 'This coupon is reached max use!'
                ]);
            }
            if (Carbon::parse($date)->between(Carbon::parse($coupon->start_date), Carbon::parse($coupon->end_date))) {
                if ($coupon->max_use > $coupon->use_count) {
                    if ($total > $coupon->minimum_amount) {

                         if ($coupon->discount_type == 0) {
                            $discount = $total * ($coupon->discount / 100);
                            $percentage_value = $coupon->discount ; 
                        } else {
                            $discount = $coupon->discount;
                            $percentage_value = $total / $discount ; 
                        }
                        if ($discount > $coupon->maximum_discount) {
                            $discount = $coupon->maximum_discount;
                        }
                        $subtotal = $total - $discount;

                        return response([
                            'success' => true,
                            'data' => [
                                'total_price' => $subtotal,
                                'total' => $total,
                                'discount' => $coupon->discount,
                                'applied_discount' => $discount,
                                'coupon_id' => $coupon->id,
                                'coupon_type' => $coupon->discount_type
                            ],
                            'message' => 'coupon apply successful.'
                        ]);
                    } else {
                        return response([
                            'success' => false,
                            'message' => 'Invalid amount.'
                        ]);
                    }
                } else {
                    return response([
                        'success' => false,
                        'message' => 'This coupon is reached max use!'
                    ]);
                }
            } else {
                return response([
                    'success' => false,
                    'message' => 'This coupon is expire!'
                ]);
            }
        } else {
            return response([
                'success' => false,
                'message' => 'Invalid Coupon code for this event!'
            ]);
        }
    }

    public function  allCoupon()
    {
        $data = Coupon::where('status', 1)->orderBy('id', 'DESC')->get();
        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    }

    public function userNotification()
    {
        (new AppHelper)->eventStatusChange();
        $data = Notification::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        foreach ($data as $value) {
            $order = Order::find($value->order_id);
            if ($order) {
                $event = $order->event_id;
                $eventData = Event::find($event);
                if($eventData) {
                    $value->event_image = url('images/upload') . '/' . $eventData->image;
                } else {
                    $value->event_image = null;
                }
            } else {
                $value->event_image = null;
            }
        }
        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    }

    public function addReview(Request $request)
    {
        $request->validate([
            'event_id' => 'bail|required',
            'order_id' => 'bail|required',
            'message' => 'bail|required',
            'rate' => 'bail|required|numeric',
        ]);
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['organization_id'] = Event::findOrFail($request->event_id)->user_id;
        $data['status'] = 0;
        $review = Review::create($data);
        return response()->json(['success' => true, 'msg' => null, 'data' => $review], 200);
    }

    public function orderTax($id)
    {
        $organizer = Event::findOrFail($id)->user_id;
        $data_array = Tax::where([ ['status', 1], ['allow_all_bill', 1]])->orderBy('id', 'DESC')->get()->makeHidden(['created_at', 'updated_at']);
       $data =array();
        foreach ($data_array as $key => $value) {
            if(isset($request->order_amount) )
            {
                if($value->amount_type == "percentage")
                {
                    $value->percentage_value = $value->price; 
                }
                else
                {
                    $value->percentage_value =  ($value->price * 100 ) / $request->order_amount ;
                }
                 
            }
            else
            {
                $value->percentage_value = $value->price;     
            }
            
            $data[] =$value ; 
        }
        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    }

    // Multiple Order tax api  
    public function orderTaxMultiple(Request $request)
    {
        $data_array = array();
        foreach ($request->event_id as $key => $id) {
             $organizer = Event::findOrFail($id)->user_id;
             $data_array = Tax::where([['user_id', $organizer], ['status', 1], ['allow_all_bill', 1]])->orderBy('id', 'DESC')->get()->makeHidden(['created_at', 'updated_at']);
        }
       
        return response()->json(['success' => true, 'msg' => null, 'data' => $data_array], 200);
    }

    public function createOrderMultiple ( Request $request )
    {   
        $order_id_array = array();
              Log::info($request->all());
      foreach ($request->order as $key => $order_request) {
            $order_request = (object) $order_request; 

            $ticekt = Ticket::find($order_request->ticket_id);
            $event = Event::find($order_request->event_id);
             $order_id = Order::where([['order_status', 'Complete']])->where('order_status', "Complete")->where('payment_status', 1)->pluck('id')->toArray();
                $sold_ticket_count = OrderChild::whereIn('order_id', $order_id)->where('ticket_id', $ticekt->id)->count();
                if ($sold_ticket_count + $order_request->quantity >= $ticekt->quantity   && $event->is_repeat == 0) {
                    $ticekt->update(['status' => 0]);
                    return response()->json(['success' => false, 'msg' => "ticekts soled out", 'data' => null], 200);
                }
               
             if(!isset($order_request->event_id) || !isset($order_request->ticket_id) ||  !isset($order_request->quantity) ||  !isset($order_request->coupon_discount) ||  !isset($order_request->payment) ||  !isset($order_request->tax)
                ||  !isset($order_request->payment_type))
                {
                    return response()->json(['success' => false, 'msg' => "missing requried fields ", 'data' => null], 200);
                }
                // dd("jj");
                $data = (array) $order_request;

                $data['order_id'] = '#' . rand(9999, 100000);
                $data['organization_id'] = Event::findOrFail($order_request->event_id)->user_id;
                $data['customer_id'] = Auth::user()->id;
                if ($order_request->payment_type == "LOCAL") {
                    $data['payment_status'] = 0;
                    $data['order_status'] = 'Pending';
                } else {
                    $data['payment_status'] = 1;
                    $data['order_status'] = 'Complete';
                }
                if ($order_request->payment_type == 'WALLET') {
                    $user = $order_request->user();
                    $user = AppUser::find($user->id);
                    if ($user->balance >= $order_request->payment) {
                        $user->withdraw($order_request->payment, ['event_id' => $order_request->ticket_id]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Insufficient balance']);
                    }
                }
                $com = Setting::findOrFail(1, ['org_commission_type', 'org_commission']);
                $p =   $order_request->payment - $order_request->tax;
                if ($order_request->payment_type == "FREE") {
                    $data['org_commission']  = 0;
                } else {
                    if ($com->org_commission_type == "percentage") {
                        $data['org_commission'] =  $p * $com->org_commission / 100;
                    } else if ($com->org_commission_type == "amount") {
                        $data['org_commission']  = $com->org_commission;
                    }
                }

                if (isset($order_request->coupon_id) && $order_request->coupon_id != null) {
                    $coupon = Coupon::find($order_request->coupon_id);
                    $count = $coupon->use_count + 1;
                    $coupon->update(['use_count' => $count]);
                    CouponUsageHistory::create([
                        'coupon_id'=> $order_request->coupon_id,
                        'appuser_id'=>  Auth::guard('userApi')->user()->id
                    ]);
                    $data['coupon_discount'] = $coupon->discount;
                    $data['coupon_id'] = $coupon->id;
                }
                if ($order_request->payment_type == "STRIPE") {
                    $currency_code = Setting::first()->currency;
                    $stripe_payment = $currency_code == "USD" || $currency_code == "EUR" || $currency_code == "INR" ? $order_request->payment * 100 : $order_request->payment;

                    $cur = Setting::find(1)->currency;
                    $stripe_secret =  PaymentSetting::find(1)->stripeSecretKey;

                    Stripe\Stripe::setApiKey($stripe_secret);
                    $stripeDetail =  Stripe\PaymentIntent::create([
                        "amount" => $stripe_payment,
                        "currency" => $cur,
                        // "source" => $order_request->payment_token,
                    ]);
                    $data['payment_token'] = $stripeDetail->id;
                }

                $data['book_seats'] = isset($order_request->book_seats) ? $order_request->book_seats : null;
                $data['seat_details'] = isset($order_request->seat_details) ? $order_request->seat_details : null;
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
                //save pos data 
                if(isset($order_request->phone))
                {
                    $pos = new PosOrder();
                    $pos->order_id  = $data->id; 
                    $pos->phone  = $order_request->phone; 
                    $pos->customername  = $order_request->customername; 
                    $pos->customeremail  = $order_request->customeremail; 
                    $pos->save();
                }   
                  
                //
                $ticket = Ticket::find($order_request->ticket_id);
                for ($i = 1; $i <= $order_request->quantity; $i++) {
                    $child['ticket_number'] = uniqid();
                    $child['ticket_id'] = $order_request->ticket_id;
                    $child['order_id'] = $data->id;
                    $child['customer_id'] = Auth::User()->id;
                    $child['checkin'] = $ticket->maximum_checkins ?? null;
                    $child['paid'] = $order_request->payment_type == 'LOCAL' ? 0 : 1 ;
                    OrderChild::create($child);
                }

                if (isset($order_request->tax_data)) {
                    foreach (json_decode($order_request->tax_data) as $value) {
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
                $detail['quantity'] = $order_request->quantity;
                $detail['event_name'] = Event::find($order_request->event_id)->name;
                $detail['date'] = Event::find($order_request->event_id)->start_time->format('d F Y h:i a');
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
                $details['quantity'] = $order_request->quantity;
                $details['event_name'] = Event::find($order_request->event_id)->name;
                $details['date'] = Event::find($order_request->event_id)->start_time->format('d F Y h:i a');
                $details['app_name'] = $setting->app_name;

                $order_id_array[] = $data->id;
                if (true) {

                        

                //if ($setting->mail_notification == 1) {
                    try {
                        // $setting = Setting::first();
                        // $config = array(
                        //     'driver'     => $setting->mail_mailer,
                        //     'host'       => $setting->mail_host,
                        //     'port'       => $setting->mail_port,
                        //     'encryption' => $setting->mail_encryption,
                        //     'username'   => $setting->mail_username,
                        //     'password'   => $setting->mail_password
                        // );
                        // Config::set('mail', $config);
                       

                        // Mail::to($user->email)->send(new TicketBook($ticket_book->mail_content, $details, $ticket_book->subject,$qrcode));
                        $qrcode = $data->order_id;
                        $order = Order::find($data->id);
                        $this->sendOrderMail($order->id);

                       /* $dataemail['order'] = Order::find($order->id);
                        $dataemail['event'] = Event::find($dataemail['order']->event_id);
                        $dataemail['user'] = AppUser::find($dataemail['order']->customer_id);
                        $dataemail['email'] = "faris.ali@wasltec.com" ;

                         Mail::send(['html'=>'emails.notifyticket'], $dataemail, function($message) use ($dataemail) {
                        $message->to($dataemail['email'])->subject
                            ('Ticket Booked');
                        $message->from('ticketbyksa@gmail.com','TicketBy');
                        });*/
                       /* $dataemail['email'] = "ghorm@wasltec.com" ;

                          Mail::send(['html'=>'emails.notifyticket'], $dataemail, function($message) use ($dataemail) {
                        $message->to($dataemail['email'])->subject
                            ('Ticket Booked');
                        $message->from('ticketbyksa@gmail.com','TicketBy');
                        });*/
                          
                    } catch (\Throwable $th) {
                        Log::info($th->getMessage());
                    }
                }

                // for Organizer notification
                $org =  User::find($data->organization_id);
                $or_message = NotificationTemplate::where('title', 'Organizer Book Ticket')->first()->message_content;
                $or_detail['organizer_name'] = $org->first_name . ' ' . $org->last_name;
                $or_detail['user_name'] = $user->name . ' ' . $user->last_name;
                $or_detail['quantity'] = $order_request->quantity;
                $or_detail['event_name'] = Event::find($order_request->event_id)->name;
                $or_detail['date'] = Event::find($order_request->event_id)->start_time->format('d F Y h:i a');
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
                $details1['quantity'] = $order_request->quantity;
                $details1['event_name'] = Event::find($order_request->event_id)->name;
                $details1['date'] = Event::find($order_request->event_id)->start_time->format('d F Y h:i a');
                $details1['app_name'] = $setting->app_name;
                if ($setting->mail_notification == 1) {
                    try {
                        // $setting = Setting::first();
                        // $config = array(
                        //     'driver'     => $setting->mail_mailer,
                        //     'host'       => $setting->mail_host,
                        //     'port'       => $setting->mail_port,
                        //     'encryption' => $setting->mail_encryption,
                        //     'username'   => $setting->mail_username,
                        //     'password'   => $setting->mail_password
                        // );
                        // Config::set('mail', $config);
              //        Mail::to($user->email)->send(new TicketBookOrg($new_ticket->mail_content, $details1, $new_ticket->subject));
                    } catch (\Throwable $th) {
                        Log::info($th->getMessage());
                    }
                }

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
      }
       
        

        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    }

    public function updateOrderestatus ( Request $request )
    {
        $order = Order::where('id',$request->id)->first();
        if(!$order)
        {
            return response()->json(['success' => false, 'msg' => "Invalid orderid " , 'data' => null], 200);
        }

        $order->payment_token = $request->payment_token ;
        $order->payment_status = $request->payment_status ;
        $order->order_status = $request->order_status == "Completed" ?  "Complete"  : $request->order_status;
        $order->save();


        $this->sendOrderMail($request->id);
        return response()->json(['success' => true, 'msg' => null , 'data' => $order], 200);
    }

     public function createOrderMultipleUnpaid ( Request $request )
    {   

        $data = $request->all();
        
        $event = Event::find($request->event_id);
        
        $org = User::find($event->user_id);

        $user = AppUser::find($request->user_id);
        if(isset($order_id))
        {
            $data['order_id'] =  $order_id;
        }
        else
        {
            $data['order_id'] =  '#' . rand(9999, 100000);    
        }

          
        if(!isset($request->ticket_id))
        {
            foreach ($request->tickets as $key_tickets => $value_tickets) {
                $ticekt = Ticket::find($value_tickets['ticket_id'] );  
                break; 
                        
            }      
        }
        else
        {
             $ticekt = Ticket::find($request->ticket_id);
        }
        $event = Event::find( $ticekt->event_id);
         // $order_id = Order::where([['order_status', 'Complete']])->where('order_status', "Complete")->where('payment_status', 1)->pluck('id')->toArray();
         //    $sold_ticket_count = OrderChild::whereIn('order_id', $order_id)->where('ticket_id', $ticekt->id)->count();
         //    if ($sold_ticket_count + $->quantity >= $ticekt->quantity   && $event->is_repeat == 0) {
         //        $ticekt->update(['status' => 0]);
         //        return response()->json(['success' => false, 'msg' => "ticekts soled out", 'data' => null], 200);
         //    }
        $data['ticket_id'] = $request->ticket_id;    
        $data['event_id'] = $event->id;
        $data['customer_id'] = $user->id;
        $data['organization_id'] = $org->id;
        $data['order_status'] = 'Pending';
        $data['web'] = 0;
        if(isset($request->slot_event_date) && !is_null($request->slot_event_date))
        {

            $data['time_slot_id'] = $request->time_slot_id;
            $data['event_book_date'] = $request->slot_event_date;    
        }

        $data['payment_status'] = 0;
        $data['order_status'] = 'Pending';
        $com = Setting::find(1, ['org_commission_type', 'org_commission']);
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

        if ($request->coupon_code != null) {
            $coupon = Coupon::find($request->coupon_id);
            if(!$coupon)
            {
                return response()->json(['success' => false, 'msg' => "Invalid coupon code " , 'data' => null], 200);
            }
            $count = $coupon->use_count + 1;
            $coupon->update(['use_count' => $count]);
            CouponUsageHistory::create([
                'coupon_id' => $request->coupon_id,
                'appuser_id' => $user->id
            ]);
            
            $data['coupon_discount'] =  (( $data['payment'] - $data['tax'])  / ( 1 - ( $coupon->discount / 100  )  )) - (1 - ( $coupon->discount / 100  ) );
            $data['coupon_id'] = $coupon->id;
        }

        $data['book_seats'] = isset($request->selectedSeatsId) ? $request->selectedSeatsId : null;
        $data['seat_details'] = isset($request->selectedSeats) ? $request->selectedSeats : null;

         if(!isset($request->ticket_id))
        {
            foreach ($request->tickets as $key_tickets => $value_tickets) {
                $data['ticket_id'] = $value_tickets['ticket_id'] ;  
                break; 
                        
            }
        }
       

        $order = Order::create($data);

        if(isset($request->phone))
        {
            $pos = new PosOrder();
            $pos->order_id  = $order->id; 
            $pos->phone  = $request->phone; 
            $pos->customername  = $request->customername; 
            $pos->customeremail  = $request->customeremail; 
            $pos->save();
        }   
        $module = Module::where('module', 'Seatmap')->first();
        if ($module->is_enable == 1 && $module->is_install == 1) {
            $seats = explode(',', $data['selectedSeatsId']);
            foreach ($seats as $key => $value) {
                $seat = Seats::find($value);
                if ($seat) {
                    $seat->update(['type' => 'occupied']);
                }
            }
        }


        // tickets array define
        
        $ticketIds = $quantities = array();  
        foreach ($request->tickets as $key_tickets => $value_tickets) {
           
                $ticketIds[] = $value_tickets['ticket_id'] ; 
                $quantities[] = $value_tickets['quantity'] ; 
                        
        }
         $tickets = array_map(function ($ticketId, $quantity) {
                return [
                    'ticket_id' => (int) $ticketId,  // Casting to int if necessary
                    'quantity' => (int) $quantity,  // Casting to int if necessary
                ];
            }, $ticketIds, $quantities);
        
         $count_quantity = 0 ;
            foreach ($tickets as $key => $ticket_array) {
                
                $ticekt = Ticket::find($ticket_array['ticket_id']);
                for ($i = 1; $i <= $ticket_array['quantity']; $i++) {
                    $count_quantity = $count_quantity +1 ; 
                    $child['ticket_number'] = uniqid();
                    $child['ticket_id'] = $ticket_array['ticket_id'];
                    $child['order_id'] = $order->id;
                    $child['customer_id'] = $request->user_id;
                    $child['checkin'] = $ticket->maximum_checkins ?? null;
                    $child['paid'] = $request->payment_type == 'LOCAL' ? 0 : 1;
                    
                    
                    OrderChild::create($child);
                 
                }
                $sold_ticket_count =  Order::where([['order_status', 'Complete']])->where('order_status',"Complete")->where('payment_status',1)->where('ticket_id',$ticekt->id)->count();
                if($sold_ticket_count >= $ticekt->quantity && $event->is_repeat == 0)
                {
                      $ticekt->update(['status'=>0 ]);
                }

            }



            $order_update_quantiy = Order::where('id',$order->id)->update(['quantity'=>$count_quantity]);
            if (isset($request->tax_data)) {
            foreach (json_decode($data['tax_data']) as $value) {
                $tax['order_id'] = $order->id;
                $tax['tax_id'] = $value->id;
                $tax['price'] = $value->price;
                OrderTax::create($tax);
            }
        }
        

        return response()->json(['success' => true, 'msg' => null, 'data' => $order], 200);
    }

    public function createOrderUnpaid1 ()
    {
        dd("kk");
    }


    public function testSendOrderMail () 
    {
        $id = '1283';
        $this->sendOrderMail($id);
        dd("jj");
    }

    public function sendOrderMail ( $id)
    {
         //$order_update = Order::where('id',$id)->update(['payment_status'=>1 , 'order_status' =>'Complete']);
        $order = Order::find($id);

        //posorder 
        $pos_order = PosOrder::where('order_id',$id)->first();
        if($pos_order)
        {
            
            $event = Event::where('id',$order->event_id)->first();
            $org_user = User::where('id',$event->user_id)->first();

            //email
             $order = Order::with(['customer', 'event', 'organization', 'ticket'])->find($id);
            $order->tax_data = OrderTax::where('order_id', $order->id)->get();
            $order->ticket_data = $oder_child =  OrderChild::where('order_id', $order->id)->get();
            foreach ($oder_child as $key => $value) {
                QrCode::format('png')
                    ->size(300)
                    ->generate($value->ticket_number, public_path('qrcodes/qr-' . $value->id . '.png'));
            }
            $customPaper = array(0, 0, 720, 1440);
            $pdf = FacadePdf::loadView('ticketmail', compact('order','pos_order'))->save(public_path("ticket.pdf"))->setPaper($customPaper, $orientation = 'portrait');
            $data["email"] = $pos_order->customeremail;
            $data["title"] = "Ticket PDF";
            $data["body"] = "";

            $dataemail['order'] = $order;
            $dataemail['event'] = $event;
            $dataemail['user'] = $pos_order ;
            $dataemail['email'] = $pos_order->customeremail ;
            $tempp = $pdf->output();

            Mail::send(['html' => 'emails.ticketpdf'], $dataemail, function ($message) use ($tempp ,$dataemail) {
                    $message->to($dataemail['email'])->subject('Ticket Booked');
                    $message->from('ticketbyksa@gmail.com', 'TicketBy')->attachData($tempp, "ticket.pdf");
                });

            //sms

            $to = substr($pos_order->phone, -9);  // str_replace('+', '', $org_user->phone);
            $message = "Thank you for booking a ticket for  ".$event->name." . Check your ticket here https://ticketby.co/order-invoice-print/".$order->id;

         
            // Prepare the data array
            $data = [
                'recipients' => [$to],
                'body' => $message,
                'sender' => 'TICKETBY'
            ];

            // Convert the data array to JSON
            $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);  // Ensure Arabic characters are kept intact

            if (true) { 
                try{
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
                    CURLOPT_POSTFIELDS => $json_data,  // Send the properly encoded JSON data
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json; charset=UTF-8',  // Ensure UTF-8 content type
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
            
            }
            return ; 
        }
          //pos order end


        
         $dataemail['order'] = $order;
        $dataemail['event'] = Event::find($dataemail['order']->event_id);
        $dataemail['user'] = AppUser::find($dataemail['order']->customer_id);
        $dataemail['email'] = $dataemail['user']->email ;
        //$dataemail['qrcode'] = $order->order_id ;
        $child_qr =  OrderChild::where('order_id', $order->id)->get();
        foreach ($child_qr as $key => $value) {
           $dataemail['qrcode'] = QrCode::format('png')
            ->size(300)
            ->generate($value->ticket_number, public_path('qrcodes/qr-'.$value->id.'.png'));
            $dataemail['qr_id'] = $value->id ; 
             Mail::send(['html'=>'emails.ticketbooked'], $dataemail, function($message) use ($dataemail) {
            $message->to($dataemail['email'])->subject
                ('Ticket Booked');
            $message->from('ticketbyksa@gmail.com','TicketBy');
            });



            // $dataemail['order'] = Order::find($order->id);
            // $dataemail['event'] = Event::find($dataemail['order']->event_id);
            // $dataemail['user'] = AppUser::find($dataemail['order']->customer_id);
            // $dataemail['email'] = "faris.ali@wasltec.com" ;


            //  Mail::send(['html'=>'emails.notifyticket'], $dataemail, function($message) use ($dataemail) {
            // $message->to($dataemail['email'])->subject
            //     ('Ticket Booked');
            // $message->from('ticketbyksa@gmail.com','TicketBy');
            // });
            // $dataemail['email'] = "ghorm@wasltec.com" ;

            //   Mail::send(['html'=>'emails.notifyticket'], $dataemail, function($message) use ($dataemail) {
            // $message->to($dataemail['email'])->subject
            //     ('Ticket Booked');
            // $message->from('ticketbyksa@gmail.com','TicketBy');
            // });
        }

        
       
    } 
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
        
        if(!isset($request->event_id) || !isset($request->ticket_id) ||  !isset($request->quantity) ||  !isset($request->coupon_discount) ||  !isset($request->payment) ||  !isset($request->tax)
        ||  !isset($request->payment_type))
        {
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
            if(!$coupon)
            {
                return response()->json(['success' => false, 'msg' => "invalid coupon", 'data' => null], 200);
            }
            $count = $coupon->use_count + 1;
            $coupon->update(['use_count' => $count]);
            CouponUsageHistory::create([
                'coupon_id'=> $request->coupon_id,
                'appuser_id'=>  Auth::guard('userApi')->user()->id
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
            $child['paid'] = $request->payment_type == 'LOCAL' ? 0 : 1 ;
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
        if (true) {
        //if ($setting->mail_notification == 1) {
            try {
                // $setting = Setting::first();
                // $config = array(
                //     'driver'     => $setting->mail_mailer,
                //     'host'       => $setting->mail_host,
                //     'port'       => $setting->mail_port,
                //     'encryption' => $setting->mail_encryption,
                //     'username'   => $setting->mail_username,
                //     'password'   => $setting->mail_password
                // );
                // Config::set('mail', $config);
                $qrcode = $data->order_id;

                $order = Order::find($id);
                 $dataemail['order'] = $order;
                $dataemail['event'] = Event::find($dataemail['order']->event_id);
                $dataemail['user'] = AppUser::find($dataemail['order']->customer_id);
                $dataemail['email'] = $dataemail['user']->email ;
                $child_qr =  OrderChild::where('order_id', $order->id)->first();
                $dataemail['qrcode'] = QrCode::format('png')
                ->size(300)
                ->generate($child_qr->ticket_number, public_path('qrcodes/qr-'.$order->id.'.png'));

                 Mail::send(['html'=>'emails.ticketbooked'], $dataemail, function($message) use ($dataemail) {
                $message->to("hitarth.rc@gmail.com")->subject
                    ('Ticket Booked');
                $message->from('ticketbyksa@gmail.com','TicketBy');
                });

                // Mail::to($user->email)->send(new TicketBook($ticket_book->mail_content, $details, $ticket_book->subject,$qrcode));

                $dataemail['order'] = Order::find($order->id);
                $dataemail['event'] = Event::find($dataemail['order']->event_id);
                $dataemail['user'] = AppUser::find($dataemail['order']->customer_id);
                $dataemail['email'] = "faris.ali@wasltec.com" ;

                 Mail::send(['html'=>'emails.notifyticket'], $dataemail, function($message) use ($dataemail) {
                $message->to($dataemail['email'])->subject
                    ('Ticket Booked');
                $message->from('ticketbyksa@gmail.com','TicketBy');
                });
                $dataemail['email'] = "ghorm@wasltec.com" ;

                  Mail::send(['html'=>'emails.notifyticket'], $dataemail, function($message) use ($dataemail) {
                $message->to($dataemail['email'])->subject
                    ('Ticket Booked');
                $message->from('ticketbyksa@gmail.com','TicketBy');
                });
                  
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
                Mail::to($user->email)->send(new TicketBookOrg($new_ticket->mail_content, $details1, $new_ticket->subject));
            } catch (\Throwable $th) {
                Log::info($th->getMessage());
            }
        }

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

        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    }

    public function viewUserOrder()
    {
       /* (new AppHelper)->eventStatusChange();*/
        $data = Order::with(['event', 'ticket', 'organization'])->where('customer_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    }

    public function viewSingleOrder($id)
    {
        (new AppHelper)->eventStatusChange();
        $data = Order::with(['event', 'ticket', 'organization'])->find($id);
        $data['order_child'] = OrderChild::where('order_id', $data->id)->get();
        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    }

    public function allSetting()
    {
        $general = Setting::find(1, ['app_name', 'app_version', 'logo', 'currency', 'onesignal_app_id', 'onesignal_project_number', 'help_center', 'appuser_privacy_policy','privacy_policy_arabic' ,'terms_services_arabic','cookie_policy', 'terms_services', 'acknowledgement', 'currency_sybmol']);
        if (auth('userApi')->check()) {
            $paymentSettings = PaymentSetting::find(1);
            $general->stripe = $paymentSettings->stripe;
            $general->cod = $paymentSettings->cod;
            $general->paypal = $paymentSettings->paypal;
            $general->razor = $paymentSettings->razor;
            $general->flutterwave = $paymentSettings->flutterwave;
            $general->stripeSecretKey = $paymentSettings->stripeSecretKey;
            $general->stripePublicKey = $paymentSettings->stripePublicKey;
            $general->paypalClientId = $paymentSettings->paypalClientId;
            $general->paypalSecret = $paymentSettings->paypalSecret;
            $general->razorPublishKey = $paymentSettings->razorPublishKey;
            $general->razorSecretKey = $paymentSettings->razorSecretKey;
            $general->ravePublicKey = $paymentSettings->ravePublicKey;
            $general->raveSecretKey = $paymentSettings->raveSecretKey;
            $general->flutterDebugMode = $paymentSettings->flutterDebugMode;
            $general->wallet = $paymentSettings->wallet;
            $general->edfpay = $paymentSettings->edfpay;
            $general->edfapay_passkey = $paymentSettings->edfapay_passkey;
            $general->edfapay_key = $paymentSettings->edfapay_key;
        }
        return response()->json(['success' => true, 'msg' => null, 'data' => $general], 200);
    }

    public function userOrder()
    {

        $data['upcoming'] = Order::with(['event', 'ticket'])->where([['customer_id', Auth::user()->id], ['order_status', 'Complete']])->orderBy('id', 'DESC')->get();
        $data['past'] = Order::with(['event', 'ticket'])
            ->where([['customer_id', Auth::user()->id], ['order_status', 'Complete']])
            ->orWhere([['customer_id', Auth::user()->id], ['order_status', 'Cancel']])
            ->orderBy('id', 'DESC')->get();

        foreach ($data['upcoming'] as $upcoming) {
            $upcoming['order_child'] = OrderChild::where('order_id', $upcoming->id)->get();
        }

        foreach ($data['past'] as $past) {
            $past['order_child'] = OrderChild::where('order_id', $past->id)->get();
        }
        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    }

    public function singleOrder($id)
    {
        (new AppHelper)->eventStatusChange();
        $data = Order::with(['event', 'ticket'])->find($id);
        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    }

    public function searchFilter ( Request $request )
    {
        

        $firstQuery = DB::table('events')->where([['status', 1], ['is_deleted', 0]])
            ->select('id', 'name', 'address',DB::raw("'event' as type") )->where('name', 'like', '%' . $request->search . '%')
    ->orWhere('address', 'like', '%' . $request->search  . '%'); // Adjust the selected columns as needed

        $secondQuery = DB::table('users')
            ->select('id', DB::raw('CONCAT(last_name, " ", first_name) AS name'),DB::raw('NULL as address'),DB::raw("'user' as type"))->where('first_name', 'like', '%' . $request->search . '%')->orWhere('last_name', 'like', '%' . $request->search  . '%'); // Ensure the columns match in order and type

        $combinedResults = $firstQuery
            ->union($secondQuery)
            ->get();
            $data = array();
            foreach ($combinedResults as $key => $combinedResult) {
                if($combinedResult->type == "user")
                {
                    $combinedResult->details = User::find($combinedResult->id);
                    $combinedResult->details->is_following = 0;
                    

                        if(in_array($combinedResult->id,array_filter(explode(',',Auth::guard('userApi')->user()->following))))
                        {
                            $combinedResult->details->is_following = 1;
                        }

                   
                    
                }
                else
                {
                    $combinedResult->details = Event::find($combinedResult->id);
                    $combinedResult->details->is_like = 0;
                    

                        if(in_array($combinedResult->id,array_filter(explode(',',Auth::guard('userApi')->user()->favorite))))
                        {
                            $combinedResult->details->is_like = 1;
                        }


                }
            }
        return response()->json($combinedResults);

    }

    public function searchEvent(Request $request)
    {

        // if search name 
        if(isset($request->search))
        {
            $firstQuery = DB::table('events')->where([['status', 1], ['is_deleted', 0]])
            ->select('id', 'name', 'address',DB::raw("'event' as type") )->where('name', 'like', '%' . $request->search . '%')
        ->orWhere('address', 'like', '%' . $request->search  . '%')->where('is_deleted',0); // Adjust the selected columns as needed

            $secondQuery = DB::table('users')
                ->select('id', DB::raw('CONCAT(last_name, " ", first_name) AS name'),DB::raw('NULL as address'),DB::raw("'user' as type"))->where('first_name', 'like', '%' . $request->search . '%')->orWhere('last_name', 'like', '%' . $request->search  . '%'); // Ensure the columns match in order and type

            $combinedResults = $firstQuery
                ->union($secondQuery)
                ->get();
                $data = array();
                foreach ($combinedResults as $key => $combinedResult) {
                    if($combinedResult->type == "user")
                    {
                        $combinedResult->details = User::find($combinedResult->id);
                    }
                    else
                    {
                        $combinedResult->details = Event::find($combinedResult->id);
                    }
                }
            return response()->json($combinedResults);
            }
        

        // if search name ends 
        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);
        $data = Event::where([['status', 1], ['is_deleted', 0], ['start_time', '>=', $date->format('Y-m-d')]]);
        if ($request->lat != null && $request->lang != null) {
            $lat = $request->lat;
            $lang = $request->lang;
            $event = array();
            $radius = 250;
            $results = DB::select(DB::raw('SELECT id,name, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(' . $lang . ') ) + sin( radians(' . $lat . ') ) * sin( radians(lat) ) ) ) AS distance FROM events HAVING distance < ' . $radius . '  ORDER BY distance'));
            if (count($results) > 0) {
                foreach ($results as $q) {
                    array_push($event, $q->id);
                }
            }
            $data = $data->whereIn('id', $event);
        }
        // if ($request->category != "All") {
        //     $data = $data->where([['category_id', $request->category], ['start_time', '>=', $date->format('Y-m-d H:i:s')]]);
        // }

        if (isset($request->date) && $request->date != "All") {

            if ($request->date == "Today") {
                $start_date = Carbon::now()->format('Y-m-d') . ' 00:00:00';
                $end_date = Carbon::now()->format('Y-m-d') . ' 23:59:59';
            } elseif ($request->date == "Tommorow") {
                $start_date = Carbon::now()->addDays(1)->format('Y-m-d') . ' 00:00:00';
                $end_date = Carbon::now()->addDays(1)->format('Y-m-d') . ' 23:59:59';
            } elseif ($request->date == "This Week") {
                $start_date = Carbon::now()->modify('this week')->format('Y-m-d') . ' 00:00:00';
                $end_date = Carbon::now()->modify('this week +6 days')->format('Y-m-d') . ' 23:59:59';
            } elseif ($request->date == "This Weekend") {
                $start_date = Carbon::now()->endOfWeek()->subDays(2)->format('Y-m-d') . ' 00:00:00';
                $end_date = Carbon::now()->endOfWeek()->format('Y-m-d') . ' 23:59:59';
            } else {
                $start_date = carbon::parse($request->date)->format('Y-m-d') . ' 00:00:00';
                $end_date = carbon::parse($request->date)->format('Y-m-d') . ' 23:59:59';
            }
            $data = $data->durationData($start_date, $end_date);
        }
        if(isset($request->sort))
        {
            $data = $data->orderBy('lowest_price',$request->sort);
        }
        $data = $data->get();
        foreach ($data as $value) {
            $value->description =  str_replace("&nbsp;", " ", strip_tags($value->description));
            $value->time = $value->start_time->format('d F Y h:i a');
            if (Auth::guard('userApi')->check()) {
                if (in_array($value->id, array_filter(explode(',', Auth::guard('userApi')->user()->favorite)))) {
                    $value->isLike = true;
                } else {
                    $value->isLike = false;
                }
            } else {
                $value->isLike = false;
            }
        }
        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    }

    public function searchEventWeb(Request $request)
    {
       
        // if search name 
         if(isset($request->search))
        {
            $firstQuery = Event::where([['status', 1], ['is_deleted', 0]])->where('name', 'like', '%' . $request->search . '%')->whereDate('end_time', '>',Carbon::now())->where('is_deleted',0); 
            // Adjust the selected columns as needed

            $combinedResults = $firstQuery
               
                ->get();
                $data = array();
                foreach ($combinedResults as $key => $combinedResult) {
                   
                       // $combinedResult->details = Event::find($combinedResult->id);
                        $combinedResult->slug = Str::slug($combinedResult->name);
                    
                }
                return response()->json(['success' => true, 'msg' => null, 'data' => $combinedResults], 200);
            //return response()->json($combinedResults);
            }
        

        // if search name ends 
        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);
        $data = Event::where([['status', 1], ['is_deleted', 0], ['end_time', '>', $date->format('Y-m-d H:i:s')]]);

        if ($request->lat != null && $request->lang != null) {
           
            $lat = $request->lat;
            $lang = $request->lang;
            $event = array();
            $radius = 250;
            $results = DB::select(DB::raw('SELECT id,name, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(' . $lang . ') ) + sin( radians(' . $lat . ') ) * sin( radians(lat) ) ) ) AS distance FROM events HAVING distance < ' . $radius . '  ORDER BY distance'));
            if (count($results) > 0) {
                foreach ($results as $q) {
                    array_push($event, $q->id);
                }
            }
            $data = $data->whereIn('id', $event);
        }
        if (isset($request->category_id)) {
            
            $data = $data->where([['category_id', $request->category_id]]);
        }
        if (isset($request->city_id)) {
            
            $data = $data->where([['city_id', $request->city_id]]);
        }
        if (isset($request->date) && $request->date != "All") {
          

            if ($request->date == "Today") {
                $start_date = Carbon::now()->format('Y-m-d') . ' 00:00:00';
                $end_date = Carbon::now()->format('Y-m-d') . ' 23:59:59';
            } elseif ($request->date == "Tommorow") {
                $start_date = Carbon::now()->addDays(1)->format('Y-m-d') . ' 00:00:00';
                $end_date = Carbon::now()->addDays(1)->format('Y-m-d') . ' 23:59:59';
            } elseif ($request->date == "This Week") {
                $start_date = Carbon::now()->modify('this week')->format('Y-m-d') . ' 00:00:00';
                $end_date = Carbon::now()->modify('this week +6 days')->format('Y-m-d') . ' 23:59:59';
            } elseif ($request->date == "This Weekend") {
                $start_date = Carbon::now()->endOfWeek()->subDays(2)->format('Y-m-d') . ' 00:00:00';
                $end_date = Carbon::now()->endOfWeek()->format('Y-m-d') . ' 23:59:59';
            } else {
                $start_date = carbon::parse($request->date)->format('Y-m-d') . ' 00:00:00';
                $end_date = carbon::parse($request->date)->format('Y-m-d') . ' 23:59:59';
            }
            $data = $data->durationData($start_date, $end_date);
        }
       
        if(isset($request->limit))
        {
            $data = $data->limit($request->limit);
        }
        if(isset($request->sort))
        {
            $data = $data->orderBy('lowest_price',$request->sort);
        }
        $data = $data->orderBy('orderby',"desc")->get();
        foreach ($data as $value) {
            $value->description =  str_replace("&nbsp;", " ", strip_tags($value->description));
            $value->time = $value->start_time->format('d F Y h:i a');
             $value->slug = Str::slug($value->name);
            if (Auth::guard('userApi')->check()) {
                if (in_array($value->id, array_filter(explode(',', Auth::guard('userApi')->user()->favorite)))) {
                    $value->isLike = true;
                } else {
                    $value->isLike = false;
                }
            } else {
                $value->isLike = false;
            }
        }
        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    }

    public function perviousEvent ( Request $request)
    {
        $data = Event::where([['status', 1], ['is_deleted', 0], ['start_time', '<=', Carbon::now()->format('Y-m-d')]]);

         if(isset($request->limit))
        {
            $data = $data->limit($request->limit);
        }

        $data = $data->get();

        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    } 

    public function getTimeSlots ( Request $request ) 
    {
        if(!isset($request->ticket_id))
        {
            return response()->json("ticket id missing");
        }
        if(!isset($request->date))
        {
            return response()->json("date missing");
        }

        $ticket_id = $request->ticket_id;
         $ticket_array = array();
        if(is_array($request->date))
        {
           
            $time_slots = EventTime::where('ticket_id',$request->ticket_id)->get();
            $ticket = Ticket::where('id',$request->ticket_id)->first();
            foreach ($request->date as $key => $date) {
                $tickets = Ticket::where('event_id',$request->ticket_id)->get();
                
                foreach ($time_slots as $key1 => $value_slot) {
                    $slot_obj = new stdClass; 
                    $slot_obj->available_quanity =  $ticket->quantity - Order::where('ticket_id',$ticket->id)->where('time_slot_id',$value_slot->id)->where('event_book_date',$date)->sum('quantity');  
                    $slot_obj->time_slot_id =  $value_slot->id;
                    $slot_obj->start_time = Carbon::parse($value_slot->start_time)->format("h:i A");
                    $slot_obj->end_time = Carbon::parse($value_slot->end_time)->format("h:i A");
                    $slot_obj->date = $date;
                    $slot_obj->price = $ticket->price;
                    $slot_obj->ticket_id = $ticket->id;
                    $ticket_array[] = $slot_obj;
                }
            }
        }
        return response()->json(['success' => true, 'msg' => null, 'data' => $ticket_array], 200);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'bail|required',
            'password' => 'bail|required|min:6',
            'password_confirmation' => 'bail|required|same:password|min:6'
        ]);
        if (Hash::check($request->old_password, Auth::user()->password)) {
            AppUser::find(Auth::user()->id)->update(['password' => Hash::make($request->password)]);
            return response()->json(['success' => true, 'msg' => 'Your password is change successfully', 'data' => null], 200);
        } else {
            return response()->json(['success' => false, 'msg' => 'Current Password is wrong!', 'data' => null], 200);
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
                $config = array(
                    'driver'     => $setting->mail_mailer,
                    'host'       => $setting->mail_host,
                    'port'       => $setting->mail_port,
                    'encryption' => $setting->mail_encryption,
                    'username'   => $setting->mail_username,
                    'password'   => $setting->mail_password
                );
                Config::set('mail', $config);
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

    public function clearNotification()
    {
        $noti = Notification::where('user_id', Auth::user()->id)->get();
        foreach ($noti as $value) {
            $value->delete();
        }
        return response()->json(['success' => true, 'msg' => 'Notification deleted successfully.'], 200);
    }
    public function user_delete($id)
    {
        $time = Carbon::now();
        $time->toArray();
        $app_user = AppUser::find($id);
        $app_user['name'] = 'User Deleted';
        $app_user['last_name'] = 'Deleted';
        $app_user['status'] = 0;
        $app_user['email'] =  $time->timestamp . '@deleteduser.com';
        $app_user->update();
        $app_user->delete();
        return response()->json(['success' => true, 'msg' => 'Account deleted successfully.'], 200);
    }
    public function otpVerify(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'otp' => 'required',
        ]);
        $user = AppUser::find($request->id);
        if ($user->is_verify == 1) {
            return response()->json(['msg' => 'User is already verified', 'success' => false], 400);
        }
        if ($user->otp == $request->otp) {
            $user->otp = null;
            $user->is_verify = 1;
            $user->device_token = $request->device_token ?? null;
            $user->update();
            Auth::guard('appuser')->login($user);
            $user = Auth::guard('appuser')->user();
            $user['token'] = $user->createToken('eventRight')->accessToken;
            return response()->json(['msg' => 'OTP verify successfully', 'data' => $user, 'success' => true], 200);
        } else {
            return response()->json(['msg' => 'Wrong OTP. Please try again.', 'success' => false]);
        }
    }
    // Wallet
    public function getBalance(Request $request)
    {
        $user = $request->user();
        $user = AppUser::find($user->id);
        $data['balance'] = $user->balance;
        $data['transactions'] = $user->transactions()->orderBy('created_at', 'desc')->get()->makeHidden(['payable_type', 'confirmed', 'uuid', 'created_at', 'updated_at']);
        return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
    }
    public function deposit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'payment_type' => 'required',
            'currency' => 'required',
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'msg' => $validator->errors()->first(), 'data' => null], 400);
        }
        $user = $request->user();
        $user = AppUser::find($user->id);
        $user->deposit($request->amount, ['payment_mode' => $request->payment_type, 'currency' => $request->currency, 'token' => $request->token]);
        $user->wallet->refreshBalance();
        $balance = $user->balance;
        $data = $user->transactions()
            ->orderBy('created_at', 'desc')
            ->first()
            ->makeHidden(['payable_type', 'confirmed', 'uuid', 'created_at', 'updated_at']);
        return response()->json(['success' => true, 'data' => $data,'balance'=>$balance]);
    }

    public function userDelete ( Request $request , $id )
    {
        $check_user = AppUser::where('id',$id)->where('status',1)->first() ; 
        if($check_user)
        {
            $update_user = AppUser::where('id',$id)->where('status',1)->update(['status'=>0]) ; 
            return response()->json(['success' => true, 'msg' => 'User Deleted successfully'], 200);
        }
        else{
            return response()->json(['success' => false, 'msg' => 'User Not Found '], 500);
        }
    }

    public function updateLocation (  Request $request  )
    {
        if(!isset($request->id) || !isset($request->type) || !isset($request->lat) || !isset($request->lang) )
        {
            $data['status'] = 500;
            $data['error'] = "Mssing requried fields";
            return response()->json($data);
        }

        if($request->type =="user")
        {
            $user = AppUser::where('id',$request->id)->update(['lat'=>$request->lat, 'lang'=>$request->lang]);
        }
        else
        {
            $user = User::where('id',$request->id)->update(['lat'=>$request->lat, 'lang'=>$request->lang]);
        }

        $data['status'] = 200;
        $data['message'] = "Location Updated";
        return response()->json($data);

    }

    public function bannerDetails ( Request $request )
    {
        $banner = Banner::where('status',1)->get();
        $banner_array = array();
        foreach($banner  as  $banner_data)
        {
            $banner_data->image_url = "https://ticketby.co/images/upload/".$banner_data->image;
            $banner_data->event = Event::where('id',$banner_data->event_id)->first();

            $banner_array[] = $banner_data ; 
        }
        $data['status'] = 200 ; 
        $data['data'] = $banner_array ;
        return response()->json($data);
    }

    public function organizerProfile    ( Request $request )
    {
        if($request->login_user_id)
        {
            $login_user = AppUser::find($request->login_user_id);    
        }
        
        $data["profile"] = User::find($request->organizer_id);
        $event_count = 0 ;
        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);
        $data['previous'] =  Event::with(['ticket'])
        ->where([['status', 1], ['is_deleted', 0], ['user_id',$request->organizer_id], ['start_time', '<=', $date->format('Y-m-d H:i:s')], ['end_time', '<=', $date->format('Y-m-d H:i:s')]])
        ->orWhere([['status', 1], ['event_status', 'Cancel'], ['is_deleted', 0], ['user_id',$request->organizer_id], ['start_time', '<=', $date->format('Y-m-d H:i:s')],['end_time', '<=', $date->format('Y-m-d H:i:s')]])
        ->orderBy('start_time', 'ASC')->get();
        foreach ($data['previous'] as $value) {
            $value->description =  str_replace("&nbsp;", " ", strip_tags($value->description));
            $event_count = $event_count + 1 ; 
            //dd(Auth::user());
            //if(array_filter(explode(',', Auth::user()->favorite)))   
            $value->is_like = 0;
            if($request->login_user_id)
            {
                if(in_array($value->id,array_filter(explode(',', $login_user->favorite))))
                {
                    $value->is_like = 1;
                }

            }
        }
       

        $data['upcoming'] =  Event::with(['ticket'])
            ->where([['status', 1], ['is_deleted', 0], ['user_id',$request->organizer_id], ['start_time', '>=', $date->format('Y-m-d H:i:s')]])
            ->where([['status', 1], ['event_status', 'Pending'], ['user_id',$request->organizer_id], ['start_time', '>=', $date->format('Y-m-d H:i:s')]])
            ->orderBy('start_time', 'ASC')->get();
        foreach ($data['upcoming'] as $value) {
            $value->description =  str_replace("&nbsp;", " ", strip_tags($value->description));
            $event_count = $event_count + 1 ; 
            $value->is_like = 0;
            if($request->login_user_id)
            {
                if(in_array($value->id,array_filter(explode(',', $login_user->favorite))))
                {
                    $value->is_like = 1;
                }

            }
        }

        $data['event_count'] = $event_count;  
        return response()->json($data);
    }

    public function socailLogin ( Request $request )
    {
       /* if(!isset($request->email) && !isset($request->apple_login_id))
        {
            $data['status'] = 401 ;
            $data['error'] = "Missing email";
            return response()->json($data);
        }*/
        if(isset($request->email))
        {
            $check  = AppUser::where('email',$request->email)->where('status',1)->first();    
        }
        else
        {
            $check  = AppUser::where('apple_login_id',$request->apple_login_id)->where('status',1)->first();    
        }
        

        if($check)
        {
            if(isset($request->apple_login_id))
            {
                $check->apple_login_id = $request->apple_login_id ; 
            }
            if(isset($request->snapchat_id))
            {
                $check->snapchat_id = $request->snapchat_id; 
            }
            if(isset($request->google_id))
            {
                $check->google_id = $request->google_id; 
            }
             $check->save();
             $user  = AppUser::where('email',$request->email)->where('status',1)->first();
            Auth::guard('appuser')->login($user);
            $user = Auth::guard('appuser')->user();
            
            $user['token'] = $user->createToken('eventRight')->accessToken;
            return response()->json(['msg' => 'Logged in', 'data' => $user, 'success' => true], 200);
        }
        else
        {
            $user = new AppUser();
            $user->name = isset($request->name) ?  $request->name : ""; 
            $user->email = isset($request->email) ? $request->email : "" ;
            $user->phone = isset($request->phone) ? $request->phone : null ;
            if(isset($request->apple_login_id))
            {
                $user->apple_login_id = $request->apple_login_id ; 
            }
            if(isset($request->snapchat_id))
            {
                $user->snapchat_id = $request->snapchat_id; 
            }
            if(isset($request->google_id))
            {
                $user->google_id = $request->google_id; 
            }
            $user->is_verify = 1;
            $user->status = 1;
            $user->save(); 
            Auth::guard('appuser')->login($user);
            $user = Auth::guard('appuser')->user();
            $user['token'] = $user->createToken('eventRight')->accessToken;
            return response()->json(['msg' => 'Logged in', 'data' => $user, 'success' => true], 200);
        }

    }

    public function cityList ( Request $request )
    {
        $city = City::all();
        $data['city'] = $city;
        $data['status'] = 200; 
         return response()->json($data); 
    }

    public function eventTime ( Request $request , $id )
    {
        $event_time  = EventTime::where('event_id',$id)->get();
        $data['status']  = 200 ; 
        $data['message']  = null ;
        $data['data'] = $event_time ;
        return $data  ;
    }
    public function webUserLogin ( Request $request )
    {
        $username = $request->user_name ; 
       
        $user = AppUser::where('email',$request->user_name)->where('status',1)->first();
        if(!is_null($user))
        {
            $user = AppUser::where('phone',$request->user_name)->where('status',1)->first();
        }

        if($user)
        {
            $otp = rand(100000, 999999);

            $to = str_replace('+', '', $user->phone);
            $message = "Your phone verification code is $otp for $setting->app_name.";
            $user = AppUser::find($user->id);
            $dataemail['name'] = $user->name;
            $dataemail['email'] = $user->email;
            $dataemail['otp'] = $otp;

            $data = array('name' => "TicketBy", 'email' => 'hivasavada@gmail.com', "otp" => $otp);
            Mail::send(['html' => 'frontend.email.otp'], $data, function ($message) use ($data) {
                $message->to($dataemail['email'])->subject('OTP Verification');
                $message->from('ticketbyksa@gmail.com', 'TicketBy');
            });
            if (true) {

                $user = AppUser::find($user->id);
                $dataemail['name'] = $user->name;
                $dataemail['email'] = $user->email;
                $dataemail['otp'] = $otp;

                $data = array('name' => "TicketBy", 'email' => 'hivasavada@gmail.com', "otp" => $otp);
                Mail::send(['html' => 'frontend.email.otp'], $data, function ($message) use ($data) {
                    $message->to($dataemail['email'])->subject('OTP Verification');
                    $message->from('ticketbyksa@gmail.com', 'TicketBy');
                });
                $user->otp = $otp;
                $user->update();
                try {
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
                       ' . $to . '
                    ],
                    "body":' . $message . ',
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

                
            }    
        }

        return response()->json(['msg' => 'Otp sent', 'data' => $user, 'success' => true], 200);
    }


   public function loginVerifyOtp ( Request $request ) 
   {

     $request->validate([
            'id' => 'required',
            'otp' => 'required',
        ]);
        $user = AppUser::where('id',$request->id)->first();
       
        if ($user->otp == $request->otp) {
            //$user->otp = null;
            $user->device_token = $request->device_token ?? null;
            $user->update();
            $user = AppUser::where('id',$request->id)->first();
            Auth::guard('appuser')->login($user);
            Session::put("user_id",$request->id);
            $user->token = $user->createToken('eventRight')->accessToken;
            $user->is_verify = 1 ;
            return response()->json(['msg' => 'OTP verify successfully', 'data' => $user, 'success' => true], 200);
        } else {
            return response()->json(['msg' => 'Wrong OTP. Please try again.', 'success' => false]);
        }
   }


   public function posEvent ( Request $request )
   {
    $events = Event::where('user_id',$request->organizer_id)->whereDate('start_time',">=",Carbon::now())->orderBy('start_time','ASC')->get();

    return response()->json(['msg' => 'Event listing', 'data' => $events, 'success' => true], 200);

   }
}
