<?php

use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LicenseController;
use GuzzleHttp\Middleware;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\GoogleController;
use App\Models\AppUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Frontend\OrganizerController;
use App\Http\Controllers\Frontend\EventOrgananizerController;
use App\Http\Controllers\Frontend\AjaxController;


Route::get('taxSettle', [FrontendController::class, 'taxSettle']);


Route::post('/ajax', [AjaxController::class, "index"]);
Route::get('fifthSightEvents', [FrontendController::class, 'fifthSightEvents']);
Route::get('auth/google', [FrontendController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [FrontendController::class, 'handleGoogleCallback']);
Route::post('/applyCoupon', [FrontendController::class, 'applyCoupon']);

Route::get('/delete/pending', [FrontendController::class, 'deletePending']);
Route::get('/order-mail-sendar', [FrontendController::class, 'ordarMailSender']);
Route::get('/create-order', [FrontendController::class, 'createOrderTickets']);

Route::any('/get-apple-pay-session', [FrontendController::class, 'getApplePaySession']);

Route::post('/guest/subscribe', [FrontendController::class, 'subscribe']);

Route::get('/pdf/download', [FrontendController::class, 'pdfTest']);


Route::get('/send-mail/{id}', [FrontendController::class, 'sendOrderMail']);

Route::post('/web/login', [FrontendController::class, 'webUserLogin']);

Route::get('/export-data', [FrontendController::class, 'excelExport']);

Route::get('/logic-testing', [FrontendController::class, 'logicTesting']);


Route::get('/qr-generate', [FrontendController::class, 'qrcodeGenerator']);


Route::any('/tamara-notification', [OrderController::class, 'tamaraNotification'])->name('tamaraNotification');

Route::group(['middleware' => ['mode', 'XSS']], function () {


    Route::any('/tamara', [OrderController::class, 'tamara'])->name('tamara');

    Route::get('/email', [FrontendController::class, 'emailtesting'])->name('emailtesting');
    Route::get('/testing', [OrderController::class, 'testingCode'])->name('testing');
    Route::get('/', [FrontendController::class, 'home'])->name('home');
    Route::get('/home2', [FrontendController::class, 'home'])->name('home2');
    Route::get('/thankyou', [FrontendController::class, 'thankyou'])->name('thankyou');
    Route::get('/failed', [FrontendController::class, 'failed'])->name('failed');
    Route::post('/send-to-admin', [FrontendController::class, 'sentMessageToAdmin']);
    Route::get('/privacy_policy', [FrontendController::class, 'privacypolicy']);
    Route::get('/FAQ', [FaqController::class, 'show']);
    Route::get('/appuser-privacy-policy', [FrontendController::class, 'appuserPrivacyPolicyShow']);
    Route::get('/show-details/{id}', [OrderController::class, 'showTicket']);
    Route::get('/events_details/{id}', [EventController::class, 'show']);
    Route::get('/change/event/order', [EventController::class, 'changeOrder']);

    Route::get('/events/previous', [FrontendController::class, 'previousEvent']);
    Route::get('organizer/VerificationConfirm/{id}', [FrontendController::class, 'LoginByMailOrganizer']);
    Route::get('organizer/otp-verify/{userid}', [FrontendController::class, 'otpViewOrganizer']);
    Route::post('organizer/otp-verify', [FrontendController::class, 'otpVerifyOrganizer']);

    Route::group(['prefix' => 'user'], function () {

        Route::post('/register', [OrderController::class, 'userRegister']);
        Route::post('/login-otp', [OrderController::class, 'sendLoginOTP'])->name('login.otp');

        Route::get('/VerificationConfirm/{id}', [FrontendController::class, 'LoginByMail']);
        Route::get('/register', [FrontendController::class, 'register']);

        Route::get('/otp-verify/{userid}', [FrontendController::class, 'otpView']);
        Route::post('/otp-verify', [FrontendController::class, 'otpVerify']);
        Route::get('login', [FrontendController::class, 'login'])->name('user.login');
        Route::post('/login', [FrontendController::class, 'userLogin']);
        Route::post('/login/verify/otp', [FrontendController::class, 'loginVerifyOtp']);

        Route::get('/resetPassword', [FrontendController::class, 'resetPassword']);
        Route::post('/resetPassword', [FrontendController::class, 'userResetPassword']);
        Route::get('/org-register', [FrontendController::class, 'orgRegister']);
        Route::post('/org-register', [FrontendController::class, 'organizerRegister']);
        Route::get('/logout', [LicenseController::class, 'adminLogout'])->name('logout');
        Route::get('/logoutuser', [FrontendController::class, 'userLogout'])->name('logoutUser');
        Route::post('/search_event', [FrontendController::class, 'searchEvent']);
        Route::get('/tag/{tagname}', [FrontendController::class, 'eventsByTag']);
        Route::get('/blog-tag/{tagname}', [FrontendController::class, 'blogByTag']);
        Route::get('/FAQs', [FrontendController::class, 'Faqs']);
        Route::any('/stripe/create-session', [FrontendController::class, 'checkoutSession'])->name('stripe.checkoutSession');
        Route::get('/stripe/success', [FrontendController::class, 'stripeSuccess'])->name('stripe.success');
        Route::get('/stripe/cancel', [FrontendController::class, 'striepCancel'])->name('stripe.cancel');
    });

    Route::group(['middleware' => 'checkStatus'], function () {

        Route::get('/all-events', [FrontendController::class, 'allEvents']);
        Route::post('/all-events', [FrontendController::class, 'allEvents']);
        Route::get('/events-category/{id}/{name}', [FrontendController::class, 'categoryEvents']);
        Route::get('/event-type/{type}', [FrontendController::class, 'eventType']);
        Route::get('/event/{id}/{name}', [FrontendController::class, 'eventDetail']);
        Route::get('/event/details/{id}/{name}', [FrontendController::class, 'eventDetailApi']);
        Route::get("organizer/{external_id}/{name}", EventOrgananizerController::class)->name("events.organizer");
        Route::get('/events/{id}', [FrontendController::class, 'eventDetail']);
        Route::get('/organizations/{id}', [FrontendController::class, 'orgDetail'])->name('organizationDetails');
        Route::post('/report-event', [FrontendController::class, 'reportEvent']);
        Route::get('/all-category', [FrontendController::class, 'allCategory']);
        Route::get('/all-blogs', [FrontendController::class, 'blogs']);
        Route::get('/blog-detail/{id}/{name}', [FrontendController::class, 'blogDetail']);
        Route::get('/contact', [FrontendController::class, 'contact']);
        Route::get('/checkout/{id}', [FrontendController::class, 'checkout']);

        // frontend
        // Route::get('/checkout', [FrontendController::class, 'checkoutFront']);


        Route::group(['middleware' => 'appuser'], function () {

            Route::get('email/verify/{id}/{token}', [FrontendController::class, 'emailVerify']);


            Route::any('/createOrder', [FrontendController::class, 'createOrder'])->name('createOrderUser');
            Route::get('/user/profile', [FrontendController::class, 'userTickets']);
            //Route::get('/user/profile2', [FrontendController::class, 'update_profile']);
            Route::get('/add-favorite/{id}/{type}', [FrontendController::class, 'addFavorite']);
            Route::get('/add-followList/{id}', [FrontendController::class, 'addFollow']);
            Route::post('/add-bio', [FrontendController::class, 'addBio']);
            Route::get('/change-password', [FrontendController::class, 'changePassword']);
            Route::post('/user-change-password', [FrontendController::class, 'changeUserPassword']);
            Route::post('/upload-profile-image', [FrontendController::class, 'uploadProfileImage']);
            Route::get('/user/profile2', [FrontendController::class, 'userTickets'])->name('myTickets');
            Route::get('/my-ticket/{id}', [FrontendController::class, 'userOrderTicket']);

            Route::get('/update_profile', [FrontendController::class, 'update_profile']);
            Route::post('/update_user_profile', [FrontendController::class, 'update_user_profile']);
            Route::get('/getOrder/{id}', [FrontendController::class, 'getOrder']);
            Route::post('/add-review', [FrontendController::class, 'addReview']);
            Route::get('/web/create-payment/{id}', [FrontendController::class, 'makePayment']);
            Route::post('/web/payment/{id}', [FrontendController::class, 'initialize'])->name('frontendPay');
            Route::get('/web/rave/callback/{id}', [FrontendController::class, 'callback'])->name('frontendCallback');

            # Wallet
            Route::group(['prefix' => 'user'], function () {
                Route::get('/wallet', [WalletController::class, 'wallet'])->name('myWallet');
                Route::get('/wallet/add-money', [WalletController::class, 'addToWallet'])->name('addToWallet');
                Route::post('/deposite', [WalletController::class, 'deposite'])->name('deposite');
                Route::any('/wallet/stripe/create-session', [WalletController::class, 'checkoutSession'])->name('stripe.checkoutSession');
                Route::get('/wallet/stripe/success', [WalletController::class, 'stripeSuccess'])->name('walletStripe.success');
                Route::get('/wallet/stripe/cancel', [WalletController::class, 'striepCancel'])->name('walletStripe.cancel');
            });
        });
    });
});
