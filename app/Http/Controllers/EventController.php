<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\Setting;
use App\Http\Controllers\AppHelper;
use App\Models\User;
use App\Models\AppUser;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\EventEmail;
use Carbon\Carbon;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Storage;


use Illuminate\Support\Facades\Config;
use Mail;

class EventController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('event_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (Auth::user()->hasRole('admin')) {
            $timezone = Setting::find(1)->timezone;
            $date = Carbon::now($timezone);
            $events  = Event::with(['category:id,name'])
                ->where([['is_deleted', 0], ['event_status', 'Pending']]);
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
        } elseif (Auth::user()->hasRole('Organizer')) {
            $timezone = Setting::find(1)->timezone;
            $date = Carbon::now($timezone);
            $events  = Event::with(['category:id,name'])
                ->where([['user_id', Auth::user()->id], ['is_deleted', 0], ['event_status', 'Pending']]);
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

        }
        return view('admin.event.index', compact('events'));
    }

    public function create()
    {
        abort_if(Gate::denies('event_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $category = Category::where('status', 1)->orderBy('id', 'DESC')->get();
        $users = User::role('Organizer')->orderBy('id', 'DESC')->get();
        if (Auth::user()->hasRole('admin')) {
            $scanner = User::role('scanner')->orderBy('id', 'DESC')->get();
        } else if (Auth::user()->hasRole('Organizer')) {
            $scanner = User::role('scanner')->where('org_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        }
        return view('admin.event.create', compact('category', 'users', 'scanner'));
    }

    public function store(Request $request)
    {
        
         $request->validate([
            'name' => 'bail|required',
            'image' => 'bail|required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_time' => 'bail|required',
            //'end_time' => 'bail|required',
            'category_id' => 'bail|required',
            'type' => 'bail|required',
            //'address' => 'bail|required_if:type,offline',
            'lat' => 'bail|required_if:type,offline',
            'lang' => 'bail|required_if:type,offline',
            'status' => 'bail|required',
            'url' => 'bail|required_if:type,online',
            'description' => 'bail|required',
             
            'is_private' => 'required|boolean',
           // 'scanner_id' => 'bail|required_if:type,offline',
            //'people' => 'bail|required',
        ]);
        // ], [
        //     'image.max' => 'The image size must not exceed 2MB.', // Custom error message
        // ]);
        
        $data = $request->all();
        if(isset($request->scanner_id) && $request->type == 'offline'){
            $data['scanner_id'] = implode(',', $request->scanner_id);
        }
        $data['security'] = 1;
        //dd($request->image , $request->cropped_image);
         // Extract base64 string
         $imageData = $request->input('cropped_image');
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                return response()->json(['message' => 'Invalid image type.'], 400);
            }

            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                return response()->json(['message' => 'Base64 decode failed.'], 400);
            }

            // Generate a unique filename
            $filename = 'image_' . time() . '.' . $type;
            
            // Store the image
            Storage::disk('root_public')->put('images/upload/' . $filename, $imageData);

            //return response()->json(['path' => 'storage/images/upload/test/' . $filename], 200);
            $data['image'] = $filename  ;
        }
        else
        {
            if ($request->hasFile('image')) {
                $data['image'] = (new AppHelper)->saveImage($request);
            }
        }
        
        if (!Auth::user()->hasRole('admin')) {
            $data['user_id'] = Auth::user()->id;
        }
        if(isset($data['is_one_day']) && $data['is_one_day'] == 1)
        {
            $data['end_time'] = Carbon::parse ($data['start_time'])->addDays(30); 
        }
        $event = Event::create($data);
        
        if(!is_null($request->emails) && count($request->emails) > 0){
            foreach($request->emails as $key => $value){
                if($value != null || $value != ''){
                    $event_email = new EventEmail();
                    $event_email->event_id = $event->id;
                    $event_email->email = $value; 
                    $event_email->save(); 

                    $dataemail['event'] = Event::find($event->id); 
                    $dataemail['email'] = $value;

                    Mail::send(['html'=>'emails.event_email'], $dataemail, function($message) use ($dataemail) {
                        $message->to($dataemail['email'])->subject
                            ('Invited to Event');
                        $message->from('ticketbyksa@gmail.com','TicketBy');
                        });
                } 
            }
        }
        return redirect()->route('events.index')->withStatus(__('Event has added successfully.'));
    }

    public function show($event)
    {
        $event = Event::with(['category', 'organization'])->find($event);
        $event->ticket = Ticket::where([['event_id', $event->id], ['is_deleted', 0]])->orderBy('id', 'DESC')->get();
        (new AppHelper)->eventStatusChange();
        $event->sales = Order::with(['customer:id,name', 'ticket:id,name'])->where('event_id', $event->id)->orderBy('id', 'DESC')->get();
        foreach ($event->ticket as $value) {
            $value->used_ticket = Order::where('ticket_id', $value->id)->sum('quantity');
        }
        $event_emails = EventEmail::where('event_id',$event->id)->get(); 
        return view('admin.event.view', compact('event','event_emails'));
    }

    public function edit(Event $event)
    {
        abort_if(Gate::denies('event_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $category =  Category::where('status', 1)->orderBy('id', 'DESC')->get();
        $users = User::role('Organizer')->orderBy('id', 'DESC')->get();
        if (Auth::user()->hasRole('admin')) {
            $scanner = User::role('scanner')->orderBy('id', 'DESC')->get();
        } else if (Auth::user()->hasRole('Organizer')) {
            $scanner = User::role('scanner')->where('org_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        }
        $event_emails = EventEmail::where('event_id',$event->id)->get(); 
        return view('admin.event.edit', compact('event', 'category', 'users', 'scanner','event_emails'));
    }

    private function removeInlineCss($html)
    {
        $doc = new \DOMDocument();
        // Load HTML and handle character encoding issues
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8"?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new \DOMXPath($doc);
        // Find all elements with a style attribute
        $nodes = $xpath->query('//@style');

        foreach ($nodes as $node) {
            $node->parentNode->removeAttribute('style');
        }

        return $doc->saveHTML();
    }

    public function update(Request $request, Event $event)
    {

        $request->validate([
            'name' => 'bail|required',
            'start_time' => 'bail|required',
           // 'end_time' => 'bail|required',
            'category_id' => 'bail|required',
            'type' => 'bail|required',
            'address' => 'bail|required_if:type,offline',
            'lat' => 'bail|required_if:type,offline',
            'lang' => 'bail|required_if:type,offline',
            'status' => 'bail|required',
            'url' => 'bail|required_if:type,online',
            'description' => 'bail|required',
            //'scanner_id' => 'bail|required_if:type,offline',
            //'people' => 'bail|required',
            'is_private' => 'required|boolean',
            'emails.*' => 'bail|required_if:is_private,1|email',
        ]);
        $data = $request->all();
        
        if($request->type == 'offline' && isset( $request->scanner_id)){
            $data['scanner_id'] = implode(',', $request->scanner_id);
        }
        if ($request->hasFile('image')) {
            (new AppHelper)->deleteFile($event->image);
            $data['image'] = (new AppHelper)->saveImage($request);
        }
        if(isset($data['is_one_day']) && $data['is_one_day'] == 1)
        {
            $data['end_time'] = $data['start_time']; 
        }

        $cleanHtmldescription = $this->removeInlineCss($request->description);
        $cleanHtmldescription_arabic = $this->removeInlineCss($request->description_arabic);
        $data['description'] = $cleanHtmldescription; 
        $data['description_arabic'] = $cleanHtmldescription_arabic; 

        $event_id = $event->id;
        $event = Event::find($event->id)->update($data);
         
        if($request->is_private == "0"){
            EventEmail::where('event_id',$event_id)->delete();
        } 
        
        if(!is_null($request->emails) && count($request->emails) > 0){
            EventEmail::where('event_id',$event_id)->delete();
            foreach($request->emails as $key => $value){
               $event_email = new EventEmail();
               $event_email->event_id = $event_id;
               $event_email->email = $value; 
               $event_email->save(); 

                $dataemail['event'] = Event::find($event_id); 
                $dataemail['email'] = $value;

                Mail::send(['html'=>'emails.event_email'], $dataemail, function($message) use ($dataemail) {
                    $message->to($dataemail['email'])->subject
                        ('Invited to Event');
                    $message->from('ticketbyksa@gmail.com','TicketBy');
                    }); 
            }
        }
        return redirect()->route('events.index')->withStatus(__('Event has updated successfully.'));
    }

    public function destroy(Event $event)
    {
        try {
            Event::find($event->id)->update(['is_deleted' => 1, 'event_status' => 'Deleted']);
            $ticket = Ticket::where('event_id', $event->id)->update(['is_deleted' => 1]);
            $banner = Banner::where('event_id', $event->id)->update(['status' => 0]);
            $coupon = Coupon::where('event_id', $event->id)->update(['status' => 0]);
            return true;
        } catch (Throwable $th) {
            return response('Data is Connected with other Data', 400);
        }
    }

    public function getMonthEvent(Request $request)
    {
        (new AppHelper)->eventStatusChange();
        $day = Carbon::parse($request->year . '-' . $request->month . '-01')->daysInMonth;
        if (Auth::user()->hasRole('Organizer')) {
            $data = Event::whereBetween('start_time', [$request->year . "-" . $request->month . "-01 12:00",  $request->year . "-" . $request->month . "-" . $day . "  23:59"])
                ->where([['status', 1], ['is_deleted', 0], ['user_id', Auth::user()->id]])
                ->orderBy('id', 'DESC')
                ->get();
        }
        if (Auth::user()->hasRole('admin')) {
            $data = Event::whereBetween('start_time', [$request->year . "-" . $request->month . "-01 12:00",  $request->year . "-" . $request->month . "-" . $day . " 23:59"])
                ->where([['status', 1], ['is_deleted', 0]])->orderBy('id', 'DESC')->get();
        }
        foreach ($data as $value) {
            $value->tickets = Ticket::where([['event_id', $value->id], ['is_deleted', 0]])->sum('quantity');
            $value->sold_ticket = Order::where('event_id', $value->id)->sum('quantity');
            $value->day = $value->start_time->format('D');
            $value->date = $value->start_time->format('d');
            $value->average = $value->tickets == 0 ? 0 : $value->sold_ticket * 100 / $value->tickets;
        }
        return response()->json(['data' => $data, 'success' => true], 200);
    }

    public function eventGallery($id)
    {
        $data  = Event::find($id);
        return view('admin.event.gallery', compact('data'));
    }

    public function addEventGallery(Request $request)
    {
        $event = array_filter(explode(',', Event::find($request->id)->gallery));
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $name = uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            array_push($event, $name);
            Event::find($request->id)->update(['gallery' => implode(',', $event)]);
        }
        return true;
    }

    public function removeEventImage($image, $id)
    {

        $gallery = array_filter(explode(',', Event::find($id)->gallery));
        if (count(array_keys($gallery, $image)) > 0) {
            if (($key = array_search($image, $gallery)) !== false) {
                unset($gallery[$key]);
            }
        }
        $aa = implode(',', $gallery);
        $data = Event::find($id);
        $data->gallery = $aa;
        $data->update();
        return redirect()->back();
    }

    public function changeOrder ( Request $request )
    {
        Event::where('id',$request->id)->update(['orderby'=>$request->orderby]);
        return true;
    }
}
