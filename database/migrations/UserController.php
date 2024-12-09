<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Mail\TicketBook;
use App\Mail\TicketBookOrg;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Log;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\OrderTax;
use App\Models\AppUser;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\CouponUsageHistory;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Tax;
use App\Models\OrganizerPaymentKeys;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use App\Models\Language;
use App\Models\Module;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\OrderChild;
use App\Models\Settlement;
use App\Models\OrganizerDetails;
use Illuminate\Support\Facades\Rave;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;
use Exception;
use Facade\FlareClient\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Throwable;

class UserController extends Controller
{
    public function __construct()
    {
        (new AppHelper)->eventStatusChange();
    }
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $users = User::with(['roles:id,name'])->where('is_delete',0)->get();
        $debugMode = env('APP_DEBUG');
        return view('admin.user.index', compact('users', 'debugMode'));
    }

    public function create()
    {

        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::all();
        $orgs = User::role('Organizer')->orderBy('id', 'DESC')->get();
        return view('admin.user.create', compact('roles', 'orgs'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'first_name' => 'bail|required',
            'last_name' => 'bail|required',
            'email' => 'bail|required|email|unique:users',
            'phone' => 'bail|required',
            'password' => 'bail|required|min:6',
            "roles"    => "bail|required|array|min:1",
            'roles.*' => 'bail|required|string|distinct|min:1',
        ]);
        $data = $request->all();
        $data['password'] =  Hash::make($request->password);
        $data['org_id'] = $request->organization;
        $data['language'] = Setting::first()->language;
        $user = User::create($data);

        if( isset($request->arabic_name)  &&  !is_null($request->arabic_name) )
        {
            $organizer_details = new OrganizerDetails();
            $organizer_details->user_id = $user->id;
            $organizer_details->arabic_name = $request->arabic_name;
            $organizer_details->facebook = $request->facebook;
            $organizer_details->twitter = $request->twitter;
            $organizer_details->instagram = $request->instagram;
            $organizer_details->whatsapp = $request->whatsapp;
            $organizer_details->terms_english = $request->terms_english;
            $organizer_details->terms_arabic = $request->terms_arabic;
            $organizer_details->short_description_english = $request->short_description_english;
            $organizer_details->short_description_arabic = $request->short_description_arabic;
            $organizer_details->long_description_english = $request->long_description_english;
            $organizer_details->long_description_arabic = $request->long_description_arabic;
            $organizer_details->save();

        }
        $user->assignRole($request->input('roles', []));
        $roles = Role::where('id', $request->roles[0])->first()->name;
        if ($roles == "Organizer") {
            OrganizerPaymentKeys::create([
                'organizer_id' => $user->id,
            ]);
        }
        return redirect()->route('users.index')->withStatus(__('User has added successfully.'));
    }

    public function show(User $user)
    {
        
        return view('admin.user.show', compact('user'));
    }

    public function edit(User $user)
    {

        $details = OrganizerDetails::where('user_id',$user->id)->first();
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::all();
        if ($user->hasRole('admin')) {
            return redirect()->route('users.index')->withStatus(__('You can not edit admin.'));
        }
        $orgs = User::role('Organizer')->orderBy('id', 'DESC')->get();
        return view('admin.user.edit', compact('roles', 'user', 'orgs','details'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'bail|required',
            'last_name' => 'bail|required',
            'phone' => 'bail|required',
            'email' => 'bail|required|unique:users,email,' . $user->id . ',id',
        ]);

        if( isset($request->arabic_name)  &&  !is_null($request->arabic_name) )
        {
            $organizer_details =  OrganizerDetails::where('user_id',$user->id)->first() ;
            if(!$organizer_details)
            {
                $organizer_details = new OrganizerDetails();
            }
            $organizer_details->user_id = $user->id;
            $organizer_details->arabic_name = $request->arabic_name;
            $organizer_details->facebook = $request->facebook;
            $organizer_details->twitter = $request->twitter;
            $organizer_details->instagram = $request->instagram;
            $organizer_details->whatsapp = $request->whatsapp;
            $organizer_details->terms_english = $request->terms_english;
            $organizer_details->terms_arabic = $request->terms_arabic;
            $organizer_details->short_description_english = $request->short_description_english;
            $organizer_details->short_description_arabic = $request->short_description_arabic;
            $organizer_details->long_description_english = $request->long_description_english;
            $organizer_details->long_description_arabic = $request->long_description_arabic;
            $organizer_details->save();

        }
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['org_id'] = $request->organization;
        $user->update($data);
        $user->syncRoles($request->input('roles', []));


        
        return redirect()->route('users.index')->withStatus(__('User has updated successfully.'));
    }

    public function bookTicket(Request $request)
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
        $events  = Event::with(['category:id,name'])
            ->where([['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d H:i:s')]]);
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
        return view('admin.org_bookTicket', compact('events', 'chip'));
    }

    public function organizerEventDetails(Request $request, $id)
    {
        $tickets = Ticket::all()->where('event_id', $id);
        return view('admin.organizer.organizerBookTicket', compact('tickets'));
    }

    public function organizerCheckout(Request $request, $id)
    {
        $data = Ticket::find($id);
        $data->user = AppUser::all();
        $data->event = Event::find($data->event_id);
        $setting = Setting::first(['app_name', 'logo']);


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
        // $data->tax = Tax::where([['user_id', $data->event->user_id], ['allow_all_bill', 1], ['status', 1]])->orderBy('id', 'DESC')->get()->makeHidden(['created_at', 'updated_at']);
        // $data->tax_total = intval(Tax::where([['user_id', $data->event->user_id], ['allow_all_bill', 1], ['status', 1]])->sum('price'));
        $data->currency_code = Setting::find(1)->currency;
        $seat = '';
        $orders = Order::where('event_id', $data->event['id'])->get();
        return view('admin.organizer.organizerCheckout', compact('data'));
    }
    public function organizerCreateOrder(Request $request)
    {
        $data = $request->all();
        $ticket = Ticket::find($request->ticket_id);
        $event = Event::find($ticket->event_id);
        $org = User::find($event->user_id);
        $user = $request->user;
        $data['order_id'] = '#' . rand(9999, 100000);
        $data['event_id'] = $event->id;
        $data['customer_id'] = $user;
        $data['organization_id'] = $org->id;
        $data['order_status'] = 'Pending';

        $order = Order::create($data);
        if (isset($request->tax_data)) {
            foreach (json_decode($data['tax_data']) as $value) {
                $tax['order_id'] = $order->id;
                $tax['tax_id'] = $value->id;
                $tax['price'] = $value->price;
                OrderTax::create($tax);
            }
        }
        return redirect('orders');
    }
    public function adminDashboard(Request $request)
    {

        $master['organizations'] = User::role('Organizer')->count();
        $master['users'] = AppUser::count();
        $master['total_order'] = Order::count();
        $master['pending_order'] = Order::where('order_status', 'Pending')->count();
        $master['complete_order'] = Order::where('order_status', 'Complete')->count();
        $master['cancel_order'] = Order::where('order_status', 'Cancel')->count();
        $master['eventDate'] = array();

        $events = Event::where([['status', 1], ['is_deleted', 0]])->orderBy('id', 'DESC')->get();
        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);
        $events  = Event::with(['category:id,name'])
            ->where([['status', 1], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d H:i:s')]]);
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

        $day = Carbon::parse(Carbon::now()->year . '-' . Carbon::now()->month . '-01')->daysInMonth;
        $monthEvent = Event::whereBetween('start_time', [date('Y') . "-" . date('m') . "-01 00:00:00",  date('Y') . "-" . date('m') . "-" . $day . " 23:59:59"])
            ->where([['status', 1], ['is_deleted', 0]])
            ->orderBy('id', 'DESC')->get();

        foreach ($monthEvent as $value) {
            $value->tickets = Ticket::where('event_id', $value->id)->sum('quantity');
            $value->sold_ticket = Order::where('event_id', $value->id)->where('payment_status',1)->where('order_status','Complete')->sum('quantity');
            $value->average = $value->tickets == 0 ? 0 : $value->sold_ticket * 100 / $value->tickets;
        }
        foreach ($events as $value) {
            $tickets = Ticket::where('event_id', $value->id)->sum('quantity');
            $sold_ticket = Order::where('event_id', $value->id)->where('payment_status',1)->where('order_status','Complete')->sum('quantity');
            $value->avaliable = $tickets - $sold_ticket;
            array_push($master['eventDate'], $value->start_time->format('Y-m-d'));
        }
        return view('admin.dashboard', compact('events', 'monthEvent', 'master'));
    }


    public function organizationDashboard(Request $request)
    {
        if(isset($request->selected_event_id) )
        {
            $selected_event_id = $request->selected_event_id ;
        }
        else
        {
            $selected_event = Event::where('user_id', Auth::user()->id)->orderBy('id','DESC')->first();
            $selected_event_id = is_null($selected_event) ? null : $selected_event->id ;
        }
        

       //dd($selected_event_id->id);
        $master['total_tickets'] = Ticket::where('event_id',$selected_event_id)->where('status',1)->where('is_deleted',0)->sum('quantity');

        $order_id = Order::where('event_id',$selected_event_id)->where('order_status',"Complete")->where('payment_status',1)->pluck('id')->toArray();
        $master['used_tickets'] =  Order::where('event_id',$selected_event_id)->where('order_status',"Complete")->where('payment_status',1)->sum('quantity');
        
        $master['events'] = Event::where([['user_id', Auth::user()->id], ['is_deleted', 0]])->count();
        $master['total_order'] = Order::where('event_id',$selected_event_id)->where('order_status',"Complete")->where('payment_status',1)->count();
        $master['pending_order'] = Order::where([['order_status', 'Pending'], ['event_id',$selected_event_id]])->count();
        $master['complete_order'] = Order::where([['order_status', 'Complete'], ['organization_id', Auth::user()->id]])->where('order_status',"Complete")->where('payment_status',1)->where('event_id',$selected_event_id)->count();
        $master['cancel_order'] = Order::where([['order_status', 'Cancel'], ['organization_id', Auth::user()->id]])->where('event_id',$selected_event_id)->count();
        $day = Carbon::parse(Carbon::now()->year . '-' . Carbon::now()->month . '-01')->daysInMonth;
        $monthEvent = Event::whereBetween('start_time', [date('Y') . "-" . date('m') . "-01 00:00:00",  date('Y') . "-" . date('m') . "-" . $day . " 23:59:59"])
            ->where([['status', 1], ['user_id', Auth::user()->id], ['is_deleted', 0]])
            ->orderBy('id', 'DESC')->get();

        foreach ($monthEvent as $value) {
            $value->tickets = Ticket::where('event_id', $value->id)->sum('quantity');
            $value->sold_ticket = Order::where('event_id', $value->id)->where('order_status',"Complete")->where('payment_status',1)->sum('quantity');
            $value->average = $value->tickets == 0 ? 0 : $value->sold_ticket * 100 / $value->tickets;
        }

        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);
        $events  = Event::with(['category:id,name'])
            ->where([['status', 1], ['user_id', Auth::user()->id], ['is_deleted', 0], ['event_status', 'Pending'], ['end_time', '>', $date->format('Y-m-d H:i:s')]]);
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

        $master['eventDate'] = array();
        foreach ($events as $value) {
            $tickets = Ticket::where('event_id', $value->id)->where('status',1)->where('is_deleted',0)->sum('quantity');
            $sold_ticket = Order::where('event_id', $value->id)->where('order_status',"Complete")->where('payment_status',1)->sum('quantity');
            $user_tickets  = Order::where('event_id', $value->id)->where([['order_status', 'Complete']])->where('order_status',"Complete")->where('payment_status',1)->sum('quantity');
            $value->avaliable = $tickets - $sold_ticket;
            $value->used_tickets = $user_tickets;
            array_push($master['eventDate'], $value->start_time->format('Y-m-d'));
        }
        $events_dropdown = Event::with(['category:id,name'])
            ->where([ ['user_id', Auth::user()->id], ['event_status', 'Pending']])->get();
        $selected_event = Event::where('id',$selected_event_id)->first();
        return view('admin.org_dashboard', compact('events', 'events_dropdown', 'monthEvent', 'master','selected_event'));
    }

    public function viewProfile()
    {
        $languages = Language::where('status', 1)->get();
        return view('admin.profile', compact('languages'));
    }

    public function editProfile(Request $request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            // $request->validate([
            //     'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:3048',
            // ]);
            $user = User::find(Auth::user()->id);
            if ($user->image != "defaultuser.png") {
                Storage::delete('/images/upload' . $user->image);
            }
            $data['image'] = (new AppHelper)->saveImage($request);
        }
        
        User::find(Auth::user()->id)->update($data);

        if (session()->get('locale') != $request->language) {
            App::setLocale($request->language);
            session()->put('locale', $request->language);
            $direction = Language::where('name', $request->language)->first()->direction;
            session()->put('direction', $direction);
        }
        return redirect('profile')->withStatus(__('Profile has updated successfully.'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'bail|required',
            'password' => 'bail|required|min:6',
            'confirm_password' => 'bail|required|same:password|min:6'
        ]);

        if (Hash::check($request->current_password, Auth::user()->password)) {
            User::find(Auth::user()->id)->update(['password' => Hash::make($request->password)]);
            return redirect('profile')->withStatus(__('Password has updated successfully.'));
        } else {
            return Redirect::back()->with('error_msg', 'Current Password is wrong!');
        }
    }



    public function makePayment($id)
    {
        $order = Order::with(['customer'])->find($id);
        return view('createPayment', compact('order'));
    }

    public function transction_verify(Request $request, $order_id)
    {
        $order = Order::find($order_id);
        $id = $request->input('transaction_id');
        if ($request->input('status') == 'successful') {
            $order->payment_token = $id;
            $order->payment_status = 1;
            $order->save();
            return view('transction_verify');
        } else {
            return view('cancel');
        }
    }


    public function changeLanguage($lang)
    {
        
        App::setLocale($lang);
        session()->put('locale', $lang);
        $dir = Language::where('name', $lang)->first()->direction;
        session()->put('direction', $dir);
        return redirect()->back();
    }

    public function scanner()
    {
        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);
        if (Auth::user()->hasRole('admin')) {
            $scanners = User::role('scanner')->orderBy('id', 'DESC')->get();
            $events = Event::where([['status', 1], ['is_deleted', 0], ['end_time', '>', $date->format('Y-m-d H:i:s')]])->get();
        } else {
            $scanners = User::role('scanner')->where('org_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            $events = Event::where([['status', 1], ['is_deleted', 0], ['end_time', '>', $date->format('Y-m-d H:i:s')], ['user_id', Auth::user()->id]])->get();
        }
        foreach ($scanners as $value) {
            $value->total_event = 0;
            foreach ($events as $key => $event) {
                if (!str_contains($event->scanner_id,$value->id)) {
                    if (preg_match("/\b$value->id\b/", $event->scanner_id)){
                        $value->total_event += 1;
                    }
                }

            }
        }
        return view('admin.scanner.index', compact('scanners'));
    }

    public function scannerCreate()
    {
        return view('admin.scanner.create');
    }

    public function addScanner(Request $request)
    {
        $request->validate([
            'first_name' => 'bail|required',
            'last_name' => 'bail|required',
            'email' => 'bail|required|email|unique:users',
            'phone' => 'bail|required',
            'password' => 'bail|required|min:6',
        ]);
        $data = $request->all();
        $data['org_id'] = Auth::user()->id;
        $data['password'] =  Hash::make($request->password);
        $data['language'] = Setting::first()->language;
        $user = User::create($data);
        $user->assignRole('scanner');
        return redirect('scanner')->withStatus(__('Scanner is added successfully.'));
    }

    public function blockScanner($id)
    {
        $user = User::find($id);
        $user->status = $user->status == "1" ? "0" : "1";
        $user->save();
        return redirect('scanner')->withStatus(__('User status changed successfully.'));
    }

    public function getScanner($id)
    {
        $data = User::where('org_id', $id)->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $data, 'success' => true], 200);
    }

    public function main_user_block($id)
    {
        $event = Event::where('user_id', $id)->get();
        foreach ($event as $item) {

            if ($item->end_time >= Carbon::now()) {
                return redirect('users')->withstatusblock(__("Please turn off all the ongoing events of the user before blocking"));
            }
        }
        $user = User::find($id);
        $user->status = $user->status == "1" ? "0" : "1";
        $user->save();
        return redirect('users')->withStatus(__('User status changed successfully.'));
    }

    public function check_email(Request $request)
    {
        $data = '';
        $setting = Setting::find(1);
        try {
            $config = array(
                'driver'     => $setting->mail_mailer,
                'host'       => $setting->mail_host,
                'port'       => $setting->mail_port,
                'encryption' => $setting->mail_encryption,
                'username'   => $setting->mail_username,
                'password'   => $setting->mail_password
            );
            Config::set('mail', $config);
            Mail::send(
                'emails.check_email',
                ['data' => $data],
                function ($message) use ($request, $setting) {
                    $message->from($setting->sender_email);
                    $message->to($request->input('mail_to'));
                    $message->subject('this mail is just to check configure');
                }
            );

            return response()->json(['message' => 'Email sent successfully', 'data' => $request->mail_to, 'success' => true]);
        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->json(['message' => 'Failed to sent Email', 'data' => $error, 'success' => false]);
        }
    }
    public function editAppUser($id)
    {
        $user = AppUser::find($id);
        return View('admin.appUser.edit', compact('user'));
    }
    public function updateAppUser(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'last_name' => 'bail|required',
            'phone' => 'bail|required',
        ]);
        $user = AppUser::find($request->id);
        $emailcheck = AppUser::where('email', $request->email)->where('id', '!=', $user->id)->first();
        if ($emailcheck) {
            return redirect()->back()->with('email', 'The email address has already been taken.');
        }
        $data =  $request->all();
        $user->update($data);
        return redirect()->back()->with('status', 'AppUser Details Update Successfully.');
    }
    public function orgincome(Request $request)
    {
        $events_dropdown = Event::with(['category:id,name'])
            ->where([ ['user_id', Auth::user()->id], ['event_status', 'Pending']])->get();
        $data = Order::with(['customer:id,name,last_name,email', 'event:id,name'])->where('payment_status', 1);
        $data->where('organization_id', Auth::user()->id);

        
        $selected_event = $selected_event_id = null ;
        if(isset($request->selected_event_id) )
        {

            $selected_event_id = $request->selected_event_id ;
            
            $data = $data->where('event_id',$selected_event_id);
            
            $selected_event = Event::where('id',$selected_event_id)->first();
        }
        
        $selected_payment = null ;
        if( isset($request->selected_payment_id))
        {
            $selected_payment = $request->selected_payment_id ; 
            if($request->selected_payment_id == "Edfapay")
            {
                $edfapy_status = array("ApplePay" , "EDAFPAY");
                $data = $data->whereIn('payment_type',$edfapy_status);
            }
            if($request->selected_payment_id == "Tabby")
            {
                $tabby_status = array("TABBY");
                $data = $data->whereIn('payment_type',$tabby_status);
            }
        }
        $data = $data->orderBy('id', 'DESC')->get();
        foreach ($data as $key => $value) {
            $ticket_total = 0 ; 
            $order_child = OrderChild::where('order_id',$value->id)->get();
            foreach ($order_child as  $child) {
                $ticket = Ticket::where('id',$child->ticket_id)->first();
                $ticket_total = $ticket_total + $ticket->price ; 
            }
            $value->ticket_price_total  = $ticket_total;  
        }


        return view('admin.organizer.revenue', compact('data','events_dropdown','selected_event_id','selected_event','selected_payment'));
    }
    public function checkoutSession(Request $request)
    {
        $request->session()->put('request', $request->all());
        $key = OrganizerPaymentKeys::where('organizer_id', $request->id)->first()->stripeSecretKey;
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
        $currencyCode = Setting::first()->currency;
        $amount = $request->total;
        if (!in_array($currencyCode, $supportedCurrency)) {
            $amount = $amount * 100;
        }
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
            'success_url' => route('orgStripe.success'),
            'cancel_url' => route('settlementReport'),
        ]);
        return response()->json(['id' => $session->id, 'status' => 200]);
    }
    public function stripeSuccess()
    {
        $request = Session::get('request');
        $data['user_id'] = $request['id'];
        $data['payment'] = $request['total'];
        $data['payment_status'] = 1;
        $data['payment_token'] = $request['token'] ?? null;
        $data['payment_type'] = 'Stripe';
        Settlement::create($data);
        Order::where([['organization_id', $data['user_id']], ['payment_status', 1], ['org_pay_status', 0]])->update(['org_pay_status' => 1]);
        return redirect()->route('settlementReport')->withStatus(__('Payment has done successfully.'));
    }
    public function orgKey(Request $request)
    {
        $key = OrganizerPaymentKeys::where('organizer_id', $request->id)->first()->stripePublicKey;
        return response()->json(['key' => $key, 'status' => 200]);
    }
    public function orderCreateForUser(Request $request)
    {
        if ($request->isMethod('get')) {
            if (Auth::user()->hasRole('admin')) {
                $ticket = Ticket::with('event')->where('is_deleted', 0)->get();
            } else {
                $ticket = Ticket::where('is_deleted', 0)->where('user_id', Auth::user()->id)->get();
            }
            return view('admin.order.create', compact('ticket'));
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required',
                'ticket_id' => 'required',
                'quantity' => 'required|min:1'
            ]);
            $ticket = Ticket::find($request->ticket_id, 'allday');
            if ($ticket->allday == 0 && $request->ticket_date == null) {
                return redirect()->back()->with('error', 'Please select a date');
            }
            $email  = $request->email;
            $user = AppUser::where('email', $email)->first();
            if (!$user) {
                $user =  AppUser::create([
                    'email' => $email,
                    'password' => bcrypt('123456'),
                    'name' => time(),
                    'provider' => 'LOCAL',
                    'is_verify' => 1,
                ]);
                $user = AppUser::where('email', $email)->first();
            }
            $data = [
                'ticket_id' => $request->ticket_id,
                'quantity' => $request->quantity,
                'user_id'   => $user->id,
                'ticket_date' => $request->ticket_date,
            ];
            $this->createOrder($data);
            return redirect()->back()->with('status', 'Order has been created successfully.');
        }
    }
    public function createOrder($data)
    {
        $data['payment_type'] = 'LOCAL';
        $ticket = Ticket::findOrFail($data['ticket_id']);
        $event = Event::find($ticket->event_id);

        $org = User::find($event->user_id);
        $user = AppUser::find($data['user_id']);
        $data['order_id'] = '#' . rand(9999, 100000);
        $data['event_id'] = $event->id;
        $data['customer_id'] = $user->id;
        $data['organization_id'] = $org->id;
        $data['payment_status'] = 0;
        $data['order_status'] = 'Complete';
        $data['ticket_date'] = Carbon::parse($data['ticket_date'])->format('Y-m-d 00:00:00');
        $com = Setting::find(1, ['org_commission_type', 'org_commission']);
        $payment = $ticket->price * $data['quantity'];
        $allTax = Tax::where('status', 1)->get();
        $totalTax = [];
        foreach ($allTax as $key => $value) {
            if ($value->amount_type == 'percentage') {
                $totalTax[$key]['id'] = $value->id;
                $totalTax[$key]['price'] = $payment * $value->price / 100;
            }
            if ($value->amount_type == 'price') {
                $totalTax[$key]['id'] = $value->id;
                $totalTax[$key]['price'] =  $value->price;
            }
        }
        // calculate whole tax
        $data['tax_data'] = json_encode($totalTax);
        $data['tax'] = array_sum(array_column($totalTax, 'price'));
        $p =   $payment - $data['tax'];
        if ($ticket->ticket == "FREE") {
            $data['org_commission']  = 0;
        } else {
            if ($com->org_commission_type == "percentage") {
                $data['org_commission'] =  $p * $com->org_commission / 100;
            } else if ($com->org_commission_type == "amount") {
                $data['org_commission']  = $com->org_commission;
            }
        }

        $data['payment'] =  $payment + $data['tax'];

        $order = Order::create($data);

        for ($i = 1; $i <= $data['quantity']; $i++) {
            $child['ticket_number'] = uniqid();
            $child['ticket_id'] = $data['ticket_id'];
            $child['order_id'] = $order->id;
            $child['customer_id'] =  $user->id;
            $child['checkin'] = $ticket->maximum_checkins ?? null;
            $child['paid'] = 0;
            OrderChild::create($child);
        }
        if (isset($data['tax_data'])) {
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
        $detail['quantity'] = $data['quantity'];
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
        $details['quantity'] = $data['quantity'];
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
        $or_detail['quantity'] = $data['quantity'];
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
        $details1['quantity'] = $data['quantity'];
        $details1['event_name'] = Event::find($order->event_id)->name;
        $details1['date'] = Event::find($order->event_id)->start_time->format('d F Y h:i a');
        $details1['app_name'] = $setting->app_name;
        if ($setting->mail_notification == 1) {
            try {
                Mail::to($org->email)->send(new TicketBookOrg($new_ticket->mail_content, $details1, $new_ticket->subject));
            } catch (\Throwable $th) {
                Log::info($th->getMessage());
            }
        }
        return true;
    }
    function getTicketsDetails(Request $request)
    {
        $ticket = Ticket::find($request->id, 'allday');
        return response()->json(['allday' => $ticket->allday]);
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

     public function userApprove ( Request $request , $id  )
     {
        $user = User::where("id",$id)->update(['is_organizer_approve' => 1]); 
        return redirect()->back()->with(['sucess'=> "Organizer Approved"]);
     }

     public function destroy ( $id )
     {
        $delete_user = User::find($id);
        $delete_user->is_delete =  1 ; 
        $delete_user->status =  0 ;
        $delete_user->save();     

        return  $delete_user ; 
     }
}
