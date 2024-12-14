<?php

namespace App\Http\Controllers\Organizer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Twilio\Rest\Client as Clients;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view("organizers.auth.login");
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->only("email", "password"), $request->input("remember"))) {
            if (Auth::user()->hasRole('Organizer')) {
                $user = Auth::user();
                $setting = Setting::first();
                if (Auth::user()->status == 0) {
                    $request->session()->flush();
                    return redirect()->route("organizers.login")->with('error_msg', 'Blocked By Admin.');
                }
                if (Auth::user()->is_organizer_approve == 0) {
                    $request->session()->flush();
                    return redirect()->route("organizers.login")->with('error_msg', 'Not Approved By Admin.');
                }
                if (!$setting->user_verify) {
                    return redirect()->intended('organization/home');
                } else {
                    if (!$user->is_verify) {
                        if ($setting->verify_by == 'email' && $setting->mail_host != NULL) {
                            $details = [
                                'url' => url('organizer/VerificationConfirm/' . $user->id)
                            ];
                            Mail::to($user->email)->send(new \App\Mail\VerifyMail($details));
                            $request->session()->flush();
                            return redirect()->route("organizers.login")->with(['success' => "Verification link has been sent to your email. Please visit that link to complete the verification"]);
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
        }

        return Redirect::back()->with('error_msg', 'Invalid Username or Password.');
    }
}
