<?php

use App\Http\Controllers\Organizer\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::group(["middleware" => ["guest"]],function (){
    Route::get("login", [LoginController::class, "showLoginForm"])->name("login");
    Route::post("login", [LoginController::class, "login"]);

});


   // Order routes      
   Route::any('/orders', [OrderController::class, 'index']);
   Route::get('/orders/{order_id}/{id}', [OrderController::class, 'show']);
   Route::get('/order-invoice/{id}', [OrderController::class, 'orderInvoice']);
   Route::post('/order/changestatus', [OrderController::class, 'changeStatus']);
   Route::post('/order/changepaymentstatus', [OrderController::class, 'changePaymentStatus']);
   Route::get('/user-review', [OrderController::class, 'userReview']);
   Route::get('/event-review', [OrderController::class, 'eventReports']);
   Route::delete('/order/{id}', [OrderController::class, 'delete']);
   Route::get('/event-reports', [OrderController::class, 'eventReports']);
   Route::get('/change-review-status/{id}', [OrderController::class, 'changeReviewStatus']);
   Route::get('/delete-review/{id}', [OrderController::class, 'deleteReview']);
   // Order routes 
