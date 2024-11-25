<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        $query = http_build_query([
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'offline',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/auth?' . $query);
    }

    public function handleGoogleCallback()
    {
        try {
            $http = new Client;

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

            return redirect()->intended('dashboard');

        } catch (Exception $e) {
            return redirect('auth/google');
        }
    }
}
