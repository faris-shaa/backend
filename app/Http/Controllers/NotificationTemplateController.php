<?php

namespace App\Http\Controllers;

use App\Models\AppUser;
use App\Models\NotificationTemplate;
use App\Models\Category;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OneSignal;

class NotificationTemplateController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('notification_template_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = NotificationTemplate::OrderBy('id', 'DESC')->get();
        return view('admin.template.index', compact('data'));
    }

    public function create()
    {
        abort_if(Gate::denies('notification_template_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.template.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'bail|required',
            'subject' => 'bail|required',
        ]);
        $data = $request->all();
        NotificationTemplate::create($data);
        return redirect()->route('notification-template.index')->withStatus(__('Template has added successfully.'));
    }

    public function edit(NotificationTemplate $notificationTemplate)
    {
        abort_if(Gate::denies('notification_template_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return response()->json(['success' => true, 'data' => $notificationTemplate], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subject' => 'bail|required',
        ]);
        $data = $request->all();
        NotificationTemplate::find($id)->update($data);
        return redirect()->route('notification-template.index')->withStatus(__('Template has update successfully.'));
    }


    public function notification()
    {
        $notification = Notification::where('organizer_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('admin.notification', compact('notification'));
    }
    public function getNotification()
    {
        $user = User::get();
        $appuser = AppUser::get();
        return view('admin.getNotification',compact('user','appuser'));
    }
    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $userId = User::whereNotNull('device_token')->pluck('device_token')->all();
        $imageUrl = '';
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $name = uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/notification_img');
            $image->move($destinationPath, $name);
            $imageUrl = asset('notification_img/' . $name);
        }
        if ($request->has('user_ids')) {
            foreach ($request->user_ids as $user) {
                $appUser = AppUser::find($user);
                if ($appUser) {
                    (new AppHelper)->sendOneSignal('user', $appUser->device_token, $request->description, $imageUrl);
                }
            }
        }

        return redirect()->back()->withStatus(__('Notification sent successfully.'));
    }
    public function deleteNotification($id)
    {
        $data = Notification::find($id);
        $data->delete();
        return redirect()->back();
    }
    public function markAllAsRead()
    {
        $notification = Notification::where('status', 1)->get();
        if (isset($notification)) {
            DB::table('notification')->update(['status' => 0]);
            return redirect()->back();
        }
    }
    public function edfapay ( )
    {
        // Example values
        $order_number = "12345";
        $order_amount = "100.00";
        $order_currency = "USD";
        $order_description = "Payment for services";
        $password = "mySecretPassword";

        // Generate the hash for EDFAPay API
        $edfapayHash = $this->generateEdfapayHash($order_number, $order_amount, $order_currency, $order_description, $password);
        dd( $edfapayHash);

    }

    function generateEdfapayHash($order_number, $order_amount, $order_currency, $order_description, $password) {
        // Step 1: Concatenate the input values
        $concatenatedString = $order_number . $order_amount . $order_currency . $order_description . $password;
        
        // Step 2: Encode the concatenated string using MD5
        $md5Encoded = md5($concatenatedString);
        
        // Step 3: Generate SHA1 hash of the MD5 encoded string
        $sha1Hash = sha1($md5Encoded);
        
        // Step 4: Convert the SHA1 hash to uppercase
        $uppercasedSha1Hash = strtoupper($sha1Hash);
        
        return $uppercasedSha1Hash;
    }
}
