<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Exception;
use App\Services\Tabby;
use App\Services\Tamara;
use App\Models\Event;
use App\Models\User;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\Coupon;
use App\Models\Tax;
use App\Models\OrderTax;
use App\Models\AppUser;
use App\Models\Category;
use App\Models\Blog;
use App\Models\Faq;
use Twilio\Rest\Client;
use GuzzleHttp\Client as ClientGuzzel;
use App\Models\Order;
use App\Models\Setting;
use App\Models\PaymentSetting;
use App\Models\NotificationTemplate;
use App\Models\EventReport;
use App\Models\OrderChild;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use OneSignal;
use Twilio\Rest\Client as Clients;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Rave;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Mail\ResetPassword;
use App\Mail\TicketBook;
use App\Mail\TicketBookOrg;
use App\Models\Language;
use App\Http\Controllers\FaqController;
use App\Models\Banner;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\CouponUsageHistory;
use App\Models\Module;
use App\Models\OrganizerPaymentKeys;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Guard;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Modules\Seatmap\Entities\Rows;
use Modules\Seatmap\Entities\SeatMaps;
use Modules\Seatmap\Entities\Seats;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Throwable;
use Vonage\Client as VonageClient;
use Vonage\SMS\Message\SMS;
use Vonage\SMS\Message\SMSCollection;
use DB;

class FrontendController extends Controller
{
    public function __construct()
    {



        $language = Session::get('locale');

        if ($language == null) {
            $lang = "English";
            App::setLocale($lang);
            session()->put('locale', $lang);
            $dir = Language::where('name', $lang)->first()->direction;
            session()->put('direction', $dir);
        }
        if (env('DB_DATABASE') != null) {
            // (new AppHelper)->mailConfig();
            (new AppHelper)->eventStatusChange();
        }
    }

    public function home()
    {

        if (env('DB_DATABASE') == null) {
            return view('admin.frontpage');
        } else {
            $setting = Setting::first(['app_name', 'logo']);
            $language = Setting::first()->language;
            SEOMeta::setTitle($setting->app_name . ' - Home' ?? env('APP_NAME'))
                ->setDescription('This is home page')
                ->setCanonical(url()->current())
                ->addKeyword(['home page', $setting->app_name, $setting->app_name . ' Home']);

            OpenGraph::setTitle($setting->app_name . ' - Home' ?? env('APP_NAME'))
                ->setDescription('This is home page')
                ->setUrl(url()->current());

            JsonLdMulti::setTitle($setting->app_name . ' - Home' ?? env('APP_NAME'));
            JsonLdMulti::setDescription('This is home page');
            JsonLdMulti::addImage($setting->imagePath . $setting->logo);

            SEOTools::setTitle($setting->app_name . ' - Home' ?? env('APP_NAME'));
            SEOTools::setDescription('This is home page');
            SEOTools::opengraph()->setUrl(url()->current());
            SEOTools::setCanonical(url()->current());
            SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);


            $timezone = Setting::find(1)->timezone;
            $date = Carbon::now($timezone);
            $events  = Event::with(['category:id,name'])
                ->where([['status', 1], ['orderby', "!=", 0], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d H:i:s')]])
                ->orderBy('orderby', 'asc')->orderBy('start_time', 'asc')->limit(3)->get();

            $pervious_events  = Event::with(['category:id,name'])
                ->where([['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '<', $date->format('Y-m-d H:i:s')]])
                ->orderBy('start_time', 'asc')->limit(3)->get();
            $organizer = User::role('Organizer')->orderBy('id', 'DESC')->get();
            $category = Category::where('status', 1)->orderBy('id', 'DESC')->get();

            $blog = Blog::with(['category:id,name'])->where('status', 1)->orderBy('id', 'DESC')->get();
            foreach ($events as $value) {
                $value->total_ticket = Ticket::where([['event_id', $value->id], ['is_deleted', 0], ['status', 1]])->sum('quantity');
                $value->sold_ticket = Order::where('event_id', $value->id)->sum('quantity');
                $value->available_ticket = $value->total_ticket - $value->sold_ticket;
            }
            $banner = Banner::with('event')->where('status', 1)->get();
            $user = Auth::guard('appuser')->user();
            $showLinkBanner = Setting::find(1, ['show_link_banner', 'googleplay_link', 'appstore_link']);
            return view('frontend.home', compact('events', 'organizer', 'category', 'blog', 'banner', 'user', 'showLinkBanner', 'language', 'pervious_events'));
        }
    }
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
    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required',
        ]);

        $userdata = array(
            'email' => $request->email,
            'password' => $request->password,
        );
        // $data = $request->all();
        // $setting = Setting::first();
        // if($data['type'] == "organizer")
        // {
        //     $user = User::where('email',$request->email)->first();
        // }
        // else{
        //     $user = AppUser::where('email',$request->email)->first();
        // }

        // $otp = rand(100000, 999999);
        // $to = $user->phone;
        // $message = "Your phone verification code is $otp for $setting->app_name.";
        // if(true)
        // {


        //     //try {
        //     $curl = curl_init();

        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL => 'https://api.taqnyat.sa/v1/messages',
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'POST',
        //         CURLOPT_POSTFIELDS =>'{
        //         "recipients": [
        //             966509108875
        //         ],
        //         "body":"Testing message from Kabir by taqnyat",
        //         "sender":"DARLANA"
        //     }',
        //         CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json',
        //         'Authorization: Bearer 647c5f992f7202a353e21ca58ecfa788'
        //         ),
        //     ));

        //     $response = curl_exec($curl);

        //     curl_close($curl);
        //     $responseData = json_decode($response, true);

        //     // if(isset($responseData['statusCode']) && $responseData['statusCode'] == 201)
        //     if(true)
        //     {
        //         if ($data['type'] == 'organizer') {
        //             $user = User::find($user->id);
        //             $user->otp = $otp;
        //             $user->update();
        //             return redirect('organizer/otp-verify/' . $user->id)->with(['success' => "Phone verification code sent via SMS."]);
        //         } else {
        //             $user = AppUser::find($user->id);
        //             $user->otp = $otp;
        //             $user->update();
        //             return redirect('user/otp-verify/' . $user->id)->with(['success' => "Phone verification code sent via SMS."]);
        //         }
        //     }
        //     else{
        //         return redirect()->back()->with('error', 'Somthing Went Wrong');
        //     }
        //     // } catch (\Throwable $th) {
        //     //     return redirect()->back()->with('error', 'Somthing Went Wrong');
        //     // }
        // }
        $remember = $request->get('remember');
        if ($request->type == 'user') {
            if (Auth::guard('appuser')->attempt($userdata, $remember)) {
                $user =  Auth::guard('appuser')->user();
                $setting = Setting::first();
                if ($user->status == 0) {
                    $request->session()->flush();
                    return redirect('user/login')->with('error_msg', 'Blocked By Admin.');
                }
                if (!$setting->user_verify) {
                    return redirect()->intended('/');
                } else {
                    if (!$user->is_verify) {
                        if ($setting->verify_by == 'email' && $setting->mail_host != NULL) {
                            $details = [
                                'url' => url('user/VerificationConfirm/' .  $user->id)
                            ];
                            Mail::to($user->email)->send(new \App\Mail\VerifyMail($details));
                            $request->session()->flush();
                            return redirect('user/login')->with(['success' => "Verification link has been sent to your email. Please visit that link to complete the verification"]);
                        }
                        if ($setting->verify_by == 'phone') {
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
                            $request->session()->flush();
                            return redirect('user/otp-verify/' . $user->id)->with(['success' => "Phone verification code sent via SMS."]);
                        }
                    } else {
                        if (isset($request->checkout)) {
                            return redirect()->to('/' . $request->checkout);
                        } else {
                            return redirect()->intended('/');
                        }
                    }
                }
                $this->setLanguage($user);
            } else {
                return Redirect::back()->with('error_msg', 'Invalid Username or Password.');
            }
        }
        if ($request->type == 'org') {
            if (Auth::attempt($userdata, $remember)) {
                if (Auth::user()->hasRole('Organizer')) {
                    $user =  Auth::user();
                    $setting = Setting::first();
                    if (Auth::user()->status == 0) {
                        $request->session()->flush();
                        return redirect('user/login')->with('error_msg', 'Blocked By Admin.');
                    }
                    if (Auth::user()->is_organizer_approve == 0) {
                        $request->session()->flush();
                        return redirect('user/login')->with('error_msg', 'Not Approved By Admin.');
                    }
                    if (!$setting->user_verify) {
                        return redirect()->intended('organization/home');
                    } else {
                        if (!$user->is_verify) {
                            if ($setting->verify_by == 'email' && $setting->mail_host != NULL) {
                                $details = [
                                    'url' => url('organizer/VerificationConfirm/' .  $user->id)
                                ];
                                Mail::to($user->email)->send(new \App\Mail\VerifyMail($details));
                                $request->session()->flush();
                                return redirect('user/login')->with(['success' => "Verification link has been sent to your email. Please visit that link to complete the verification"]);
                            }
                            if ($setting->verify_by == 'phone') {
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
                                $user = User::find($user->id);
                                $user->otp = $otp;
                                $user->update();
                                $request->session()->flush();
                                return redirect('organizer/otp-verify/' . $user->id)->with(['success' => "Phone verification code sent via SMS."]);
                            }
                        } else {
                            return redirect()->intended('organization/home');
                        }
                    }
                } else {
                    Auth::logout();
                    return Redirect::back()->with('error_msg', 'Only authorized person can login.');
                }
            } else {
                return Redirect::back()->with('error_msg', 'Invalid Username or Password.');
            }
        }
    }

    public function userLogout(Request $request)
    {
        if (Auth::guard('appuser')->check()) {
            Auth::guard('appuser')->logout();
            return redirect('/');
        }
    }
    public function register(Request $request)
    {
        if (Auth::guard('appuser')->check() || Auth::check()) {
            return redirect()->back();
        }
        $setting = Setting::first(['app_name', 'logo']);

        SEOMeta::setTitle($setting->app_name . ' - Register' ?? env('APP_NAME'))
            ->setDescription('This is register page')
            ->setCanonical(url()->current())
            ->addKeyword([
                'register page',
                $setting->app_name,
                $setting->app_name . ' Register',
                'sign-up page',
                $setting->app_name . ' sign-up'
            ]);

        OpenGraph::setTitle($setting->app_name . ' - Register' ?? env('APP_NAME'))
            ->setDescription('This is register page')
            ->setUrl(url()->current());

        JsonLdMulti::setTitle($setting->app_name . ' - Register' ?? env('APP_NAME'));
        JsonLdMulti::setDescription('This is register page');
        JsonLdMulti::addImage($setting->imagePath . $setting->logo);

        SEOTools::setTitle($setting->app_name . ' - Register' ?? env('APP_NAME'));
        SEOTools::setDescription('This is register page');
        SEOTools::opengraph()->addProperty(
            'keywords',
            [
                'register page',
                $setting->app_name,
                $setting->app_name . ' Register',
                'sign-up page',
                $setting->app_name . ' sign-up'
            ]
        );
        SEOTools::opengraph()->addProperty('image', $setting->imagePath . $setting->logo);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);
        $logo = Setting::find(1)->logo;
        $phone = Country::get();

        return view('frontend.auth.register', compact('logo', 'phone', 'request'));
    }
    public function sendOtp($request, $user)
    {
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
        return redirect()->route('form.show')->withInput()->with(['success' => "Phone verification code sent via SMS."]);
    }
    public function userRegister(Request $request)
    {

        $verify = 0; //Setting::first()->user_verify == 1 ? 0 : 1;
        $data = $request->all();
        if ($data['user_type'] == 'user') {
            $request->validate([
                'name' => 'bail|required',
            ]);
        }
        $request->validate([
            'last_name' => 'bail|required',
            'email' => 'bail|required|email|unique:app_user|unique:users',
            'phone' => 'bail|required|numeric',
            'password' => 'bail|required|min:6',
            'Countrycode' => 'bail|required',
        ]);

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

                $to = str_replace('+', '', $user->phone);
                $message = "Your phone verification code is $otp for $setting->app_name.";
                if (true) {

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

                    // if(isset($responseData['statusCode']) && $responseData['statusCode'] == 201)
                    if (true) {
                        if ($data['user_type'] == 'organizer') {

                            $user = User::find($user->id);
                            $dataemail['name'] = $user->first_name . " " . $user->last_name;
                            $dataemail['email'] = $user->email;
                            $dataemail['otp'] = $otp;
                            $data = array('name' => "TicketBy", 'email' => 'hivasavada@gmail.com', "otp" => $otp);
                            Mail::send(['html' => 'frontend.email.otp'], $data, function ($message) use ($data) {
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

                            $data = array('name' => "TicketBy", 'email' => 'hivasavada@gmail.com', "otp" => $otp);
                            Mail::send(['html' => 'frontend.email.otp'], $data, function ($message) use ($data) {
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


                    return redirect('user/otp-verify/' . $user->id)->with(['success' => "Phone verification code sent via SMS."]);
                }
            }
        }
        return redirect('user/login')->with(['success' => "Congratulations! Your account registration was successful. You can now log in to your account and start using our services. Thank you for choosing our platform"]);
    }
    public function LoginByMail($id)
    {
        $user = AppUser::find($id);
        if (Auth::guard('appuser')->loginUsingId($id)) {
            $user =  Auth::guard('appuser')->user();
            $verify = AppUser::find($user->id);
            $verify->email_verified_at = Carbon::now();
            $verify->is_verify = 1;
            $verify->update();
            $this->setLanguage($user);
            return redirect('/');
        }
    }
    public function LoginByMailOrganizer($id)
    {
        $user = User::find($id);
        if (Auth::loginUsingId($id)) {
            $user =  Auth::user();
            $verify = User::find($user->id);
            $verify->email_verified_at = Carbon::now();
            $verify->is_verify = 1;
            $verify->update();
            $this->setLanguage($user);
            return redirect('organization/home');
        }
    }
    public function resetPassword()
    {
        $setting = Setting::first(['app_name', 'logo']);
        SEOMeta::setTitle($setting->app_name . ' - reset password' ?? env('APP_NAME'))
            ->setDescription('This is reset password page')
            ->setCanonical(url()->current())
            ->addKeyword([
                'reset password page',
                $setting->app_name,
                $setting->app_name . ' reset password',
                'forgot password page',
                $setting->app_name . ' forgot password'
            ]);

        OpenGraph::setTitle($setting->app_name . ' - reset password' ?? env('APP_NAME'))
            ->setDescription('This is reset password page')
            ->setUrl(url()->current());

        JsonLdMulti::setTitle($setting->app_name . ' - reset password' ?? env('APP_NAME'));
        JsonLdMulti::setDescription('This is reset password page');
        JsonLdMulti::addImage($setting->imagePath . $setting->logo);

        SEOTools::setTitle($setting->app_name . ' - reset password' ?? env('APP_NAME'));
        SEOTools::setDescription('This is reset password page');
        SEOTools::opengraph()->addProperty(
            'keywords',
            [
                'reset password page',
                $setting->app_name,
                $setting->app_name . ' reset password',
                'forgot password page',
                $setting->app_name . ' forgot password'
            ]
        );
        SEOTools::opengraph()->addProperty('image', $setting->imagePath . $setting->logo);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);
        return view('frontend.auth.resetPassword');
    }

    public function userResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email',
        ]);
        if ($request->type == 'user') {
            $user = AppUser::where('email', $request->email)->first();
        } else {
            $user = User::where('email', $request->email)->first();
        }
        $password = rand(100000, 999999);
        if ($user) {
            $content = NotificationTemplate::where('title', 'Reset Password')->first()->mail_content;
            $detail['user_name'] = $user->name;
            $detail['password'] = $password;
            $detail['app_name'] = Setting::find(1)->app_name;
            if ($request->type == 'user') {
                AppUser::find($user->id)->update(['password' => Hash::make($password)]);
            } else {
                User::find($user->id)->update(['password' => Hash::make($password)]);
            }
            try {
                Mail::to($user->email)->send(new ResetPassword($content, $detail));
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', $th->getMessage());
            }
            return redirect()->route('user.login')->with('success', 'New password will send in your mail, please check it.');
        } else {
            return Redirect::back()->with('error', 'Invalid Email Id, Please try another.');
        }
    }

    public function orgRegister()
    {
        $setting = Setting::first(['app_name', 'logo']);

        SEOMeta::setTitle($setting->app_name . ' - Organizer Register' ?? env('APP_NAME'))
            ->setDescription('This is organizer register page')
            ->setCanonical(url()->current())
            ->addKeyword([
                'organizer register page',
                $setting->app_name,
                $setting->app_name . ' Organizer Register',
                'organizer sign-up page',
                $setting->app_name . ' organizer sign-up'
            ]);

        OpenGraph::setTitle($setting->app_name . ' - Organizer Register' ?? env('APP_NAME'))
            ->setDescription('This is organizer register page')
            ->setUrl(url()->current());

        JsonLdMulti::setTitle($setting->app_name . ' - Organizer Register' ?? env('APP_NAME'));
        JsonLdMulti::setDescription('This is organizer register page');
        JsonLdMulti::addImage($setting->imagePath . $setting->logo);

        SEOTools::setTitle($setting->app_name . ' - Organizer Register' ?? env('APP_NAME'));
        SEOTools::setDescription('This is register page');
        SEOTools::opengraph()->addProperty(
            'keywords',
            [
                'register page',
                $setting->app_name,
                $setting->app_name . ' Organizer Register',
                'organizer sign-up page',
                $setting->app_name . ' organizer sign-up'
            ]
        );
        SEOTools::opengraph()->addProperty('image', $setting->imagePath . $setting->logo);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);
        return view('frontend.auth.orgRegister');
    }

    public function organizerRegister(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'first_name' => 'bail|required',
            'last_name' => 'bail|required',
            'email' => 'bail|required|email|unique:users',
            'phone' => 'bail|required|numeric',
            'password' => 'bail|required|min:6',
            'confirm_password' => 'bail|required|min:6|same:password',
            'country' => 'bail|required',
        ]);
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['image'] = 'defaultuser.png';
        $data['language'] = Setting::first()->language;
        $user = User::create($data);
        $user->assignRole('Organizer');
        OrganizerPaymentKeys::create([
            'organizer_id' => $user->id,
        ]);

        return redirect('login');
    }
    public function previousEvent(Request $request)
    {
        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);
        $events  = Event::with(['category:id,name'])
            ->where([['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '<', $date->format('Y-m-d')]])->limit(10)->get();

        return view('frontend.previous_events', compact('events'));
    }

    public function allEvents(Request $request)
    {
        (new AppHelper)->eventStatusChange();
        $setting = Setting::first(['app_name', 'logo']);

        SEOMeta::setTitle($setting->app_name . ' - All-Events' ?? env('APP_NAME'))
            ->setDescription('This is all events page')
            ->setCanonical(url()->current())
            ->addKeyword([
                'all event page',
                $setting->app_name,
                $setting->app_name . ' All-Events',
                'events page',
                $setting->app_name . ' Events',
            ]);

        OpenGraph::setTitle($setting->app_name . ' - All-Events' ?? env('APP_NAME'))
            ->setDescription('This is all events page')
            ->setUrl(url()->current());

        JsonLdMulti::setTitle($setting->app_name . ' - All-Events' ?? env('APP_NAME'));
        JsonLdMulti::setDescription('This is all events page');
        JsonLdMulti::addImage($setting->imagePath . $setting->logo);

        SEOTools::setTitle($setting->app_name . ' - All-Events' ?? env('APP_NAME'));
        SEOTools::setDescription('This is all events page');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('keywords', [
            'all event page',
            $setting->app_name,
            $setting->app_name . ' All-Events',
            'events page',
            $setting->app_name . ' Events',
        ]);
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);

        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);
        $events  = Event::with(['category:id,name'])
            ->where([['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d')]]);

        $chip = array();
        if ($request->has('type') && $request->type != null) {
            $chip['type'] = $request->type;
            $events = $events->where('type', $request->type);
        }
        if ($request->has('category') && $request->category != null) {
            $chip['category'] = Category::find($request->category)->name;
            $events = $events->where('category_id', $request->category);
        }
        if ($request->has('duration') && $request->duration != null) {
            $chip['date'] = $request->duration;
            if ($request->duration == 'Today') {
                $temp = Carbon::now($timezone);
                $events = $events->where('start_time', '<=', $temp);
            } else if ($request->duration == 'Tomorrow') {
                $temp = Carbon::tomorrow($timezone);
                $events = $events->where('start_time', '<=', $temp);
            } else if ($request->duration == 'ThisWeek') {
                $now = Carbon::now($timezone);
                $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i:s');
                $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i:s');
                $events = $events->where('start_time', '<=', $weekEndDate);
            } else if ($request->duration == 'date') {
                if (isset($request->date)) {
                    $temp = Carbon::parse($request->date)->format('Y-m-d H:i:s');
                    $events = $events->where('start_time', '<=', $temp)->where('end_time', '>=', $temp);
                }
            }
        }
        $events = $events->orderBy('start_time', 'ASC')->get();
        foreach ($events as $value) {
            $value->total_ticket = Ticket::where([['event_id', $value->id], ['is_deleted', 0], ['status', 1]])->sum('quantity');
            $value->sold_ticket = Order::where('event_id', $value->id)->sum('quantity');
            $value->available_ticket = $value->total_ticket - $value->sold_ticket;
        }
        $user = Auth::guard('appuser')->user();
        $offlinecount = 0;
        $onlinecount = 0;
        foreach ($events as $key => $value) {
            if ($value->type == 'online') {
                $onlinecount += 1;
            }
            if ($value->type == 'offline') {
                $offlinecount += 1;
            }
        }

        return view('frontend.events', compact('user', 'events', 'chip', 'onlinecount', 'offlinecount'));
    }

    public function eventDetail($id, $name = null)
    {

        $setting = Setting::first(['app_name', 'logo']);
        $currency = "$"; //Setting::first(['currency_sybmol']);

        $data = Event::with(['category:id,name,image', 'organization:id,first_name,organization_name,bio,last_name,image'])->find($id);
        SEOMeta::setTitle($data->name)
            ->setDescription($data->description)
            ->addMeta('event:category', $data->category->name, 'property')
            ->addKeyword([
                $setting->app_name,
                $data->name,
                $setting->app_name . ' - ' . $data->name,
                $data->category->name,
                $data->tags
            ]);

        OpenGraph::setTitle($data->name)
            ->setDescription($data->description)
            ->setUrl(url()->current())
            ->addImage($data->imagePath . $data->image)
            ->setArticle([
                'start_time' => $data->start_time,
                'end_time' => $data->end_time,
                'organization' => $data->organization->name,
                'catrgory' => $data->category->name,
                'type' => $data->type,
                'address' => $data->address,
                'tag' => $data->tags,
            ]);


        JsonLd::setTitle($data->name)
            ->setDescription($data->description)
            ->setType('Article')
            ->addImage($data->imagePath . $data->image);

        SEOTools::setTitle($data->name);
        SEOTools::setDescription($data->description);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('keywords', [
            $setting->app_name,
            $data->name,
            $setting->app_name . ' - ' . $data->name,
            $data->category->name,
            $data->tags
        ]);
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);
        SEOTools::jsonLd()->addImage($data->imagePath . $data->image);
        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);

        $data->free_ticket = Ticket::where([['event_id', $data->id], ['is_deleted', 0], ['type', 'free'], ['status', 1], ['end_time', '>=', $date->format('Y-m-d H:i:s')], ['start_time', '<=', $date->format('Y-m-d H:i:s')]])->orderBy('id', 'DESC')->get();
        $data->paid_ticket = Ticket::where([['event_id', $data->id], ['is_deleted', 0], ['type', 'paid'], ['status', 1], ['end_time', '>=', $date->format('Y-m-d H:i:s')], ['start_time', '<=', $date->format('Y-m-d H:i:s')]])->orderBy('id', 'DESC')->get();

        $data->review = Review::where('event_id', $data->id)->orderBy('id', 'DESC')->get();
        // foreach ($data->paid_ticket as $value) {
        //     $used = Order::where('ticket_id', $value->id)->sum('quantity');
        //     $value->available_qty = $value->quantity - $used;
        // }
        $total_paid =  $used_paid = 0;
        foreach ($data->paid_ticket as $value) {
            if ($data->is_repeat == 1) {

                $start_date = Carbon::now()->subDays(1)->format("Y-m-d") . " " . Carbon::parse($data->end_time)->format("H:i:s");
                $end_date = Carbon::now()->format("Y-m-d") . " " . Carbon::parse($data->start_time)->format("H:i:s");
                $used = Order::where('ticket_id', $value->id)->where('created_at', '<', $end_date)->where('created_at', '>', $start_date)->sum('quantity');
                //dd($start_date , $end_date);
                $total_paid  =  $total_paid  +  $value->quantity;
                $used_paid = $used_paid + $used;
            } else {
                $used = Order::where('ticket_id', $value->id)->sum('quantity');
                $total_paid  =  $total_paid  +  $value->quantity;
                $used_paid = $used_paid + $used;
            }
        }

        foreach ($data->paid_ticket as $value) {
            $value->available_qty = $total_paid  - $used_paid;
        }

        foreach ($data->free_ticket as $value) {
            $used = Order::where('ticket_id', $value->id)->sum('quantity');
            $value->available_qty = $value->quantity - $used;
        }
        $images = explode(",", string: $data->gallery);
        $tags =  explode(",", $data->tags);
        $appUser = Auth::guard('appuser')->user();
        $rate = round(Review::where('event_id', $data->id)->avg('rate'));
        if ($data->category_id == 14) {
            $ticket_detail = DB::table('event_ticket_details')->get();
        } else {
            $ticket_detail = null;
        }
        // dd($data);
        // dd($tags);
        //dd($data->paid_ticket[0]->available_qty);
        // return view('frontend.eventDetail', compact('currency', 'data', 'images', 'tags', 'appUser', 'rate' , 'ticket_detail'));
        return view('front.eventDetail', compact('currency', 'data', 'images', 'tags', 'appUser', 'rate', 'ticket_detail'));
    }

    public function orgDetail($id)
    {
        $setting = Setting::first(['app_name', 'logo']);
        $data = User::find($id);

        SEOMeta::setTitle(($data->first_name ?? '') . ' ' . ($data->last_name ?? ''))
            ->setDescription($data->bio)
            ->addKeyword([
                $setting->app_name,
                $data->name,
                ($data->first_name ?? '') . ' ' . ($data->last_name ?? ''),
            ]);

        OpenGraph::setTitle(($data->first_name ?? '') . ' ' . $data->last_name ?? '')
            ->setDescription($data->bio)
            ->setType('profile')
            ->setUrl(url()->current())
            ->addImage($data->imagePath . $data->image)
            ->setProfile([
                'first_name' => ($data->first_name ?? ''),
                'last_name' => ($data->last_name ?? ''),
                'username' => $data->name,
                'email' => $data->email,
                'bio' => $data->bio,
                'country' => $data->country,
            ]);

        JsonLd::setTitle(($data->first_name ?? '') . ' ' . ($data->last_name ?? ''))
            ->setDescription($data->bio)
            ->setType('Profile')
            ->addImage($data->imagePath . $data->image);

        SEOTools::setTitle(($data->first_name ?? '') . ' ' . ($data->last_name ?? ''));
        SEOTools::setDescription($data->bio);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('keywords', [
            $setting->app_name,
            $data->name,
            ($data->first_name ?? '') . ' ' . ($data->last_name ?? ''),
        ]);
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);
        SEOTools::jsonLd()->addImage($data->imagePath . $data->image);

        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);
        $data->total_event = Event::where([['status', 1], ['is_deleted', 0], ['user_id', $id], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d H:i:s')]])->count();
        $data->events = Event::where([['status', 1], ['is_deleted', 0], ['user_id', $id], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d H:i:s')]])->orderBy('start_time', 'ASC')->get();
        return view('frontend.orgDetail', compact('data'));
    }

    public function reportEvent(Request $request)
    {
        $data = $request->all();
        if (Auth::guard('appuser')->check()) {
            $data['user_id'] = Auth::guard('appuser')->user()->id;
        }
        EventReport::create($data);
        return redirect()->back()->withStatus(__('Report is submitted successfully.'));
    }

    public function checkoutFront(Request $request)
    {
        $ids = $request->input('ids');

        // dd( $ids);
    }

    public function checkout(Request $request, $id)
    {
        $data = Ticket::find($id);
        $data->event = Event::find($data->event_id);

        $setting = Setting::first();

        SEOMeta::setTitle($data->name)
            ->setDescription($data->description)
            ->addKeyword([
                $setting->app_name,
                $data->name,
                $data->event->name,
                $data->event->tags
            ]);

        OpenGraph::setTitle($data->name)
            ->setDescription($data->description)
            ->setUrl(url()->current());

        JsonLd::setTitle($data->name)
            ->setDescription($data->description);

        SEOTools::setTitle($data->name);
        SEOTools::setDescription($data->description);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('keywords', [
            $setting->app_name,
            $data->name,
            $data->event->name,
            $data->event->tags
        ]);
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);

        $arr = [];
        $used = Order::where('ticket_id', $id)->sum('quantity');
        $data->available_qty = $data->quantity - $used;
        $data->tax = Tax::where([['allow_all_bill', 1], ['status', 1]])->orderBy('id', 'DESC')->get()->makeHidden(['created_at', 'updated_at']);
        foreach ($data->tax as $key => $item) {
            if ($item->amount_type == 'percentage') {

                $amount = ($item->price * $data->price) / 100;
                array_push($arr, $amount);
            }
            if ($item->amount_type == 'price') {
                $amount = $item->price;
                array_push($arr, $amount);
            }
        }
        $data->tax_total = array_sum($arr);
        $data->tax_total = round($data->tax_total, 2);
        $data->currency_code = $setting->currency;
        $data->currency = $setting->currency_sybmol;
        $data->module = Module::where('module', 'Seatmap')->first();
        if ($data->seatmap_id != null && $data->module->is_install == 1 && $data->module->is_enable == 1) {
            $seat_map = SeatMaps::findOrFail($data->seatmap_id);
            $rows = Rows::where('seat_map_id', $data->seatmap_id)->get();
            foreach ($rows as $row) {
                $seats = Seats::where('row_id', $row->id)->get();
                $seatsByRow[$row->id] = $seats;
            }
            $data->seat_map = $seat_map;
            $data->rows = $rows;
            $data->seatsByRow = $seatsByRow;
        }
        $data->totalPersTax = Tax::where([['allow_all_bill', 1], ['status', 1], ['amount_type', 'percentage']])->sum('price');
        $data->totalAmountTax = Tax::where([['allow_all_bill', 1], ['status', 1], ['amount_type', 'price']])->sum('price');
        return view('frontend.checkout', compact('data'));
    }
    public function applyCoupon(Request $request)
    {
        $total = $request->total;
        $date = Carbon::now()->format('Y-m-d');
        $coupon = Coupon::where([['name', $request->coupon_code], ['status', 1]])->first();
        if ($coupon) {
            if (isset(Auth::guard('appuser')->user()->id)) {

                $couponHistory = CouponUsageHistory::where([['coupon_id', $coupon->id], ['appuser_id', Auth::guard('appuser')->user()->id]])->get();
                if (count($couponHistory) >= $coupon->max_use_per_user) {
                    return response([
                        'success' => false,
                        'message' => 'This coupon is reached max use!'
                    ]);
                }
            }

            if (Carbon::parse($date)->between(Carbon::parse($coupon->start_date), Carbon::parse($coupon->end_date))) {
                if ($coupon->max_use > $coupon->use_count) {
                    if ($total > $coupon->minimum_amount) {

                        if ($coupon->discount_type == 0) {
                            $discount = $total * ($coupon->discount / 100);
                        } else {
                            $discount = $coupon->discount;
                        }
                        if ($discount > $coupon->maximum_discount) {
                            $discount = $coupon->maximum_discount;
                        }

                        $subtotal = $total - $discount;
                        $arr = [];
                        $tax = Tax::where([['allow_all_bill', 1], ['status', 1]])->orderBy('id', 'DESC')->get()->makeHidden(['created_at', 'updated_at']);
                        foreach ($tax as $key => $item) {
                            if ($item->amount_type == 'percentage') {

                                $amount = ($item->price * $subtotal) / 100;
                                array_push($arr, $amount);
                            }
                            if ($item->amount_type == 'price') {
                                $amount = $item->price;
                                array_push($arr, $amount);
                            }
                        }

                        $total_tax =  array_sum($arr);

                        $payment =  $subtotal  + array_sum($arr);

                        return response([
                            'success' => true,
                            'payableamount' => $discount,
                            'total_price' => $subtotal,
                            'total' => $total,
                            'discount' => $coupon->discount,
                            'coupon_id' => $coupon->id,
                            'coupon_type' => $coupon->discount_type,
                            'total_tax' => $total_tax,
                            'payment' => $payment
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

    public function createOrder(Request $request)
    {

        $data = $request->all();


        /*if($request->payment_type =="TABBY"  )
        {
            
            $tabby = new Tabby();
           
            $payment = $tabby->makeCurlCall($data);
            
            $response['url'] = $payment ; 
            $response['type'] = "Tabby" ; 
            $response['status'] = 201 ; 
            return response()->json($response);
        }
        */


        if ($request->payment_type == "TAMARA") {

            $tamara = new Tamara();
            $response = array();
            $payment = $tamara->makeCurlCall($data);
            $response['url'] = $payment;
            $response['type'] = "TAMARA";
            $response['status'] = 201;

            return response()->json($response);
        }


        $ticket = Ticket::findOrFail($request->ticket_id);
        /*if ($ticket->allday == 0) {
            $request->validate([
                'ticket_date' => 'bail|required',
            ]);
        }*/
        if ($request->payment_type == 'WALLET') {
            $user = Auth::guard('appuser')->user()->id;
            $user = AppUser::find($user);
            if ($user->balance >= $request->payment) {
                $user->withdraw($request->payment, ['event_id' => $request->ticket_id]);
            } else {
                return response()->json(['success' => false, 'message' => 'Insufficient balance']);
            }
        }
        $event = Event::find($ticket->event_id);

        $org = User::find($event->user_id);
        $user = AppUser::find(Auth::guard('appuser')->user()->id);
        $data['order_id'] = '#' . rand(9999, 100000);
        $data['event_id'] = $event->id;
        $data['customer_id'] = $user->id;
        $data['organization_id'] = $org->id;
        $data['order_status'] = 'Pending';

        if ($user->id == 202) {
            return response("This is eror");
        }

        // if ($request->payment_type == 'LOCAL') {
        $data['payment_status'] = 0;
        $data['order_status'] = 'Pending';
        // } else {
        //     $data['payment_status'] = 1;
        //     $data['order_status'] = 'Complete';
        // }


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
            $coupon = Coupon::find($request->coupon_code);
            $count = $coupon->use_count + 1;
            $coupon->update(['use_count' => $count]);
            CouponUsageHistory::create([
                'coupon_id' => $request->coupon_code,
                'appuser_id' => $user->id
            ]);
            $data['coupon_discount'] = $coupon->discount;
            $data['coupon_id'] = $coupon->id;
        }

        $data['book_seats'] = isset($request->selectedSeatsId) ? $request->selectedSeatsId : null;
        $data['seat_details'] = isset($request->selectedSeats) ? $request->selectedSeats : null;
        $order = Order::create($data);
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

        for ($i = 1; $i <= $request->quantity; $i++) {
            $child['ticket_number'] = uniqid();
            $child['ticket_id'] = $request->ticket_id;
            $child['order_id'] = $order->id;
            $child['customer_id'] = Auth::guard('appuser')->user()->id;
            $child['checkin'] = $ticket->maximum_checkins ?? null;
            $child['paid'] = $request->payment_type == 'LOCAL' ? 0 : 1;
            OrderChild::create($child);
        }
        if (isset($request->tax_data)) {
            foreach (json_decode($data['tax_data']) as $value) {
                $tax['order_id'] = $order->id;
                $tax['tax_id'] = $value->id;
                $tax['price'] = $value->price;
                OrderTax::create($tax);
            }
        }


        Session::put('order_id', $order->id);
        $user = AppUser::find($order->customer_id);
        $setting = Setting::find(1);
        //dd($request);




        // for user notification
        $message = NotificationTemplate::where('title', 'Book Ticket')->first()->message_content;
        $detail['user_name'] = $user->name . ' ' . $user->last_name;
        $detail['quantity'] = $request->quantity;
        $detail['event_name'] = Event::find($order->event_id)->name;
        $detail['date'] = Event::find($order->event_id)->start_time->format('d F Y h:i a');
        $detail['app_name'] = $setting->app_name;
        $noti_data = ["{{user_name}}", "{{quantity}}", "{{event_name}}", "{{date}}", "{{app_name}}"];
        $message1 = str_replace($noti_data, $detail, $message);
        $notification = array();
        $notification['organizer_id'] = null;
        $notification['user_id'] = $user->id;
        $notification['order_id'] = $order->id;
        $notification['title'] = 'Ticket Booked';
        $notification['message'] = $message1;
        Notification::create($notification);
        if ($setting->push_notification == 1) {
            if ($user->device_token != null) {
                (new AppHelper)->sendOneSignal('user', $user->device_token, $message1);
            }
        }
        // for user mail
        $ticket_book = NotificationTemplate::where('title', 'Book Ticket')->first();
        $details['user_name'] = $user->name . ' ' . $user->last_name;
        $details['quantity'] = $request->quantity;
        $details['event_name'] = Event::find($order->event_id)->name;
        $details['date'] = Event::find($order->event_id)->start_time->format('d F Y h:i a');
        $details['app_name'] = $setting->app_name;
        if (true) {

            try {

                // $dataemail['order'] = Order::find($order->id);
                // $dataemail['event'] = Event::find($dataemail['order']->event_id);
                // $dataemail['user'] = AppUser::find($dataemail['order']->customer_id);
                // $dataemail['email'] = $dataemail['user']->email ;
                // //$dataemail['qrcode'] = $order->order_id ;
                // $dataemail['qrcode'] = QrCode::format('png')
                // ->size(300)
                // ->generate($order->order_id, public_path('qrcodes/qr.png'));

                //  Mail::send(['html'=>'emails.ticketbooked'], $dataemail, function($message) use ($dataemail) {
                // $message->to($dataemail['email'])->subject
                //     ('Ticket Booked');
                // $message->from('ticketbyksa@gmail.com','TicketBy');
                // });


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


                $qrcode = $order->order_id;
                // Mail::to($user->email)->send(new TicketBook($ticket_book->mail_content, $details, $ticket_book->subject, $qrcode));
            } catch (\Throwable $th) {
                Log::info($th->getMessage());
            }
        }

        if ($request->payment_type == "EDAFPAY" || $request->payment_type == "CARD") {
            $order->user = AppUser::find($order['customer_id']);

            $response = $this->edafpayCreate($order, $request);
            if ($response['status'] != 200) {
                Order::find($order->id)->forceDelete();
            }
            $r_data = [];
            if (isset($response['url'])) {

                return response()->json(["url" => $response['url'], "body" => $response['params']['body'], "status" => $response['status']]);
            }
            return response()->json($response);
        }

        if ($request->payment_type == "TABBY") {

            $tabby = new Tabby();

            $payment = $tabby->makeCurlCall($data);

            if ($payment['status'] == 500) {
                $response['error_message'] = $payment['error_message'];
                $response['type'] = "Tabby";
                $response['status'] = 500;
                return response()->json($response);
            } else {
                $response['url'] = $payment['url'];
                $response['type'] = "Tabby";
                $response['status'] = 201;
                return response()->json($response);
            }
        }


        // for Organizer notification
        // $org =  User::find($order->organization_id);
        // $or_message = NotificationTemplate::where('title', 'Organizer Book Ticket')->first()->message_content;
        // $or_detail['organizer_name'] = $org->organization_name;
        // $or_detail['user_name'] = $user->name . ' ' . $user->last_name;
        // $or_detail['quantity'] = $request->quantity;
        // $or_detail['event_name'] = Event::find($order->event_id)->name;
        // $or_detail['date'] = Event::find($order->event_id)->start_time->format('d F Y h:i a');
        // $or_detail['app_name'] = $setting->app_name;
        // $or_noti_data = ["{{organizer_name}}", "{{user_name}}", "{{quantity}}", "{{event_name}}", "{{date}}", "{{app_name}}"];
        // $or_message1 = str_replace($or_noti_data, $or_detail, $or_message);
        // $or_notification = array();
        // $or_notification['organizer_id'] =  $org->id;
        // $or_notification['user_id'] = null;
        // $or_notification['order_id'] = $order->id;
        // $or_notification['title'] = 'New Ticket Booked';
        // $or_notification['message'] = $or_message1;
        // Notification::create($or_notification);
        // if ($setting->push_notification == 1) {
        //     if ($org->device_token != null) {
        //         (new AppHelper)->sendOneSignal('organizer', $org->device_token, $or_message1);
        //     }
        // }
        // for Organizer mail
        // $new_ticket = NotificationTemplate::where('title', 'Organizer Book Ticket')->first();
        // $details1['organizer_name'] = $org->first_name . ' ' . $org->last_name;
        // $details1['user_name'] = $user->name . ' ' . $user->last_name;
        // $details1['quantity'] = $request->quantity;
        // $details1['event_name'] = Event::find($order->event_id)->name;
        // $details1['date'] = Event::find($order->event_id)->start_time->format('d F Y h:i a');
        // $details1['app_name'] = $setting->app_name;

        // try{



        // Mail::send(['html'=>'emails.ticketbooked'], $dataemail, function($message) use ($dataemail) {
        // $message->to($dataemail['email'])->subject
        //     ('Ticket Booked');
        // $message->from('ticketbyksa@gmail.com','TicketBy');
        // });


        // faris and groom mail 




        // } catch (\Throwable $th) {
        //         Log::info($th->getMessage());
        //     }

        if ($setting->mail_notification == 1) {
            try {

                //Mail::to($org->email)->send(new TicketBookOrg($new_ticket->mail_content, $details1, $new_ticket->subject));
            } catch (\Throwable $th) {
                Log::info($th->getMessage());
            }
        }

        return response()->json(['success' => true, 'message' => 'Payment successful']);
    }
    public function sendOrderMail($id)
    {

        //$order_update = Order::where('id',$id)->update(['payment_status'=>1 , 'order_status' =>'Complete']);
        $order = Order::find($id);


        $dataemail['order'] = $order;
        $dataemail['event'] = Event::find($dataemail['order']->event_id);
        $dataemail['user'] = AppUser::find($dataemail['order']->customer_id);
        $dataemail['email'] = $dataemail['user']->email;
        //$dataemail['qrcode'] = $order->order_id ;
        $child_qr =  OrderChild::where('order_id', $order->id)->get();
        foreach ($child_qr as $key => $value) {
            $dataemail['qrcode'] = QrCode::format('png')
                ->size(300)
                ->generate($value->ticket_number, public_path('qrcodes/qr-' . $value->id . '.png'));
            $dataemail['qr_id'] = $value->id;
            Mail::send(['html' => 'emails.ticketbooked'], $dataemail, function ($message) use ($dataemail) {
                $message->to($dataemail['email'])->subject('Ticket Booked');
                $message->from('ticketbyksa@gmail.com', 'TicketBy');
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
        $order_update = Order::where('id', $id)->update(['payment_status' => 1, 'order_status' => 'Complete']);
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
        return true;
    }
    public function categoryEvents($id, $name)
    {
        $setting = Setting::first(['app_name', 'logo']);
        $category = Category::find($id);

        SEOMeta::setTitle($setting->app_name . '- Events' ?? env('APP_NAME'))
            ->setDescription('This is category events page')
            ->setCanonical(url()->current())
            ->addKeyword([
                'category event page',
                $category->name . ' - Events',
                $setting->app_name,
                $setting->app_name . ' Events',
                'events page',
            ]);

        OpenGraph::setTitle($setting->app_name . ' - Events' ?? env('APP_NAME'))
            ->setDescription('This is category events page')
            ->setUrl(url()->current());

        JsonLdMulti::setTitle($setting->app_name . ' - Events' ?? env('APP_NAME'));
        JsonLdMulti::setDescription('This is category events page');
        JsonLdMulti::addImage($setting->imagePath . $setting->logo);

        SEOTools::setTitle($setting->app_name . ' - Events' ?? env('APP_NAME'));
        SEOTools::setDescription('This is category events page');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('keywords', [
            'category event page',
            $category->name . ' - Events',
            $setting->app_name,
            $setting->app_name . ' Events',
            'events page',
        ]);
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);

        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);
        $events  = Event::with(['category:id,name'])
            ->where([['status', 1], ['is_deleted', 0], ['category_id', $id], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d H:i:s')]])
            ->orderBy('start_time', 'ASC')->get();
        $offlinecount = 0;
        $onlinecount = 0;
        foreach ($events as $key => $value) {
            if ($value->type == 'online') {
                $onlinecount += 1;
            }
            if ($value->type == 'offline') {
                $offlinecount += 1;
            }
        }
        $user = Auth::guard('appuser')->user();
        $catactive = $name;
        return view('frontend.events', compact('events', 'category', 'onlinecount', 'offlinecount', 'user', 'catactive'));
    }

    public function eventType($type)
    {
        $setting = Setting::first(['app_name', 'logo']);

        SEOMeta::setTitle($setting->app_name . ' - All-Events' ?? env('APP_NAME'))
            ->setDescription('This is all events page')
            ->setCanonical(url()->current())
            ->addKeyword([
                'all event page',
                $setting->app_name,
                $setting->app_name . ' All-Events',
                'events page',
                $setting->app_name . ' Events',
            ]);

        OpenGraph::setTitle($setting->app_name . ' - All-Events' ?? env('APP_NAME'))
            ->setDescription('This is all events page')
            ->setUrl(url()->current());

        JsonLdMulti::setTitle($setting->app_name . ' - All-Events' ?? env('APP_NAME'));
        JsonLdMulti::setDescription('This is all events page');
        JsonLdMulti::addImage($setting->imagePath . $setting->logo);

        SEOTools::setTitle($setting->app_name . ' - All-Events' ?? env('APP_NAME'));
        SEOTools::setDescription('This is all events page');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('keywords', [
            'all event page',
            $setting->app_name,
            $setting->app_name . ' All-Events',
            'events page',
            $setting->app_name . ' Events',
        ]);
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);


        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);
        if ($type == "all") {
            $events  = Event::with(['category:id,name'])
                ->where([['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d H:i:s')]])
                ->orderBy('start_time', 'ASC')->get();

            return view('frontend.events', compact('events'));
        } else {
            $events  = Event::with(['category:id,name'])
                ->where([['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['type', $type], ['end_time', '>', $date->format('Y-m-d H:i:s')]])
                ->orderBy('start_time', 'ASC')->get();
            return view('frontend.events', compact('events', 'type'));
        }
    }

    public function allCategory()
    {
        $setting = Setting::first(['app_name', 'logo']);

        SEOMeta::setTitle($setting->app_name . ' - Category' ?? env('APP_NAME'))
            ->setDescription('This is all category page')
            ->setCanonical(url()->current())
            ->addKeyword([
                'all event page',
                $setting->app_name,
                $setting->app_name . ' Category',
                'category page',
                $setting->app_name . ' category',
            ]);

        OpenGraph::setTitle($setting->app_name . ' - Category' ?? env('APP_NAME'))
            ->setDescription('This is all category page')
            ->setUrl(url()->current());

        JsonLdMulti::setTitle($setting->app_name . ' - Category' ?? env('APP_NAME'));
        JsonLdMulti::setDescription('This is all category page');
        JsonLdMulti::addImage($setting->imagePath . $setting->logo);

        SEOTools::setTitle($setting->app_name . ' - Category' ?? env('APP_NAME'));
        SEOTools::setDescription('This is all category page');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('keywords', [
            'all event page',
            $setting->app_name,
            $setting->app_name . ' Category',
            'category page',
            $setting->app_name . ' category',
        ]);
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);
        $data = Category::where('status', 1)->orderBy('id', 'DESC')->get();
        $catactive = 'all';

        return view('frontend.allCategory', compact('data', 'catactive'));
    }

    public function blogs()
    {

        $phone = "+966509108875";
        $to = str_replace('+', '', $phone);

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
                "body":"Testing message from Kabir by taqnyat",
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
        dd($responseData);

        $id = $payment->id;

        $redirect_url = $payment->configuration->available_products->installments[0]->web_url;

        return redirect($redirect_url);

        dd("l");
        $blogs = Blog::where('status', 1)->orderBy('id', 'DESC')->get();
        $category = Category::where('status', 1)->orderBy('id', 'DESC')->get();
        $setting = Setting::first(['app_name', 'logo']);
        SEOMeta::setTitle($setting->app_name . ' - Blogs' ?? env('APP_NAME'))
            ->setDescription('This is blogs page')
            ->setCanonical(url()->current())
            ->addKeyword([
                'blogs page',
                $setting->app_name,
                $setting->app_name . ' Blogs',
                'blog page',
            ]);
        OpenGraph::setDescription('This is blogs page');
        OpenGraph::setTitle($setting->app_name . ' - Blogs' ?? env('APP_NAME'));
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'blogs');
        JsonLd::setTitle($setting->app_name . ' - Blogs' ?? env('APP_NAME'));
        JsonLd::setDescription('This is blogs page');
        JsonLd::addImage($setting->imagePath . $setting->logo);
        SEOTools::setTitle($setting->app_name . ' - Blogs' ?? env('APP_NAME'));
        SEOTools::setDescription('This is blogs page');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'blogs');
        $user = Auth::guard('appuser')->user();
        return view('frontend.blog', compact('blogs', 'category', 'user'));
    }

    public function blogDetail($id, $name)
    {
        $setting = Setting::first(['app_name', 'logo']);

        $data = Blog::find($id);
        $data->category = Category::find($data->category_id);
        $tags = explode(',', $data->tags);
        SEOMeta::setTitle($data->title);
        SEOMeta::setDescription($data->description);
        SEOMeta::addMeta('blog:published_time', $data->created_at->toW3CString(), 'property');
        SEOMeta::addMeta('blog:category', $data->category->name, 'property');
        SEOMeta::addKeyword($data->tags);

        OpenGraph::setTitle($data->title)
            ->setDescription($data->description)
            ->setType('blog')
            ->addImage($data->imagePath . $data->image)
            ->setArticle([
                'published_time' => $data->created_at,
                'modified_time' => $data->updated_at,
                'section' => $data->category->name,
                'tag' => $data->tags
            ]);

        JsonLd::setTitle($data->title);
        JsonLd::setDescription($data->description);
        JsonLd::setType('Blog');
        JsonLd::addImage($data->imagePath . $data->image);
        $user = Auth::guard('appuser')->user();

        return view('frontend.blogDetail', compact('data', 'tags', 'user'));
    }

    public function profile()
    {
        $user = Auth::guard('appuser')->user();
        $setting = Setting::first(['app_name', 'logo']);

        SEOMeta::setTitle('User Profile')
            ->setDescription('This is user profile page')
            ->addKeyword([
                $setting->app_name,
                $user->name,
                $user->name . ' ' . $user->last_name,
            ]);

        OpenGraph::setTitle('User Profile')
            ->setDescription('This is user profile page')
            ->setType('profile')
            ->setUrl(url()->current())
            ->addImage($user->imagePath . $user->image)
            ->setProfile([
                'first_name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'bio' => $user->bio,
                'country' => $user->country,
            ]);

        JsonLd::setTitle('User Profile' ?? env('APP_NAME'))
            ->setDescription('This is user profile page')
            ->setType('Profile')
            ->addImage($user->imagePath . $user->image);

        SEOTools::setTitle('User Profile' ?? env('APP_NAME'));
        SEOTools::setDescription('This is user profile page');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('keywords', [
            $setting->app_name,
            $user->name,
            $user->name . ' ' . $user->last_name,
        ]);
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);
        SEOTools::jsonLd()->addImage($user->imagePath . $user->image);

        $user->saved_event = Event::whereIn('id', array_filter(explode(',', $user->favorite)))->where([['status', 1], ['is_deleted', 0]])->get();
        $user->saved_blog = Blog::whereIn('id', array_filter(explode(',', $user->favorite_blog)))->where('status', 1)->get();
        $user->following = User::whereIn('id', array_filter(explode(',', $user->following)))->get();
        foreach ($user->saved_event as $value) {
            $value->total_ticket = Ticket::where([['event_id', $value->id], ['is_deleted', 0], ['status', 1]])->sum('quantity');
            $value->sold_ticket = Order::where('event_id', $value->id)->sum('quantity');
            $value->available_ticket = $value->total_ticket - $value->sold_ticket;
        }
        return view('frontend.profile', compact('user'));
    }

    public function update_profile()
    {
        $user =  Auth::guard('appuser')->user();
        $phone = Country::get();
        $languages = Language::where('status', 1)->get();
        return view('frontend.user_profile', compact('user', 'languages', 'phone'));
    }

    public function update_user_profile(Request $request)
    {
        $data = $request->all();
        $user =  Auth::guard('appuser')->user();
        $user->update($data);
        $this->setLanguage($user);
        return redirect('/user/profile');
    }

    public function setLanguage($user)
    {
        $name = $user->language;

        if (!$name) {
            $name = 'عربي';
        }
        App::setLocale($name);
        session()->put('locale', $name);
        $direction = Language::where('name', $name)->first()->direction;
        session()->put('direction', $direction);
        return true;
    }

    public function addFavorite($id, $type)
    {
        $users = AppUser::find(Auth::guard('appuser')->user()->id);
        if ($type == "event") {
            $likes = array_filter(explode(',', $users->favorite));
            if (count(array_keys($likes, $id)) > 0) {
                if (($key = array_search($id, $likes)) !== false) {
                    unset($likes[$key]);
                }
                $msg = "Remove event from Favorite!";
            } else {
                array_push($likes, $id);
                $msg = "Add event in Favorite!";
            }
            $client = AppUser::find(Auth::guard('appuser')->user()->id);
            $client->favorite = implode(',', $likes);
        } else if ($type == "blog") {
            $likes = array_filter(explode(',', $users->favorite_blog));
            if (count(array_keys($likes, $id)) > 0) {
                if (($key = array_search($id, $likes)) !== false) {
                    unset($likes[$key]);
                }
                $msg = "Remove blog from Favorite!";
            } else {
                array_push($likes, $id);
                $msg = "Add blog in Favorite!";
            }
            $client = AppUser::find(Auth::guard('appuser')->user()->id);
            $client->favorite_blog = implode(',', $likes);
        }
        $client->update();
        return response()->json(['msg' => $msg, 'success' => true, 'type' => $type], 200);
    }

    public function addFollow($id)
    {
        $users = AppUser::find(Auth::guard('appuser')->user()->id);
        $likes = array_filter(explode(',', $users->following));
        if (count(array_keys($likes, $id)) > 0) {
            if (($key = array_search($id, $likes)) !== false) {
                unset($likes[$key]);
            }
            $msg = "Remove from following list!";
        } else {
            array_push($likes, $id);
            $msg = "Add in following!";
        }
        $client = AppUser::find(Auth::guard('appuser')->user()->id);
        $client->following = implode(',', $likes);
        $client->update();
        return response()->json(['msg' => $msg, 'success' => true], 200);
    }

    public function addBio(Request $request)
    {
        $success = AppUser::find(Auth::guard('appuser')->user()->id)->update(['bio' => $request->bio]);
        return response()->json(['data' => $request->bio, 'success' => $success], 200);
    }

    public function changePassword()
    {
        $setting = Setting::first(['app_name', 'logo']);

        SEOMeta::setTitle($setting->app_name . ' - Change Password' ?? env('APP_NAME'))
            ->setDescription('This is change password page')
            ->setCanonical(url()->current())
            ->addKeyword([
                'change password page',
                $setting->app_name,
                $setting->app_name . ' Change Password'
            ]);

        OpenGraph::setTitle($setting->app_name . ' - Change Password' ?? env('APP_NAME'))
            ->setDescription('This is change password page')
            ->setUrl(url()->current());

        JsonLdMulti::setTitle($setting->app_name . ' - Change Password' ?? env('APP_NAME'));
        JsonLdMulti::setDescription('This is change password page');
        JsonLdMulti::addImage($setting->imagePath . $setting->logo);

        SEOTools::setTitle($setting->app_name . ' - Change Password' ?? env('APP_NAME'));
        SEOTools::setDescription('This is change password page');
        SEOTools::opengraph()->addProperty('keywords', [
            'change password page',
            $setting->app_name,
            $setting->app_name . ' Change Password'
        ]);
        SEOTools::opengraph()->addProperty('image', $setting->imagePath . $setting->logo);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);

        return view('frontend.auth.changePassword');
    }

    public function changeUserPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'bail|required',
            'password' => 'bail|required|min:6',
            'password_confirmation' => 'bail|required|same:password|min:6'
        ]);
        if (Hash::check($request->old_password, Auth::guard('appuser')->user()->password)) {
            AppUser::find(Auth::guard('appuser')->user()->id)->update(['password' => Hash::make($request->password)]);
            return redirect('user/profile')->withStatus(__('Password is changed successfully.'));
        } else {
            return Redirect::back()->with('error_msg', 'Current Password is wrong!');
        }
    }

    public function uploadProfileImage(Request $request)
    {
        $appuser = AppUser::find(Auth::guard('appuser')->user());
        if ($request->hasFile('image') != 'defaultuser.png') {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:3048',
            ]);
            (new AppHelper)->deleteFile($appuser->image);
            $imageName = (new AppHelper)->saveImage($request);
            AppUser::find(Auth::guard('appuser')->user()->id)->update(['image' => $imageName]);
        } else {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:3048',
            ]);
            $imageName = (new AppHelper)->saveImage($request);
            AppUser::find(Auth::guard('appuser')->user()->id)->update(['image' => $imageName]);
        }
        return response()->json(['data' => $imageName, 'success' => true], 200);
    }

    public function contact()
    {
        $setting = Setting::first(['app_name', 'logo']);
        $data = ContactUs::find(1);
        SEOMeta::setTitle($setting->app_name . ' - Contact Us' ?? env('APP_NAME'))
            ->setDescription('This is contact us page')
            ->setCanonical(url()->current())
            ->addKeyword([
                $setting->app_name,
                $setting->app_name . ' Contact Us',
                'contact us page',
            ]);

        OpenGraph::setTitle($setting->app_name . ' - Contact Us' ?? env('APP_NAME'))
            ->setDescription('This is contact us page')
            ->setUrl(url()->current());

        JsonLdMulti::setTitle($setting->app_name . ' - Contact Us' ?? env('APP_NAME'));
        JsonLdMulti::setDescription('This is contact us page');
        JsonLdMulti::addImage($setting->imagePath . $setting->logo);

        SEOTools::setTitle($setting->app_name . ' - Contact Us' ?? env('APP_NAME'));
        SEOTools::setDescription('This is contact us page');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('keywords', [
            $setting->app_name,
            $setting->app_name . ' Contact Us',
            'contact us page',
        ]);
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);
        if ($data) {
            return view('frontend.contact', compact('data'));
        }
        return view('frontend.contact');
    }

    public function userTickets()
    {

        $user = Auth::guard('appuser')->user();
        $setting = Setting::first(['app_name', 'logo', 'currency']);
        SEOMeta::setTitle('User Tickets')
            ->setDescription('This is user tickets page')
            ->addKeyword([
                $setting->app_name,
                $user->name,
                $user->name . ' ' . $user->last_name,
                $user->name . ' ' . $user->last_name . ' tickets',
            ]);

        OpenGraph::setTitle('User Tickets')
            ->setDescription('This is user tickets page')
            ->setUrl(url()->current())
            ->addImage($user->imagePath . $user->image);


        JsonLd::setTitle('User Tickets' ?? env('APP_NAME'))
            ->setDescription('This is user tickets page')
            ->addImage($user->imagePath . $user->image);

        SEOTools::setTitle('User Tickets' ?? env('APP_NAME'));
        SEOTools::setDescription('This is user tickets page');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('keywords', [
            $setting->app_name,
            $user->name,
            $user->name . ' ' . $user->last_name,
            $user->name . ' ' . $user->last_name . ' tickets',
        ]);
        SEOTools::jsonLd()->addImage($setting->imagePath . $setting->logo);
        SEOTools::jsonLd()->addImage($user->imagePath . $user->image);
        (new AppHelper)->eventStatusChange();
        $ordertax = array();
        $tax = array();

        $ticket['upcoming'] = Order::with(['event:id,name,image,start_time,type,end_time,address', 'ticket:id,ticket_number,start_time,name,price,type', 'organization:id,first_name,last_name,image'])
            ->where([['customer_id', Auth::guard('appuser')->user()->id], ['order_status', 'Pending']])
            ->orWhere([['customer_id', Auth::guard('appuser')->user()->id], ['order_status', 'Complete']])
            ->orderBy('id', 'DESC')->paginate(10);
        $event = [];

        if (count($ticket['upcoming']) > 0) {
            foreach ($ticket['upcoming'] as $events) {
                if ($events->event->start_time <= Carbon::now() && $events->event->end_time >= Carbon::now()) {
                    $event[] = $events;
                }
            }
            $ticket['upcoming']->event = $event;
        }

        $ordertax = array();
        $tax = array();
        foreach ($ticket['upcoming'] as $item) {
            $ordertaxs = OrderTax::where('order_id', $item->id)->get();
            $ordertax = $ordertaxs;
        }
        foreach ($ordertax as $item) {
            $taxs = Tax::find($item->tax_id)->get();
            $tax = $taxs;
        }
        $ticket['upcoming']->maintax = $tax;


        $ticket['past'] = Order::with(['event:id,name,image,start_time,type,end_time,address', 'ticket:id,ticket_number,name,type,price', 'organization:id,first_name,last_name,image'])
            ->where([['customer_id', Auth::guard('appuser')->user()->id], ['order_status', 'Cancel']])
            ->orderBy('id', 'DESC')->paginate(10);
        if (count($ticket['past']) > 0) {
            foreach ($ticket['past'] as $events) {
                if ($events->event->end_time <= Carbon::now()) {
                    $event[] = $events;
                }
            }
            $ticket['past']->event = $event;
        }
        foreach ($ticket['past'] as $item) {
            $ordertaxs = OrderTax::where('order_id', $item->id)->get();
            $ordertax = $ordertaxs;
        }

        foreach ($ordertax as $item) {
            $taxs = Tax::find($item->tax_id)->get();
            $tax = $taxs;
        }
        $ticket['past']->maintax = $tax;

        $likedEvents = Event::whereIn('id', array_filter(explode(',', $user->favorite)))->where([['status', 1], ['is_deleted', 0]])->orderBy('id', 'DESC')->get();
        foreach ($likedEvents as $value) {
            $value->description =  str_replace("&nbsp;", " ", strip_tags($value->description));
            $value->time = $value->start_time->format('d F Y h:i a');
        }
        $likedBlogs = Blog::whereIn('id', array_filter(explode(',', $user->favorite_blog)))->where([['status', 1]])->orderBy('id', 'DESC')->get();
        $userFollowing = User::whereIn('id', array_filter(explode(',', $user->following)))->where([['status', 1]])->orderBy('id', 'DESC')->get();
        $wallet = PaymentSetting::first()->wallet;
        return view('frontend.userTickets', compact('likedEvents', 'ticket', 'likedBlogs', 'userFollowing', 'wallet'));
    }
    public function userOrderTicket($id)
    {
        $order = Order::with(['event', 'ticket', 'organization'])->find($id);
        $taxes_id = OrderTax::where('order_id', $order->id)->get();
        // $coupon = Coupon::find($order->coupon_id);
        $taxes = [];
        foreach ($taxes_id as $key => $value) {
            $temp_tax[] = Tax::find($value->tax_id);
            $taxes = $temp_tax;
        }
        $orderchild = OrderChild::where('order_id', $order->id)->get();
        $review = Review::where('order_id', $order->id)->first();
        return view('frontend.userOrderTicket', compact('order', 'taxes', 'review', 'orderchild'));
    }
    public function  getOrder($id)
    {
        $data = Order::with(['event:id,name,image,start_time,type,end_time,address', 'ticket:id,ticket_number,name,price,type', 'organization:id,first_name,last_name,image'])->find($id);
        $data->review = Review::where('order_id', $id)->first();
        $data->time = $data->created_at->format('D') . ', ' . $data->created_at->format('d M Y') . ' at ' . $data->created_at->format('h:i a');
        $data->start_time = $data->event->start_time->format('d M Y') . ', ' . $data->event->start_time->format('h:i a');
        $data->end_time = $data->event->end_time->format('d M Y') . ', ' . $data->event->end_time->format('h:i a');
        $taxs = array();
        $ordertax = OrderTax::where('order_id', $id)->get();
        foreach ($ordertax as $item) {
            $taxs = Tax::find($item->tax_id)->get();
            $taxs = $taxs;
        }
        $data->maintax = $taxs;

        return response()->json(['data' => $data, 'success' => true], 200);
    }

    public function addReview(Request $request)
    {
        $data = $request->all();
        $data['organization_id'] = Order::find($request->order_id)->organization_id;
        $data['event_id'] = Order::find($request->order_id)->event_id;
        $data['user_id'] = Auth::guard('appuser')->user()->id;
        $data['status'] = 0;
        Review::create($data);
        return redirect()->back();
    }

    public function sentMessageToAdmin(Request $request)
    {
        $data = $request->all();
        try {
            Mail::send('emails.message', ['data' => $data], function ($message) use ($data) {
                $setting = Setting::first();
                $message->from($setting->sender_email);
                $message->to(User::find(1)->email);
                $message->subject($data['subject']);
            });
        } catch (Throwable $th) {
            Log::info($th->getMessage());
        }
        return redirect('/contact');
    }

    public function privacypolicy()
    {
        $policy = Setting::find(1)->privacy_policy_organizer;
        return view('frontend.privacy-policy', compact('policy'));
    }

    public function appuserPrivacyPolicyShow(Request $request)
    {
        $policy = Setting::find(1)->appuser_privacy_policy;
        return view('frontend.privacy-policy', compact('policy'));
    }
    public function searchEvent(Request $request)
    {
        $search = $request->search ?? '';
        if ($search == '') {
            return redirect()->back();
        }
        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);
        $events  = Event::with(['category:id,name'])
            ->where([['address', 'LIKE', "%$search%"], ['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d')]])
            ->orWhere([['name', 'LIKE', "%$search%"], ['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d')]])
            ->orWhere([['description', 'LIKE', "%$search%"], ['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d')]]);
        $chip = array();
        if ($request->has('type') && $request->type != null) {
            $chip['type'] = $request->type;
            $events = $events->where('type', $request->type);
        }
        if ($request->has('category') && $request->category != null) {
            $chip['category'] = Category::find($request->category)->name;
            $events = $events->where('category_id', $request->category);
        }
        if ($request->has('duration') && $request->duration != null) {
            $chip['date'] = $request->duration;
            if ($request->duration == 'Today') {
                $temp = Carbon::now($timezone)->format('Y-m-d');
                $events = $events->whereBetween('start_time', [$temp . ' 00:00:00', $temp . ' 23:59:59']);
            } else if ($request->duration == 'Tomorrow') {
                $temp = Carbon::tomorrow($timezone)->format('Y-m-d');
                $events = $events->whereBetween('start_time', [$temp . ' 00:00:00', $temp . ' 23:59:59']);
            } else if ($request->duration == 'ThisWeek') {
                $now = Carbon::now($timezone);
                $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i:s');
                $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i:s');
                $events = $events->whereBetween('start_time', [$weekStartDate, $weekEndDate]);
            } else if ($request->duration == 'date') {
                if (isset($request->date)) {
                    $temp = Carbon::parse($request->date)->format('Y-m-d H:i:s');
                    $events = $events->whereBetween('start_time', [$request->date . ' 00:00:00', $request->date . ' 23:59:59']);
                }
            }
        }
        $events = $events->orderBy('start_time', 'ASC')->get();
        foreach ($events as $value) {
            $value->total_ticket = Ticket::where([['event_id', $value->id], ['is_deleted', 0], ['status', 1]])->sum('quantity');
            $value->sold_ticket = Order::where('event_id', $value->id)->sum('quantity');
            $value->available_ticket = $value->total_ticket - $value->sold_ticket;
        }
        $user = Auth::guard('appuser')->user();
        $offlinecount = 0;
        $onlinecount = 0;
        foreach ($events as $key => $value) {
            if ($value->type == 'online') {
                $onlinecount += 1;
            }
            if ($value->type == 'offline') {
                $offlinecount += 1;
            }
        }
        return view('frontend.events', compact('user', 'events', 'chip', 'onlinecount', 'offlinecount'));
    }
    public function eventsByTag($tag)
    {
        $events = Event::where([['tags', 'LIKE', "%$tag%"], ['is_deleted', 0]])->get();
        $onlinecount = 0;
        $offlinecount = 0;
        foreach ($events as $key => $value) {
            if ($value->type == 'online') {
                $onlinecount += 1;
            } else {
                $offlinecount += 1;
            }
        }
        if (Auth::guard('appuser')->check()) {
            $user = Auth::guard('appuser')->user();
            return view('frontend.events', compact('events', 'onlinecount', 'offlinecount', 'user'));
        }
        return view('frontend.events', compact('events', 'onlinecount', 'offlinecount'));
    }
    public function blogByTag($tag)
    {
        $blogs = Blog::where('tags', 'LIKE', "%$tag%")->where('status', 1)->orderBy('id', 'DESC')->get();
        $category = Category::where('status', 1)->orderBy('id', 'DESC')->get();
        if (Auth::guard('appuser')->user()) {
            $user = Auth::guard('appuser')->user();
            return view('frontend.blog', compact('blogs', 'category', 'user'));
        }
        return view('frontend.blog', compact('blogs', 'category'));
    }
    public function Faqs()
    {
        $data = Faq::where('status', 1)->get();
        return view('frontend.show_faq', compact('data'));
    }
    public function otpView($id)
    {
        $user = AppUser::find($id);
        return view('frontend.auth.otp', compact('user'));
    }
    public function otpViewOrganizer($id)
    {
        $user = User::find($id);
        return view('frontend.auth.otporganizer', compact('user'));
    }
    public function otpVerify(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);
        $user = AppUser::find($request->id);
        if ($user->otp == $request->otp) {
            $user->otp = null;
            $user->is_verify = 1;
            $user->update();
            Auth::guard('appuser')->login($user);
            return redirect('/');
        } else {
            return redirect()->back()->with('error', 'Wrong OTP. Please try again.');
        }
    }
    public function otpVerifyOrganizer(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);
        $user = User::find($request->id);
        if ($user->otp == $request->otp) {
            $user->otp = null;
            $user->is_verify = 1;
            $user->update();
            //Auth::login($user);
            return redirect('user/login');
        } else {
            return redirect()->back()->with('error', 'Wrong OTP. Please try again.');
        }
    }
    public function checkoutSession(Request $request)
    {
        $request->session()->put('request', $request->all());
        $key = PaymentSetting::first()->stripeSecretKey;
        Stripe::setApiKey($key);
        $supportedCurrency = [
            "EUR",   # Euro
            "GBP",   # British Pound Sterling
            "CAD",   # Canadian Dollar
            "AUD",   # Australian Dollar
            "JPY",   # Japanese Yen
            "CHF",   # Swiss Franc
            "NZD",   # New Zealand Dollar
            "HKD",   # Hong Kong Dollar
            "SGD",   # Singapore Dollar
            "SEK",   # Swedish Krona
            "DKK",   # Danish Krone
            "PLN",   # Polish Złoty
            "NOK",   # Norwegian Krone
            "CZK",   # Czech Koruna
            "HUF",   # Hungarian Forint
            "ILS",   # Israeli New Shekel
            "MXN",   # Mexican Peso
            "BRL",   # Brazilian Real
            "MYR",   # Malaysian Ringgit
            "PHP",   # Philippine Peso
            "TWD",   # New Taiwan Dollar
            "THB",   # Thai Baht
            "TRY",   # Turkish Lira
            "RUB",   # Russian Ruble
            "INR",   # Indian Rupee
            "ZAR",   # South African Rand
            "AED",   # United Arab Emirates Dirham
            "SAR",   # Saudi Riyal
            "KRW",   # South Korean Won
            "CNY"    # Chinese Yuan
        ];
        $amount = $request->payment;
        if (!in_array($request->currency, $supportedCurrency)) {
            $amount = $amount * 100;
        }
        $currencyCode = Setting::first()->currency;
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $currencyCode,
                        'product_data' => [
                            'name' => "Payment"
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('stripe.success'),
            'cancel_url' => route('stripe.cancel'),
        ]);
        $request->session()->put('payment_token', $session->id);
        return response()->json(['id' => $session->id, 'status' => 200]);
    }
    public function stripeSuccess()
    {
        $request = Session::get('request');

        $ticket = Ticket::findOrFail($request['ticket_id']);

        $event = Event::find($ticket->event_id);

        $org = User::find($event->user_id);
        $user = AppUser::find(Auth::guard('appuser')->user()->id);
        $data['order_id'] = '#' . rand(9999, 100000);
        $data['event_id'] = $event->id;
        $data['customer_id'] = $user->id;
        $data['organization_id'] = $org->id;
        $data['order_status'] = 'Pending';
        $data['ticket_id'] = $request['ticket_id'];
        $data['quantity'] = $request['quantity'];
        $data['payment_type'] = 'STRIPE';
        $data['payment_token'] = Session::get('payment_token');
        $data['payment'] = $request['payment'];
        $data['tax'] = $request['tax'];
        $data['coupon_id'] = $request['coupon_id'] ?? null;
        $data['payment_status'] = 1;
        $data['order_status'] = 'Complete';
        $com = Setting::find(1, ['org_commission_type', 'org_commission']);
        $p =   $request['payment'] - $request['tax'];
        if ($request['payment_type'] == "FREE") {
            $data['org_commission']  = 0;
        } else {
            if ($com->org_commission_type == "percentage") {
                $data['org_commission'] =  $p * $com->org_commission / 100;
            } else if ($com->org_commission_type == "amount") {
                $data['org_commission']  = $com->org_commission;
            }
        }

        if (isset($request['coupon_code'])) {
            $coupon = Coupon::find($request->coupon_code);
            $count = $coupon->use_count + 1;
            $coupon->update(['use_count' => $count]);
            CouponUsageHistory::create([
                'coupon_id' => $request->coupon_code,
                'appuser_id' => $user->id
            ]);
            $data['coupon_discount'] = $coupon->discount;
            $data['coupon_id'] = $coupon->id;
        }

        $data['book_seats'] = isset($request['selectedSeatsId']) ? $request['selectedSeatsId'] : null;
        $data['seat_details'] = isset($request['selectedSeats']) ? $request['selectedSeats'] : null;
        $order = Order::create($data);
        $module = Module::where('module', 'Seatmap')->first();
        if ($module->is_enable == 1 && $module->is_install == 1) {
            $seats = explode(',', $data['book_seats']);
            foreach ($seats as $key => $value) {
                $seat = Seats::find($value);
                if ($seat) {
                    $seat->update(['type' => 'occupied']);
                }
            }
        }

        for ($i = 1; $i <= $request['quantity']; $i++) {
            $child['ticket_number'] = uniqid();
            $child['ticket_id'] = $request['ticket_id'];
            $child['order_id'] = $order->id;
            $child['customer_id'] = Auth::guard('appuser')->user()->id;
            $child['checkin'] = $ticket->maximum_checkins ?? null;
            $child['paid'] =  1;
            OrderChild::create($child);
        }
        if (isset($request['tax_data'])) {
            foreach (json_decode($data['tax_data']) as $value) {
                $tax['order_id'] = $order->id;
                $tax['tax_id'] = $value->id;
                $tax['price'] = $value->price;
                OrderTax::create($tax);
            }
        }

        $user = AppUser::find($order->customer_id);
        $setting = Setting::find(1);

        // for user notification
        $message = NotificationTemplate::where('title', 'Book Ticket')->first()->message_content;
        $detail['user_name'] = $user->name . ' ' . $user->last_name;
        $detail['quantity'] = $request['quantity'];
        $detail['event_name'] = Event::find($order->event_id)->name;
        $detail['date'] = Event::find($order->event_id)->start_time->format('d F Y h:i a');
        $detail['app_name'] = $setting->app_name;
        $noti_data = ["{{user_name}}", "{{quantity}}", "{{event_name}}", "{{date}}", "{{app_name}}"];
        $message1 = str_replace($noti_data, $detail, $message);
        $notification = array();
        $notification['organizer_id'] = null;
        $notification['user_id'] = $user->id;
        $notification['order_id'] = $order->id;
        $notification['title'] = 'Ticket Booked';
        $notification['message'] = $message1;
        Notification::create($notification);
        if ($setting->push_notification == 1) {
            if ($user->device_token != null) {
                (new AppHelper)->sendOneSignal('user', $user->device_token, $message1);
            }
        }
        // for user mail
        $ticket_book = NotificationTemplate::where('title', 'Book Ticket')->first();
        $details['user_name'] = $user->name . ' ' . $user->last_name;
        $details['quantity'] = $request['quantity'];
        $details['event_name'] = Event::find($order->event_id)->name;
        $details['date'] = Event::find($order->event_id)->start_time->format('d F Y h:i a');
        $details['app_name'] = $setting->app_name;
        if ($setting->mail_notification == 1) {

            try {
                $qrcode = $order->order_id;
                Mail::to($user->email)->send(new TicketBook($ticket_book->mail_content, $details, $ticket_book->subject, $qrcode));
            } catch (\Throwable $th) {
                Log::info($th->getMessage());
            }
            $this->sendMail($order->id);
        }

        // for Organizer notification
        $org =  User::find($order->organization_id);
        $or_message = NotificationTemplate::where('title', 'Organizer Book Ticket')->first()->message_content;
        $or_detail['organizer_name'] = $org->first_name . ' ' . $org->last_name;
        $or_detail['user_name'] = $user->name . ' ' . $user->last_name;
        $or_detail['quantity'] = $request['quantity'];
        $or_detail['event_name'] = Event::find($order->event_id)->name;
        $or_detail['date'] = Event::find($order->event_id)->start_time->format('d F Y h:i a');
        $or_detail['app_name'] = $setting->app_name;
        $or_noti_data = ["{{organizer_name}}", "{{user_name}}", "{{quantity}}", "{{event_name}}", "{{date}}", "{{app_name}}"];
        $or_message1 = str_replace($or_noti_data, $or_detail, $or_message);
        $or_notification = array();
        $or_notification['organizer_id'] =  $org->id;
        $or_notification['user_id'] = null;
        $or_notification['order_id'] = $order->id;
        $or_notification['title'] = 'New Ticket Booked';
        $or_notification['message'] = $or_message1;
        Notification::create($or_notification);
        if ($setting->push_notification == 1) {
            if ($org->device_token != null) {
                (new AppHelper)->sendOneSignal('organizer', $org->device_token, $or_message1);
            }
        }
        // for Organizer mail
        $new_ticket = NotificationTemplate::where('title', 'Organizer Book Ticket')->first();
        $details1['organizer_name'] = $org->first_name . ' ' . $org->last_name;
        $details1['user_name'] = $user->name . ' ' . $user->last_name;
        $details1['quantity'] = $request['quantity'];
        $details1['event_name'] = Event::find($order->event_id)->name;
        $details1['date'] = Event::find($order->event_id)->start_time->format('d F Y h:i a');
        $details1['app_name'] = $setting->app_name;
        if ($setting->mail_notification == 1) {
            try {
                $setting = Setting::first();
                Mail::to($org->email)->send(new TicketBookOrg($new_ticket->mail_content, $details1, $new_ticket->subject));
            } catch (\Throwable $th) {
                Log::info($th->getMessage());
            }
        }
        Session::forget('request');
        return redirect()->route('myTickets');
    }
    public function striepCancel()
    {
        return redirect()->back();
    }
    public function transcationCheckEdfapay()
    {
        // Define the variables
        $trans_id = Session::get('edfapay_trans');
        $PASSWORD = "a92f7b7f0d869d3e676c5facda5262ae";
        $card_number = Session::get('edfapay_card');
        $email = Session::get('edfapay_email');

        // Function to reverse a string
        function reverseString($str)
        {
            return strrev($str);
        }

        // Reverse the email
        $reversedEmail = reverseString($email);

        // Reverse the card number (first 6 and last 4 digits combined)
        $reversedCard = reverseString(substr($card_number, 0, 6) . substr($card_number, -4));

        // Concatenate and uppercase the final string
        $result = strtoupper($reversedEmail . $PASSWORD . $trans_id . $reversedCard);

        // Generate the MD5 hash
        $finalResult = md5($result);
        // Print the resul
        return $finalResult;
    }
    public function edafpayHashMaker($order, $request)
    {
        $password = "a92f7b7f0d869d3e676c5facda5262ae";
        //$password = "3a282e78e09e33a2063919b7b42d290c";

        $cardNumber = $request->card_input;
        $email = isset($order->user->email) ? $order->user->email : "ticketbyksa@gmail.com";
        Session::put('edfapay_card', $cardNumber);
        Session::put('edfapay_email', $email);
        function reverseString($str)
        {
            return strrev($str);
        }
        $final = strtoupper(reverseString($email) . $password . reverseString(substr($cardNumber, 0, 6) . substr($cardNumber, -4)));
        $sha1Hash = md5($final);
        Session::put('hash', $sha1Hash);
        return $sha1Hash;

        // $merchantPass = "3a282e78e09e33a2063919b7b42d290c";

        // $description = "Description" ; 

        // $currency = "SAR"; 


        // // Concatenate the values
        // //$stringToHash =  $order['order_id'] . $order['payment'] . $currency . $description . $merchantPass;
        // $stringToHash = "#35332" . "1". "SAR" . "Description" . "3a282e78e09e33a2063919b7b42d290c";

        // // Convert the concatenated string to uppercase
        // $uppercaseString = strtoupper($stringToHash);

        // // Generate the MD5 hash
        // $md5Hash = md5($uppercaseString);

        // // Generate the SHA1 hash of the MD5 hash
        // $sha1Hash = sha1($md5Hash);


    }


    public function edafpayCreate($order, $request)
    {

        $hash = $this->edafpayHashMaker($order, $request);
        $email = isset($order->user->email) ? $order->user->email : "ticketbyksa@gmail.com";
        $f_name = isset($order->user->name) ? $order->user->name : "ticketby";
        $l_name = isset($order->user->last_name) ? $order->user->last_name : "ticketby";
        $phone = isset($order->user->phone) ? $order->user->phone : "+966559344333";

        $curl = curl_init();
        $request->payment = number_format($request->payment, 2, '.', '');
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.edfapay.com/payment/post',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('client_key' => 'd857c073-c58c-49dc-906b-24fa667dc306', 'order_id' => $order->order_id, 'hash' => $hash, 'order_description' => 'Description', 'order_currency' => 'SAR', 'order_amount' => $request->payment, 'card_number' => $request->card_input, 'card_exp_month' => $request->month, 'card_exp_year' => '20' . $request->year, 'card_cvv2' => $request->cvv, 'payer_phone' => $phone, 'payer_country' => 'SA', 'payer_address' => $email, 'action' => 'SALE', 'payer_zip' => '623524', 'payer_ip' => '176.44.76.100', 'payer_first_name' => $email, 'payer_city' => 'Riyadh', 'auth' => 'N', 'payer_last_name' => $l_name, 'payer_email' => $email, 'term_url_3ds' => 'https://ticketby.pixicard.com/thankyou', 'recurring_init' => 'N', 'req_token' => 'N', 'merchant_origin' => 'http://pay.edfapay.com', 'card_scheme' => 'VISA'),
        ));
        $response = curl_exec($curl);
        $responseData = json_decode($response, true);
        //'client_key' => 'a918ee4d-d87a-4298-8a80-04ee30d8bebc' stage
        //'client_key' => 'd857c073-c58c-49dc-906b-24fa667dc306' live
        /*
  CURLOPT_POSTFIELDS => array('client_key' => 'a918ee4d-d87a-4298-8a80-04ee30d8bebc','order_id' => $order->order_id,'hash' =>$hash,'order_description' => 'Description','order_currency' => 'SAR','order_amount' => $request->payment,'card_number' => $request->card_input,'card_exp_month' =>$request->month,'card_exp_year' => '20'.$request->year,'card_cvv2' => $request->cvv,'payer_phone' => '+966559344333','payer_country' => 'SA','payer_address' => 'Abdulrhman.a@edfapay.com','action' => 'SALE','payer_zip' => '623524','payer_ip' => '176.44.76.100','payer_first_name' => 'Abdulrhman','payer_city' => 'Riyadh','auth' => 'N','payer_last_name' => 'alnafisah','payer_email' => 'Abdulrhman.a@edfapay.com','term_url_3ds' => 'https://ticketby.co/thankyou','recurring_init' => 'N','req_token' => 'N','merchant_origin' => 'http://pay.edfapay.com','card_scheme' => 'VISA'),
));*/

        if ($responseData['trans_id']) {
            Session::put("edfapay_trans", $responseData['trans_id']);
        }


        $data = array();

        if (isset($responseData['redirect_url'])) {
            $data['url'] = $responseData['redirect_url'];
            $data['params'] = $responseData['redirect_params'];
            $data['status'] = 200;
            return $data;
        }
        if (isset($responseData['error_message'])) {
            $data['error_message'] = $responseData['error_message'] . " - " . $responseData['errors'][0]['error_message'];
            $data['status'] = 400;
            return $data;
        }
        if (!isset($responseData['error_message']) && !isset($responseData['redirect_url'])) {
            $data['error_message'] = "Internal Server Error";
            $data['status'] = 500;
            return $data;
        }

        return redirect($responseData['redirect_url']);
    }

    public function thankyou(Request $request)
    {

        $orderId = Session::get('order_id');
        $order = Order::findOrFail($orderId);

        if ($order->payment_type == "EDAFPAY") {
            $hash = $this->transcationCheckEdfapay();

            $url = 'https://api.edfapay.com/payment/post';
            $action = 'GET_TRANS_DETAILS';
            $client_key = 'd857c073-c58c-49dc-906b-24fa667dc306';
            $trans_id =  Session::get('edfapay_trans');

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.edfapay.com/payment/post',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'action' => $action,
                    'client_key' => $client_key,
                    'trans_id' => $trans_id,
                    'hash' => $hash
                )
            ));
            $response = curl_exec($curl);
            $responseData = json_decode($response, true);

            if ($responseData['status'] == "DECLINED") {
                return redirect('/failed');
            }
        }
        $this->sendOrderMail($orderId);
        return view('frontend/thankyou');
    }

    public function failed()
    {
        $orderId = Session::get('order_id');
        OrderChild::where('order_id', $orderId)->forceDelete();
        Order::where('id', $orderId)->forceDelete();
        return view('frontend/failed');
    }

    public function sendOTPMail($dataMail)
    {

        //$data = array('name'=>$dataMail['name'] , 'email'=>$dataMail['email'],"otp"=>$dataMail['otp']);
        $data = array('name' => "TicketBy", 'email' => 'hivasavada@gmail.com', "otp" => $dataMail['otp']);
        Mail::send(['html' => 'frontend.email.otp'], $data, function ($message) use ($data) {
            $message->to("hivasavada£gmail.com")->subject('OTP Verification');
            $message->from('ticketbyksa@gmail.com', 'TicketBy');
        });
    }

    // apple pay 
    public function getApplePaySession(Request $request)
    {

        \Log::error('apple pay');
        \Log::error($request->validationURL);
        $validationURL =  $request->validationURL;

        $displayName = 'merchant.com.wasltec.Applepaywasltec';
        $displayName = 'TicketBy';
        $domainName = 'ticketby.co';

        // Load your merchant identity certificate

        // $certificatePath = public_path('applepay/certificate.pem');
        // $keyPath = storage_path('applepay/privatekey.pem');
        // $keyPassword = 'TicketBy@2024';

        // Sign the payload using your merchant identity certificate
        // $signedPayload = shell_exec("openssl smime -sign -signer $certificatePath -inkey $keyPath -outform DER -nodetach -passin pass:$keyPassword");


        $data = [
            'merchantIdentifier' => $displayName,
            'displayName' => $displayName,
            'domainName' => $domainName,
            'initiative' => 'web',
            'initiativeContext' => $domainName,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $validationURL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'

        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        \Log::error($response);
        if ($response === false) {
            \Log::error("error");
            return response()->json(['error' => 'Merchant validation failed.'], 500);
        }
        return response($response);

        $ch = curl_init();
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $validationURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSLCERT, $certificatePath);
        curl_setopt($ch, CURLOPT_SSLKEY, $keyPath);
        curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $keyPassword);

        // Execute the request
        $response = curl_exec($ch);
        \Log::error($response);
        if ($response === false) {
            \Log::error("error");
            return response()->json(['error' => 'Merchant validation failed.'], 500);
        }

        // Close the cURL handle
        curl_close($ch);
        \Log::error($response);
        // Send the response back to the client
        return response($response);

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $validationURL);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     'Content-Type: application/json'

        // ]);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // $response = curl_exec($ch);
        // dd($response);
        // curl_close($ch);

        // return response()->json(json_decode($response, true));
    }

    //google login 

    public function redirectToGoogle()
    {
        $query = http_build_query([
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => 'email profile openid',
            'access_type' => 'offline',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/auth?' . $query);
    }
    public function handleGoogleCallback(Request $request)
    {
        dd("jj");
        try {
            $http = new ClientGuzzel;

            $response = $http->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'client_id' => env('GOOGLE_CLIENT_ID'),
                    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                    'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
                    'code' => request('code'),
                    'grant_type' => 'authorization_code',
                ],
            ]);

            $tokenData = json_decode((string) $response->getBody(), true);

            $response = $http->get('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $tokenData['access_token']);

            $userData = json_decode((string) $response->getBody(), true);

            $user = User::where('email', $userData['email'])->first();
            dd($user,  $userData);
            if ($user) {
                Auth::login($user);
            } else {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make(uniqid()),
                ]);

                Auth::login($user);
            }

            return redirect()->intended('/');
        } catch (Exception $e) {
            return redirect('auth/google');
        }
    }

    public function emailtesting()
    {
        $data = array();
        Mail::send(['html' => 'frontend.email.check'], $data, function ($message) use ($data) {
            $message->to("kabirphp099@gmail.com")->subject('OTP Verification');
            $message->from('support@ticketby.co', 'ticketby');
        });
    }

    public function ordarMailSender()
    {
        $order_id = "445";
        $this->sendOrderMail($order_id);
    }


    public function createOrderTickets()
    {

        $order_data['order_id'] = '#' . rand(9999, 100000);
        $order_data['event_id'] = 97;
        $order_data['customer_id'] = 61;
        $order_data['organization_id'] = 55;
        $order_data['order_status'] = 'Pending';
        $order_data['order_status'] = 'Pending';
        $order_data['ticket_id'] = 90; // 90 f 91 m
        $order_data['quantity'] = 40; // 90 f 91 m
        $order_data['tax'] = 0;
        $order_data['payment'] = 0;
        $order_data['payment_type'] = "manaul";
        $order_data['payment_status'] = 1;
        $order_data['order_status'] = "Complete";

        $order = Order::create($order_data);

        for ($i = 0; $i < 40; $i++) {
            $child['ticket_number'] = uniqid();
            $child['ticket_id'] = 90;
            $child['order_id'] = $order->id;
            $child['customer_id'] = 61;
            $child['checkin'] =  null;
            $child['paid'] = 1;
            OrderChild::create($child);

            QrCode::format('png')
                ->size(300)
                ->generate($child['ticket_number'], public_path('qrcodes-mannual/qr-' . $child['ticket_number'] . '.png'));
        }
    }
    //google login 

    public function deletePending()
    {
        Log::info("delete cron");
        $orders = Order::where('created_at', "<", Carbon::now()->subMinutes(40))->where('order_status', "Pending")->where('payment_type', "EDAFPAY")->pluck('id')->toArray();
        $order_tax = OrderTax::whereIn('order_id', $orders)->forceDelete();
        $order_child = OrderChild::whereIn('order_id', $orders)->forceDelete();
        $order = Order::whereIn('id', $orders)->forceDelete();
    }
}
