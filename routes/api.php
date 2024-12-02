<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrganizationApiController;
use App\Http\Controllers\ScannerApiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/testSendMail',[ApiController::class,'testSendOrderMail']);

Route::post('/social-login',[ApiController::class,'socailLogin']);

Route::get('/banner-details',[ApiController::class,'bannerDetails']);

Route::get('/testing-logic',[OrderController::class,'testingLogic']);

Route::get('/event/time/{id}', [ApiController::class, 'eventTime']);

Route::any('/apple/callback', [OrderController::class, 'appleCallback']);
Route::post('/user/register', [OrderController::class, 'userRegisterApp']);
Route::post('/user/login', [ApiController::class, 'userLogin']);
Route::post('/update-location', [ApiController::class, 'updateLocation']);
Route::get('/user/delete/{id}', [ApiController::class, 'userDelete']);
//Route::post('/user/register', [ApiController::class, 'userRegister']);
Route::post('/user/forget-password', [OrderController::class, 'forgetPassword']);
Route::post('/user/otp-verify',[ApiController::class,'otpVerify']);

// new apis 
Route::post('/user/ticket-detail-multiple', [ApiController::class, 'ticketDetailMultiple']);
// new apis 


//Route::post('/user/forget-password', [ApiController::class, 'forgetPassword']);
Route::post('/login/verify/otp', [ApiController::class, 'loginVerifyOtp']);
Route::get('/user/setting', [ApiController::class, 'allSetting']);
Route::post('/user/events', [ApiController::class, 'events']);
Route::get('/city', [ApiController::class, 'cityList']);
Route::get('/user/event-detail/{id}', [ApiController::class, 'eventDetail']);
Route::get('/user/ticket-detail/{id}', [ApiController::class, 'ticketDetail']);
Route::get('/user/event-tickets/{id}', [ApiController::class, 'eventTickets']);
Route::post('/user/event-from-category', [ApiController::class, 'EventFrmCategory']);
Route::post('/user/search-free-event', [ApiController::class, 'searchFreeEvent']);
Route::post('/user/report-event', [ApiController::class, 'reportEvent']);
Route::get('/user/all-coupon', [ApiController::class, 'allCoupon']);
Route::post('/user/search-event', [ApiController::class, 'searchEvent']);
Route::post('/user/search-event/web', [ApiController::class, 'searchEventWeb']);
Route::get('/user/previous/event', [ApiController::class, 'perviousEvent']);
Route::post('/time/slots', [ApiController::class, 'getTimeSlots']);
Route::post('/web/login', [OrderController::class, 'webUserLogin']);
Route::post('/app/login',[OrderController::class,'webUserApp']);

Route::post('/user/search-filter', [ApiController::class, 'searchFilter']);
Route::get('/user/category', [ApiController::class, 'category']);
Route::get('/organizer-profile', [ApiController::class, 'organizerProfile']);
Route::get('/pos/events', [ApiController::class, 'posEvent']);
Route::group(['prefix' => 'user', 'middleware' => ['auth:userApi']], function () {
    
    Route::get('/organization', [ApiController::class, 'organization']);
    Route::get('/organization-detail/{id}', [ApiController::class, 'organizationDetail']);
    Route::get('/category-event', [ApiController::class, 'categoryEvent']);
    Route::get('/profile', [ApiController::class, 'userProfile']);
    Route::get('/user-likes', [ApiController::class, 'userLikes']);
    Route::get('/user-following', [ApiController::class, 'userFollowing']);
    Route::post('/edit-profile', [ApiController::class, 'editUserProfile']);
    Route::post('/change-profile-image', [ApiController::class, 'editImage']);
    Route::post('/add-favorite', [ApiController::class, 'addFavorite']);
    Route::post('/add-following-list', [ApiController::class, 'addFollowing']);
    Route::post('/check-code', [ApiController::class, 'checkCode']);
    Route::get('/user-notification', [ApiController::class, 'userNotification']);
    Route::get('/order-tax/{event_id}', [ApiController::class, 'orderTax']);
    Route::post('/order-tax-multiple', [ApiController::class, 'orderTaxMultiple']);
    Route::post('/create-order', [ApiController::class, 'createOrder']);
    Route::post('/create-order-multiple', [ApiController::class, 'createOrderMultiple']);
    Route::post('/create-order-multiple-unpaid', [ApiController::class, 'createOrderMultipleUnpaid']);

    Route::post('/create-order-unpaid', [ApiController::class, 'createOrderUnpaid']);
    
    Route::get('/user-order', [ApiController::class, 'userOrder']);
    Route::get('/view-all-tickets', [ApiController::class, 'viewUserOrder']);
    Route::get('/view-single-order/{id}', [ApiController::class, 'viewSingleOrder']);
    Route::post('/add-review', [ApiController::class, 'addReview']);
    Route::post('/change-password', [ApiController::class, 'changePassword']);
    Route::get('/clear-notification', [ApiController::class, 'clearNotification']);
    Route::get('/user_delete_self/{id}', [ApiController::class, 'user_delete']);

    // Wallet
    Route::get('/get-wallet',[ApiController::class,'getBalance'])->name('getBalance');
    Route::post('/wallet-deposit',[ApiController::class,'deposit'])->name('deposit');
});

// organization
Route::post('/web/user/login', [OrderController::class, 'webUserLogin']);
Route::post('/organization/login', [OrganizationApiController::class, 'organizationLogin']);
Route::post('/organization/forget-password', [OrderController::class, 'forgetPasswordOrganizer']);
//Route::post('/organization/register', [OrganizationApiController::class, 'organizationRegister']);
Route::post('/organization/register', [OrderController::class, 'organizationRegister']);
Route::get('/organization/setting', [OrganizationApiController::class, 'organizationSetting']);
Route::post('/organization/otp-verify',[OrganizationApiController::class,'otpVerify']);
Route::get('/organization/delete/{id}',[OrganizationApiController::class,'deleteOrganizer']);

Route::post('/resend-otp',[OrganizationApiController::class,'reSendOTP']);




Route::group(['prefix' => 'organization', 'middleware' => ['auth:api']], function () {


    Route::post('/set-profile', [OrganizationApiController::class, 'setProfile']);
    Route::get('/profile', [OrganizationApiController::class, 'profile']);

    Route::get('/all-events', [OrganizationApiController::class, 'events']);
    Route::get('/search-events', [OrganizationApiController::class, 'searchEvents']);
    Route::get('/all-scanner', [OrganizationApiController::class, 'scanner']);
    Route::post('/add-scanner', [OrganizationApiController::class, 'addScanner']);
    Route::post('/add-event', [OrganizationApiController::class, 'addEvent']);
    Route::post('/edit-event', [OrganizationApiController::class, 'editEvent']);
    Route::get('/cancel-event/{id}', [OrganizationApiController::class, 'cancelEvent']);
    Route::get('/delete-event/{id}', [OrganizationApiController::class, 'deleteEvent']);
    Route::get('/eventDetail/{id}', [OrganizationApiController::class, 'eventDetail']);
    Route::get('/event-guestList/{id}', [OrganizationApiController::class, 'eventGuestList']); //// aa devani 6e
    Route::get('/orderDetail/{id}', [OrganizationApiController::class, 'orderDetail']);
    Route::get('/event-tickets/{id}', [OrganizationApiController::class, 'eventTickets']);
    Route::post('/add-ticket', [OrganizationApiController::class, 'addTicket']);
    Route::post('/edit-ticket', [OrganizationApiController::class, 'editTicket']);
    Route::get('/delete-ticket/{id}', [OrganizationApiController::class, 'deleteTicket']);
    Route::get('/ticketDetail/{id}', [OrganizationApiController::class, 'ticketDetail']);
    Route::get('/category', [OrganizationApiController::class, 'allCategory']);
    Route::get('/view-tax', [OrganizationApiController::class, 'viewTax']);
    Route::post('/add-tax', [OrganizationApiController::class, 'addTax']);
    Route::post('/edit-tax', [OrganizationApiController::class, 'editTax']);
    Route::get('/delete-tax/{id}', [OrganizationApiController::class, 'deleteTax']);
    Route::get('/change-status-tax/{id}/{status}', [OrganizationApiController::class, 'changeStatusTax']);
    Route::get('/taxDetail/{id}', [OrganizationApiController::class, 'taxDetail']);
    Route::get('/faq', [OrganizationApiController::class, 'viewFaq']);
    Route::post('/add-feedback', [OrganizationApiController::class, 'addFeedback']);
    Route::post('/change-password', [OrganizationApiController::class, 'changePassword']);
    Route::post('/edit-profile', [OrganizationApiController::class, 'editProfile']);
    Route::get('/followers', [OrganizationApiController::class, 'followers']);
    Route::get('/notifications', [OrganizationApiController::class, 'notifications']);
    Route::get('/order-delete/{id}', [OrganizationApiController::class, 'deleteOrder']);
    Route::post('/change-profile-image', [OrganizationApiController::class, 'editImage']);
    Route::get('/coupon-event', [OrganizationApiController::class, 'couponEvent']);
    Route::get('/coupons', [OrganizationApiController::class, 'coupons']);
    Route::post('/add-coupon', [OrganizationApiController::class, 'addCoupon']);
    Route::post('/edit-coupon', [OrganizationApiController::class, 'editCoupon']);
    Route::get('/couponDetail/{id}', [OrganizationApiController::class, 'couponDetail']);
    Route::get('/delete-coupon/{id}', [OrganizationApiController::class, 'deleteCoupon']);
    Route::get('/clear-notification', [OrganizationApiController::class, 'clearNotification']);
    Route::post('/remove-gallery', [OrganizationApiController::class, 'removeGalleryImage']);
    Route::post('/add-gallery-image', [OrganizationApiController::class, 'addImageGallery']);

});

// scanner

Route::post('/scanner/login', [ScannerApiController::class, 'scannerLogin']);
Route::post('/scanner/forget-password', [ScannerApiController::class, 'forgetPassword']);
Route::get('/scanner/setting', [ScannerApiController::class, 'scannerSetting']);
Route::get('/scanner/events', [ScannerApiController::class, 'events']);
Route::group(['prefix' => 'scanner'], function () {
    Route::get('/single-order/{id}', [ScannerApiController::class, 'singleOrder']);
    
    Route::get('/event-detail/{id}', [ScannerApiController::class, 'eventDetail']);
    Route::get('/event-users/{id}', [ScannerApiController::class, 'eventUsers']);
    Route::get('/profile', [ScannerApiController::class, 'profile']);
    Route::post('/edit-profile', [ScannerApiController::class, 'editProfile']);
    Route::get('/scan-ticket/{id}/{event_id}', [ScannerApiController::class, 'scanTicket']);
    Route::post('/change-password', [ScannerApiController::class, 'changePassword']);
});
