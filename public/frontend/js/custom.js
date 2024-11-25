"use strict";
var base_url = $("#base_url").val();
var cur = $("#currency").val();
var language = document.getElementById('lang').value;
console.log(language, " language");
var translations = [];
var translate_month = [];
if (language == "عربي") {
    translations.Print = "طباعة";
    translations.Excel = "إكسل";
    translations.CSV = "ملف ";
    translations.PDF = "ملف ";
    translations.NOEVENTSFOUND = "لم يتم العثور على أحداث ";
    translate_month.January = "يناير  ";
    translate_month.February = "فبراير  ";
    translate_month.March = "مارس  ";
    translate_month.April = "أبريل  ";
    translate_month.May = "مايو  ";
    translate_month.June = "يونيو  ";
    translate_month.July = "يوليو  ";
    translate_month.August = "أغسطس  ";
    translate_month.September = "سبتمبر  ";
    translate_month.October = "أكتوبر  ";
    translate_month.November = "نوفمبر  ";
    translate_month.December = "ديسمبر  ";
}
if (language == "English") {
    translations.Print = "Print";
    translations.Excel = "Excel";
    translations.CSV = "CSV";
    translations.PDF = "PDF ";
    translations.NOEVENTSFOUND = "No Events Found ";
    translate_month.January = "January ";
    translate_month.February = "February ";
    translate_month.March = "March ";
    translate_month.April = "April ";
    translate_month.May = "May ";
    translate_month.June = "June ";
    translate_month.July = "July ";
    translate_month.August = "August ";
    translate_month.September = "September ";
    translate_month.October = "October ";
    translate_month.November = "November ";
    translate_month.December = "December ";
}
$(".lds-ripple").fadeOut(1500, function () {
    $("#app").animate(
        {
            opacity: 1,
        },
        700
    );
});
$(document).ready(function () {
    var url = window.location.href;
    var id = url.substring(url.lastIndexOf("#") + 1);
    if (id == "tickets") {
        $("#tickets").load();
    }

    $(".select2").select2(
        {
            dropdownAutoWidth: true,
            width: 'style' // Ensures the dropdown inherits the width from its container
        }
    );
    var date_language = 'en';
    if (language == "عربي") {
        date_language = 'ar';
    }
    if ($($("#date")).length) {
        $("#date").flatpickr({
            minDate: "today",
            dateFormat: "Y-m-d",
            locale: date_language,
            inline: true,

        });
    }

    var proQty = $(".pro-qty");
    var price = $("#ticket_price").val();
    var tax_total = $("#tax_total").val();
    // var amount_type = $('.amount_type').val();

    var total = 0;
    proQty.on("click", ".qtybtn", function () {
        var $button = $(this);
        var currency_code = $("#currency_code").val();
        var tpo = parseInt($("#tpo").val());
        var available = parseInt($("#available").val());
        var oldValue = $button.parent().find("input").val();
        if ($button.hasClass("inc")) {
            if (oldValue < tpo && oldValue < available) {
                var newVal = parseFloat(oldValue) + 1;
            } else {
                newVal = oldValue;
                $('#quantityMsg').show();
                $('#quantityMsg').text('Max Limit Reached')
            }
        } else {
            if (oldValue > 1) {
                var newVal = parseFloat(oldValue) - 1;
                $('#quantityMsg').hide();
            } else {
                newVal = 1;
            }
        }
        $('.payments input[type=radio][name="payment_type"]').prop(
            "checked",
            false
        );
        $button.parent().find("input").val(newVal);
        $(".event-middle .qty").html(newVal);
        // total = parseInt(parseInt(newVal * price) + parseInt(tax_total));
        var x = parseFloat(newVal * price);
        /*var tax_data = $(".tax_data").val();
        const obj = JSON.parse(tax_data);
        var totalTaxOfAmountType = 0;
        var totalTaxOfPercentageType = 0;
        $(".taxes").html("");
        obj.forEach((element) => {
            if (element["amount_type"] == "percentage") {
                var per_price = parseFloat(
                    (element["price"] * x) / 100
                ).toFixed(2);
                totalTaxOfPercentageType = parseFloat(
                    totalTaxOfPercentageType +
                        parseFloat((element["price"] * price) / 100).toFixed(2)
                ).toFixed(2);
                $(".taxes").append(
                    '<div class="flex justify-between">' +
                        '<p class="font-poppins font-normal text-lg leading-7 text-gray-200 ">' +
                        element["name"] +
                        " (" +
                        element["price"] +
                        "%)  " +
                        "</p>" +
                        '<p class="font-poppins font-medium text-lg leading-7 text-gray-300">' +
                        cur +
                        per_price +
                        "</p>"
                );
            }
            if (element["amount_type"] == "price") {
                totalTaxOfAmountType = totalTaxOfAmountType + element["price"];
                var per_price = element["price"];
                $(".taxes").append(
                    '<div class="flex justify-between">' +
                        '<p class="font-poppins font-normal text-lg leading-7 text-gray-200 ">' +
                        element["name"] +
                        "</p>" +
                        '<p class="font-poppins font-medium text-lg leading-7 text-gray-300">' +
                        cur +
                        per_price +
                        "</p>"
                );
            }
        });*/

        /*var totalPersTax =
            (parseFloat($("#totalPersTax").val()) * newVal * price) / 100;
        var totalAmountTax = parseFloat($("#totalAmountTax").val());
        tax_total = totalPersTax + totalAmountTax;
        total = parseFloat(price) * newVal + parseFloat(tax_total);
        $(".totaltax").text(cur + tax_total);
        $(".subtotal").text(cur + total.toFixed(2));
        $(".add_ticket").val(x);
        $("#tax_total").val(tax_total);

        $(".event-total .total").html(cur + total);
        $("#payment").val(total);
        if (currency_code == "USD" || currency_code == "EUR") {
            total = total * 100;
        }
        $("#stripe_payment").val(total);
        $("#apply").prop("disabled", false);
        $(".coupon_code").removeAttr("readonly");*/
    });

    $("#apply").on("click", function () {
        $(".couponerror").html("");
        var p = $(".coupon_code").val();
        if ($("#coupon_code").val() != "") {
            var currency = $("#currency").val();
            $.ajax({
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                url: base_url + "/applyCoupon",
                data: {
                    coupon_code: $("input[name=coupon_code]").val(),
                    total: $("input[name=ticket_total]").val(),
                    event_id: $("input[name=event_id]").val(),
                },

                success: function (result) {
                    if (result.success == true) {
                        var stripeamount =
                            parseFloat(result.total_price).toFixed(2) * 100;
                        $("#stripe_payment").val(stripeamount);
                        $("#payment").val(
                            parseFloat(result.payment).toFixed(2)
                        );
                        $(".payment").text(
                            currency + parseFloat(result.payment).toFixed(2)
                        );

                        // $(".subtotal").text(
                        //     currency + parseFloat(result.total_price).toFixed(2)
                        // );
                        // $("#subtotal").val(
                        //     parseFloat(result.total_price).toFixed(2)
                        // );

                        $(".ticket-price-amount").text(
                            currency + parseFloat(result.total_price).toFixed(2)
                        );
                        $("#ticket-price-amount").val(
                            parseFloat(result.total_price).toFixed(2)
                        );

                        $(".tax_total").text(
                            currency + parseFloat(result.total_tax).toFixed(2)
                        );
                        $("#tax_total").val(
                            parseFloat(result.total_tax).toFixed(2)
                        );

                        $(".tax_total_price").text(
                            currency + parseFloat(result.total_tax).toFixed(2)
                        );




                        $(".discount").text(
                            currency +
                            parseFloat(result.payableamount).toFixed(2)
                        );
                        $("#coupon_discount").val(
                            parseFloat(result.discount).toFixed(2)
                        );
                        $("#coupon_id").val(result.coupon_id);
                        $(".coupon_id").text(result.coupon_id);
                        $("#apply").prop("disabled", true);
                        $(".coupon_code").attr("readonly", true);
                        if (result.coupon_type == 0) {
                            $("#discount_type").text("%" + result.discount);
                        }
                        $('#tabby-button').attr('data-tabby-price', parseFloat(result.payment).toFixed(2));
                        initializeTabbyCard();
                    }
                    if (result.success == false) {
                        $(".couponerror").html(
                            '<div class="text-danger ml-2" >' +
                            result.message +
                            "</div>"
                        );
                    }
                },
            });
        }
    });
    $(document).on("click", ".btn-bio", function () {
        $(".bio-control").show(1000);
    });

    $(".bio-control").focusout(function () {
        var bio = this.value;
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            url: base_url + "/add-bio",
            data: {
                bio: bio,
            },
            success: function (result) {
                if (result.success == true) {
                    $(".bio-section").html(
                        '<p class="detail-bio">' + bio + "</p>"
                    );
                }
            },
            error: function (err) {
                console.log("err ", err);
            },
        });
    });

    $("#OpenImgUpload").on("click", function () {
        $("#imgUpload").trigger("click");
    });
    $("#imgUpload").change(function () {
        readURL(this);
    });

    $("#toggleCurrentPassword").on("click", function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        // var input = $($(this).attr("toggle"));
        var input = $("#current_password");
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    $("#toggleNewPassword").on("click", function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        // var input = $($(this).attr("toggle"));
        var input = $("#new_password");
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    $("#toggleConfirmPassword").on("click", function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        // var input = $($(this).attr("toggle"));
        var input = $("#confirm_password");
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    $(".event-data").on("click", function () {
        $(".event-data").removeClass("active");
        $(this).addClass("active");
        var id = $(this).attr("id").split("-")[1];
        $.ajax({
            type: "GET",
            url: base_url + "/getOrder/" + id,
            success: function (result) {
                if (result.success == true) {
                    if (result.data.event.type == "online") {
                        var type = "Online Event";
                    } else {
                        var type = result.data.event.address;
                    }
                    if (result.data.order_status == "Pending") {
                        var status = "badge-warning";
                    } else if (result.data.order_status == "Complete") {
                        var status = "badge-success";
                    } else if (result.data.order_status == "Cancel") {
                        var status = "badge-danger";
                    }
                    if (result.data.payment_status == 1) {
                        var payment_status_class = "badge-success";
                        var payment_status = "Paid";
                    }
                    if (result.data.payment_status == 0) {
                        var payment_status_class = "badge-warning";
                        var payment_status = "Waiting";
                    }
                    if (
                        (result.data.review == null &&
                            result.data.order_status == "Complete") ||
                        result.data.order_status == "Cancel"
                    ) {
                        var review_content =
                            '<div><button class="btn open-addReview"  data-toggle="modal" data-id="' +
                            result.data.id +
                            '" data-order="' +
                            result.data.order_id +
                            '"  data-target="#reviewModel"><i class="fa fa-star"></i></button><p>Review</p></div>';
                    } else {
                        var review_content = "";
                    }
                    if (result.data.review != null) {
                        var review_content = "";
                    }
                    var rating =
                        result.data.review != null
                            ? '<div class="rating order-rate"></div>'
                            : "";

                    var payment_token =
                        result.data.payment_token == null
                            ? "-"
                            : result.data.payment_token;

                    $(".single-order").html(
                        '<div class="single-order-top"></div><div class="order-bottom"></div>'
                    );
                    $(".single-order-top").append(
                        '<p class="text-light mb-0">' +
                        result.data.order_id +
                        "</p><h4>Booked on :" +
                        " " +
                        result.data.time +
                        '</h4> <span class="badge ' +
                        status +
                        '">' +
                        result.data.order_status +
                        "</span>" +
                        rating +
                        '<div class="" id="mycustomqrcode"></div>' +
                        '<div class="row mt-2"><div class="col-lg-2"><img class="w-100" src="' +
                        base_url +
                        "/images/upload/" +
                        result.data.event.image +
                        '">\
					</div><div class="col-5"><h6 class="mb-0">' +
                        result.data.event.name +
                        '</h6><p class="mb-0">By: ' +
                        result.data.organization.first_name +
                        " " +
                        result.data.organization.last_name +
                        '</p><p class="mb-0">' +
                        result.data.start_time +
                        ' to </p><p class="mb-0">' +
                        result.data.end_time +
                        '</p><p class="mb-0">' +
                        type +
                        '</p></div><div class="col-5 "> <div class="right-data text-center"><div><button class="btn" onclick="viewPayment()"><i class="fa fa-credit-card"></i></button><p>Payment</p></div>' +
                        review_content +
                        '<div>\
					<a class="btn" target="_blank" href="' +
                        base_url +
                        "/order-invoice-print/" +
                        result.data.id +
                        '"><i class="fa fa-print"></i></a><p>Print</p></div><div><a href="show-details/' +
                        result.data.id +
                        '" target="_blank" class="btn" > <i class="fa fa-ticket"></i> <p>Show</p></a></div> </div><div class="payment-data hide" ><p class="mb-0"><span>Payment Method : </span>' +
                        result.data.payment_type +
                        '</p><p class="mb-1"><span>Payment Token : </span>' +
                        payment_token +
                        '</p><span class="badge ' +
                        payment_status_class +
                        '">' +
                        payment_status +
                        "</span>  </div>  </div></div>"
                    );

                    // var qrcode = new QRCode(document.getElementById("mycustomqrcode"), result.data.order_id);

                    if (result.data.ticket.type == "free") {
                        $(".order-bottom").append(
                            '<div class="order-ticket-detail mb-4"><div><p>' +
                            result.data.ticket.name +
                            "</p></div><div> " +
                            result.data.quantity +
                            ' tickets</div></div><div class="order-total"> <p>Ticket Price</p><p> FREE</p></div><div class="order-total"> <p>Coupon discount</p><p> 0.00</p></div><div class="order-total"><p>Tax</p><p> 0.00</p></div><div class="order-total"> <h6>Total</h6><h6>FREE</h6></div>'
                        );
                    } else {
                        $(".order-bottom").append(
                            '<div class="order-ticket-detail mb-4"><div><p>' +
                            result.data.ticket.name +
                            "</p></div><div> " +
                            result.data.quantity +
                            " X " +
                            cur +
                            result.data.ticket.price +
                            '</div></div> <div class="taxes"></div> <div class="order-total"><p>Tax</p><p>(+) ' +
                            cur +
                            result.data.tax +
                            '</p></div> <div class="order-total"> <p>Ticket Price</p><p> ' +
                            cur +
                            result.data.ticket.price *
                            result.data.quantity +
                            '</p></div><div class="order-total"> <p>Coupon discount</p><p>(-) ' +
                            cur +
                            result.data.coupon_discount +
                            '</p></div><div class="order-total"> <h6>Total</h6><h6>' +
                            cur +
                            result.data.payment +
                            "</h6></div>"
                        );
                    }
                    result.data.maintax.forEach((element) => {
                        if (element.amount_type == "price") {
                            $(".taxes").append(
                                '<div class="order-total"><p>' +
                                element.name +
                                "</p><p>" +
                                element.price +
                                "</p></div>"
                            );
                        }
                        if (element.amount_type == "percentage") {
                            $(".taxes").append(
                                '<div class="order-total"><p>' +
                                element.name +
                                "&nbsp; &nbsp;( " +
                                element.price +
                                "% )" +
                                "</p><p >" +
                                (result.data.ticket.price *
                                    result.data.quantity *
                                    element.price) /
                                100 +
                                "</p></div>"
                            );
                        }
                    });

                    if (result.data.review != null) {
                        for (i = 1; i <= 5; i++) {
                            var active =
                                result.data.review.rate >= i ? "active" : "";
                            $(".single-order-top .order-rate").append(
                                '<i class="fa fa-star ' + active + ' mr-1"></i>'
                            );
                        }
                        $(".single-order-top .order-rate").append(
                            '<span class="ml-3 text-white">' +
                            result.data.review.message +
                            "</span>"
                        );
                    }
                }
            },
            error: function (err) {
                console.log("err ", err);
            },
        });
    });

    $(".chip-button").on("click", function () {
        var type = $(this).attr("id").split("-")[0];
        var id = $(this).attr("id").split("-")[1];
        window.location.replace(base_url + "/all-events");
    });

    $("#duration").change(function () {
        if (this.value == "date") {
            $(".date-section").removeClass("hidden");
            $(".date-section").addClass("visible");
        } else {
            $(".date-section").addClass("hidden");
        }
    });

    let preType = "";

    $(".payments input[type=radio][name=payment_type] ").on(
        "change",
        function () {
            var ticketDateInput = document.querySelector(
                'input[name="ticket_date"]'
            );
            var stripeRadio = document.querySelector(
                '.payments input[type=radio][name="payment_type"][value="STRIPE"]'
            );
            var paypalRadio = document.querySelector(
                '.payments input[type=radio][name="payment_type"][value="PAYPAL"]'
            );
            var razorRadio = document.querySelector(
                '.payments input[type=radio][name="payment_type"][value="RAZOR"]'
            );
            var flutterRadio = document.querySelector(
                '.payments input[type=radio][name="payment_type"][value="FLUTTERWAVE"]'
            );

            if (ticketDateInput && !ticketDateInput.value) {

                $(".ticket_date").text("The ticket date is required");
                $("#paypal-button-container").hide();
                $("#stripeform").hide();
                $("#form_submit").show();
                $(".payments input[type=radio][name=payment_type]").prop(
                    "checked",
                    false
                );
            } else if (stripeRadio && stripeRadio.checked) {
                stripeSession();
            } else if (paypalRadio && paypalRadio.checked) {
                $(".ticket_date").html("");
                $("#stripeform").hide();
                $("#form_submit").attr("disabled", true);
                $("#paypal-button-container").show();
            } else if (razorRadio && razorRadio.checked) {
                $(".ticket_date").html("");
                $("#stripeform").hide();
                $("#form_submit").hide();
                $("#paypal-button-container").hide();
                var razorpayOptions = {
                    key: $("#razor_key").val(),
                    amount: $("#payment").val() * 100,
                    name: "CodesCompanion",
                    description: "test",
                    capture: true,
                    image: "https://i.imgur.com/n5tjHFD.png",
                    handler: demoSuccessHandler,
                };

                window.r = new Razorpay(razorpayOptions);
                r.open();
            } else if (flutterRadio && flutterRadio.checked) {
                FlutterwaveCheckout({
                    public_key: $("input[name=flutterwave_key]").val(),
                    tx_ref:
                        Math.floor(Math.random() * (1000 - 9999 + 1)) + 9999,
                    amount: $("#payment").val(),
                    currency: $("input[name=currency_code]").val(),
                    payment_options: " ",
                    customer: {
                        email: $("input[name=email]").val(),
                        phone_number: $("input[name=phone]").val(),
                        name: $("input[name=name]").val(),
                    },
                    callback: function (data) {
                        if (data.status == "successful") {
                            $("#payment_token").val(data.transaction_id);
                            $("#form_submit").attr("disabled", false);
                            $("#form_submit").trigger("click");

                            $("#form_submit").attr("disabled", false);
                            $("input[name=payment_status]").val(1);
                            $("input[name=payment_token]").val(
                                data.transaction_id
                            );
                            $("input[name=payment_type]").val("FLUTTERWAVE");
                            document.getElementById("ticketorder").submit();
                            booking();
                        }
                    },
                    customizations: {
                        title: $("input[name=company_name]").val(),
                        description: "Doctor Appointment Booking",
                    },
                });
            }

            $("#paypal-button-container").html("");
            $(".paypal-button-section").hide();
            $(".stripe-form-section").hide();
            $(".stripe-form").html("");

            if (this.value == "PAYPAL") {
                $("#form_submit").attr("disabled", true);
                paypal_sdk
                    .Buttons({
                        createOrder: function (data, actions) {
                            return actions.order.create({
                                purchase_units: [
                                    {
                                        amount: {
                                            value: $("#payment").val(),
                                        },
                                    },
                                ],
                            });
                        },
                        onApprove: function (data, actions) {
                            return actions.order
                                .capture()
                                .then(function (details) {
                                    $("#payment_token").val(details.id);
                                    $("#form_submit").attr("disabled", false);

                                    var requestData = {
                                        payment: $("#payment").val(),
                                        payment_token: details.id,
                                        payment_type: "PAYPAL",
                                        ticket_id: $("#ticket_id").val(),

                                        event_id: $("#event_id").val(),
                                        current_url: $("#current_url").val(),
                                        coupon_code: $("#coupon_id").val(),
                                        tax: $("#tax_total").val(),
                                        quantity: $("#quantity").val(),
                                        ticket_date: $("#onetime").val(),
                                        selectedSeats:
                                            $("#selectedSeats").val(),
                                        selectedSeatsId:
                                            $("#selectedSeatsId").val(),
                                    };

                                    $.ajax({
                                        headers: {
                                            "X-CSRF-TOKEN": $(
                                                'meta[name="csrf-token"]'
                                            ).attr("content"),
                                        },
                                        url: "/createOrder",
                                        type: "POST",
                                        data: requestData,

                                        success: function (data) {
                                            if (data.success == true) {
                                                window.location.href =
                                                    "/my-tickets";
                                            } else {
                                                $("#stripe_message").text(
                                                    data.message
                                                );
                                                $("#stripe_message").show();
                                            }
                                        },
                                        error: function (data) {
                                            if (data.status === 422) {
                                                var errors =
                                                    data.responseJSON.errors;
                                                console.log(
                                                    errors.ticket_date[0]
                                                );
                                                $(".ticket_date").text(
                                                    errors.ticket_date[0]
                                                );
                                            }
                                        },
                                    });
                                });
                        },
                    })
                    .render("#paypal-button-container");

                $(".paypal-button-section").show(500);
            }
            if (this.value == "LOCAL") {
                $(".card-pay").hide();
                $("#form_submit").show();
                $("#form_submit").attr("disabled", false);
                $("#paypal-button-container").hide();
                $("#stripeform").hide();
                $("#form_submit").on("click", function () {
                    var requestData = {
                        payment: $("#payment").val(),
                        payment_token: null,
                        payment_type: "LOCAL",
                        ticket_id: $("#ticket_id").val(),
                        event_id: $("#event_id").val(),
                        current_url: $("#current_url").val(),
                        coupon_code: $("#coupon_id").val(),
                        tax: $("#tax_total").val(),
                        quantity: $("#quantity").val(),
                        ticket_date: $("#onetime").val(),
                        selectedSeats: $("#selectedSeats").val(),
                        selectedSeatsId: $("#selectedSeatsId").val(),
                    };
                    createOrder(requestData);
                });
            }
            if (this.value == "EDAFPAY") {

                $("#EDAFPAY_form_submit").show();
                $("#TABBY_form_submit").hide();
                $(".epay").hide();

                // $("#TABBY_form_submit").addClass('hidden');
                // $("#EDAFPAY_form_submit").removeClass('hidden');
                $(".stc").hide();

                $("#EDAFPAY_form_submit").attr("disabled", false);
                $("#paypal-button-container").hide();
                $("#stripeform").hide();
                $(".card-pay").show();
                $(".tabby").hide();
                if ($("#is-login").val() == 0) {

                    $("#login-error-payment").css("display", "block");

                    var event_id = $("#event-id").val();
                    var event_quantity = $("#event-quantity").val();
                    setTimeout(function () {
                        $("#registrationModal").modal("show");
                        //window.location.href = "https://ticketby.co/user/register?checkout=checkout/" + event_id +"?quantity="+event_quantity;
                    }, 3000);
                    //window.location.href = "https://ticketby.co/user/register?checkout=checkout/"+event_id;
                }
                else {

                    $("#login-error-payment").css("display", "none");

                }
                $("#EDAFPAY_form_submit").on("click", function () {
                    
                    var requestData = {
                        payment: $("#payment").val(),
                        payment_token: null,
                        payment_type: "EDAFPAY",
                        ticket_id: $("#ticket_id").val(),
                        slot_event_date: $("#slot-event-date").val(),
                        time_slot_id: $("#time_slot_id").val(),
                        event_id: $("#event_id").val(),
                        current_url: $("#current_url").val(),
                        coupon_code: $("#coupon_id").val(),
                        tax: $("#tax_total").val(),
                        quantity: $("#event-quantity").val(),
                        ticket_date: $("#onetime").val(),
                        selectedSeats: $("#selectedSeats").val(),
                        selectedSeatsId: $("#selectedSeatsId").val(),
                        ticket_ids: $("#ticket_ids").val(),
                        ticket_quantities: $("#ticket_quantities").val(),
                        month: $("#month").val(),
                        year: $("#year").val(),
                        cvv: $("#cvc").val(),
                        holder: $("#cd-holder-input").val(),
                        card_input: $("#cd-number-1").val() + $("#cd-number-2").val() + $("#cd-number-3").val() + $("#cd-number-4").val(),
                    };
                    console.log(month);
                    console.log(year);
                    createOrder(requestData);
                });
            }
            if (this.value == "EPAY") {
                $("#EDAFPAY_form_submit").hide();
                $("#TABBY_form_submit").hide();
                $("#EPAY_form_submit").show();
                $(".stc").hide();

                $(".epay").show();
                $(".tabby").hide();
                $(".card-pay").hide();

            }
            if (this.value == "STC") {
                $(".stc").show();
                $("#EDAFPAY_form_submit").hide();
                $("#TABBY_form_submit").hide();
                $("#EPAY_form_submit").hide();
                $("#STC_form_submit").show();
                $(".tabby").hide();
                $(".card-pay").hide();
                $(".epay").hide();

            }
            if (this.value == "TABBY") {

                // $("#TABBY_form_submit").show();
                $("#EDAFPAY_form_submit").hide();
                $(".epay").hide();

                $("#TABBY_form_submit").show();
                $("#TABBY_form_submit").removeClass('hidden');
                $("#EDAFPAY_form_submit").addClass('hidden');
                $(".tabby").show();
                $(".card-pay").hide();

                $("#TABBY_form_submit").attr("disabled", false);
                $("#paypal-button-container").hide();
                $("#stripeform").hide();
                if ($("#is-login").val() == 0) {

                    $("#login-error-payment").css("display", "block");

                    var event_id = $("#event-id").val();
                    // window.location.href = "https://ticketby.co/user/register?checkout=checkout/"+event_id;
                    setTimeout(function () {
                        window.location.href = "https://ticketby.co/user/register?checkout=checkout/" + event_id;
                    }, 3000);
                }
                else {

                    $("#login-error-payment").css("display", "none");

                }
                $("#TABBY_form_submit").on("click", function () {
                    var requestData = {
                        payment: $("#payment").val(),
                        payment_token: null,
                        payment_type: "TABBY",
                        ticket_id: $("#ticket_id").val(),
                        slot_event_date: $("#slot-event-date").val(),
                        time_slot_id: $("#time_slot_id").val(),
                        event_id: $("#event_id").val(),
                        current_url: $("#current_url").val(),
                        coupon_code: $("#coupon_id").val(),
                        tax: $("#tax_total").val(),
                        quantity: $("#event-quantity").val(),
                        ticket_date: $("#onetime").val(),
                        selectedSeats: $("#selectedSeats").val(),
                        selectedSeatsId: $("#selectedSeatsId").val(),
                        ticket_ids: $("#ticket_ids").val(),
                        ticket_quantities: $("#ticket_quantities").val(),
                    };
                    createOrder(requestData);
                });
            }
            if (this.value == "TAMARA") {   

                 // $("#TABBY_form_submit").show();
                $("#EDAFPAY_form_submit").hide();
                $(".epay").hide();
                
                $("#TABBY_form_submit").show();
                $("#TABBY_form_submit").removeClass('hidden');
                $("#EDAFPAY_form_submit").addClass('hidden');
                $(".tabby").hide();
                $(".card-pay").hide();

                $("#TABBY_form_submit").attr("disabled", false);
                $("#paypal-button-container").hide();
                $("#stripeform").hide();
                if ($("#is-login").val() == 0) {

                    $("#login-error-payment").css("display", "block");

                    var event_id = $("#event-id").val();
                    // window.location.href = "https://ticketby.co/user/register?checkout=checkout/"+event_id;
                    setTimeout(function () {
                        window.location.href = "https://ticketby.co/user/register?checkout=checkout/" + event_id;
                    }, 3000);
                }
                else {

                    $("#login-error-payment").css("display", "none");

                }
                $("#TABBY_form_submit").on("click", function () {
                    var requestData = {
                        payment: $("#payment").val(),
                        payment_token: null,
                        payment_type: "TAMARA",
                        ticket_id: $("#ticket_id").val(),
                        slot_event_date: $("#slot-event-date").val(),
                        time_slot_id: $("#time_slot_id").val(),
                        event_id: $("#event_id").val(),
                        current_url: $("#current_url").val(),
                        coupon_code: $("#coupon_id").val(),
                        tax: $("#tax_total").val(),
                        quantity: $("#event-quantity").val(),
                        ticket_date: $("#onetime").val(),
                        selectedSeats: $("#selectedSeats").val(),
                        selectedSeatsId: $("#selectedSeatsId").val(),
                        ticket_ids: $("#ticket_ids").val(),
                        ticket_quantities: $("#ticket_quantities").val(),
                    };
                    createOrder(requestData);
                });

               /* $("#form_submit").show();
                $(".tamara").show();
                $("#form_submit").attr("disabled", false);
                $("#paypal-button-container").hide();
                $("#stripeform").hide();
                $("#form_submit").on("click", function () {
                    var requestData = {
                        payment: $("#payment").val(),
                        payment_token: null,
                        payment_type: "TAMARA",
                        ticket_id: $("#ticket_id").val(),

                        event_id: $("#event_id").val(),
                        current_url: $("#current_url").val(),
                        coupon_code: $("#coupon_id").val(),
                        tax: $("#tax_total").val(),
                        quantity: $("#quantity").val(),
                        ticket_date: $("#onetime").val(),
                        selectedSeats: $("#selectedSeats").val(),
                        selectedSeatsId: $("#selectedSeatsId").val(),
                    };
                    createOrder(requestData);
                });*/
            }
            if (this.value == "CARD") {

                $("#form_submit").show();
                $(".card-pay").show();
                $("#form_submit").attr("disabled", false);
                $("#paypal-button-container").hide();
                $("#stripeform").hide();
                $("#form_submit").on("click", function () {
                    var requestData = {
                        payment: $("#payment").val(),
                        payment_token: null,
                        payment_type: "EDAFPAY",
                        ticket_id: $("#ticket_id").val(),

                        event_id: $("#event_id").val(),
                        current_url: $("#current_url").val(),
                        coupon_code: $("#coupon_id").val(),
                        tax: $("#tax_total").val(),
                        quantity: $("#quantity").val(),
                        ticket_date: $("#onetime").val(),
                        selectedSeats: $("#selectedSeats").val(),
                        selectedSeatsId: $("#selectedSeatsId").val(),
                        month: $("#month").val(),
                        year: $("#year").val(),
                        cvv: $("#cvv").val(),
                        holder: $("#cd-holder-input").val(),
                        card_input: $("#cd-number-1").val() + $("#cd-number-2").val() + $("#cd-number-3").val() + $("#cd-number-4").val(),
                    };
                    createOrder(requestData);
                });
            }
            if (this.value == "FREE") {
                $("#form_submit").show();
                $("#paypal-button-container").hide();
                $("#stripeform").hide();
                $("#form_submit").on("click", function () {
                    var requestData = {
                        payment: 0,
                        payment_token: null,
                        payment_type: "FREE",
                        ticket_id: $("#ticket_id").val(),

                        event_id: $("#event_id").val(),
                        current_url: $("#current_url").val(),
                        coupon_code: $("#coupon_id").val(),
                        tax: $("#tax_total").val(),
                        quantity: $("#quantity").val(),
                        ticket_date: $("#onetime").val(),
                        selectedSeats: $("#selectedSeats").val(),
                        selectedSeatsId: $("#selectedSeatsId").val(),
                    };
                    createOrder(requestData);
                });
            }
            preType = this.value;
            if (this.value == "wallet") {
                $("#form_submit").show();
                $("#paypal-button-container").hide();
                $("#stripeform").hide();
                $("#form_submit").on("click", function () {
                    var requestData = {
                        payment: $("#payment").val(),
                        payment_token: null,
                        payment_type: "WALLET",
                        ticket_id: $("#ticket_id").val(),

                        event_id: $("#event_id").val(),
                        current_url: $("#current_url").val(),
                        coupon_code: $("#coupon_id").val(),
                        tax: $("#tax_total").val(),
                        quantity: $("#quantity").val(),
                        ticket_date: $("#onetime").val(),
                        selectedSeats: $("#selectedSeats").val(),
                        selectedSeatsId: $("#selectedSeatsId").val(),
                    };
                    createOrder(requestData);
                });
            }
        }
    );

    // Create Order
    function createOrder(requestData) {

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "/createOrder",
            type: "POST",
            data: requestData,
            beforeSend: function () {
                $("#formtext").hide(100);
                $("#formloader").show(100);
            },
            complete: function () {
                $("#formloader").hide(100);
                $("#formtext").show(100);
            },
            success: function (data) {

                if (data.status == 201) {
                    window.location.href = data.url;
                }
                if (data.status == 200) {

                    var form = new FormData();
                    form.append("body", data.body);
                    form.append("url", data.url);
                    console.log("data", "https://ticketby.co/order/edfapay?body=" + data.body + "&url=" + data.url);
                    window.location.href = "https://ticketby.co/order/edfapay?body=" + data.body + "&url=" + data.url;
                    // var settings = {
                    //   "url": "https://ticketby.co/order/edfapay",
                    //   "method": "POST",
                    //   "timeout": 0,
                    //   "headers": {
                    //     "Cookie": "PHPSESSID=90dqavrbliq73tf0jptf7joga3",
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    //   },
                    //   "processData": false,
                    //   "mimeType": "multipart/form-data",
                    //   "contentType": false,
                    //   "data": form
                    // };

                    // $.ajax(settings).done(function (response) {

                    //   $('html').html(response.html);
                    // });

                }
                if (data.status != 200 && data.status != 201) {
                    $(".payment-error").show();
                    $(".error-message").html(data.error_message);

                }
                if (data.success == true) {
                    window.location.href = "/my-tickets";
                } else {
                    $("#stripe_message").text(data.message);
                    $("#stripe_message").show();
                }
            },
            error: function (data) {
                if (data.status === 422) {
                    var errors = data.responseJSON.errors;
                    console.log(errors.ticket_date[0]);
                    $(".ticket_date").text(errors.ticket_date[0]);
                }
            },
        });
    }

    $("#imageUploadForm").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            url: base_url + "/upload-profile-image",
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result) {
                    $("#profileDropdown .header-profile-img").attr(
                        "src",
                        base_url + "/images/upload/" + result.data
                    );
                    window.location.reload();
                }
            },
            error: function (err) {
                console.log(err);
            },
        });
    });

    $("#onetime").on("change", function () {
        var lastDate = new Date($("#onetime").data("date"));
        var selectedDate = new Date($(this).val());
        if (selectedDate > lastDate) {
            var formattedLastDate = formatDate(lastDate);
            $(this).val(formattedLastDate);
        }
    });

    function formatDate(date) {
        var year = date.getFullYear();
        var month = String(date.getMonth() + 1).padStart(2, "0");
        var day = String(date.getDate()).padStart(2, "0");
        return `${year}-${month}-${day}`;
    }
});

function addFavorite(id, type) {
    $.ajax({
        type: "GET",
        url: base_url + "/add-favorite/" + id + "/" + type,
        success: function (result) {
            if (result.success == true) {
                setTimeout(() => {
                    window.location.reload();
                }, 800);
            }
        },
        error: function (err) {
            console.log("err ", err);
        },
    });
}

function demoSuccessHandler(transaction) {
    $("#payment_token").val(transaction.razorpay_payment_id);
    $("#form_submit").attr("disabled", false);
    var requestData = {
        payment: $("#payment").val(),
        payment_token: transaction.razorpay_payment_id,
        payment_type: "RAZORPAY",
        ticket_id: $("#ticket_id").val(),

        event_id: $("#event_id").val(),
        current_url: $("#current_url").val(),
        coupon_code: $("#coupon_id").val(),
        tax: $("#tax_total").val(),
        quantity: $("#quantity").val(),
        ticket_date: $("#onetime").val(),
        selectedSeats: $("#selectedSeats").val(),
        selectedSeatsId: $("#selectedSeatsId").val(),
    };

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: "/createOrder",
        type: "POST",
        data: requestData,

        success: function (data) {
            if (data.success == true) {
                $("#stripe_message").text(data.message);
                window.location.href = "/my-tickets";
            } else {
                $("#stripe_message").text(data.message);
                $("#stripe_message").show();
            }
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON.errors;
                console.log(errors.ticket_date[0]);
                $(".ticket_date").text(errors.ticket_date[0]);
            }
        },
    });
}

function viewPayment() {
    $(".payment-data").slideToggle();
}

function addRate(id) {
    $(".rating i").css("color", "#d2d2d2");
    $('#reviewModel input[name="rate"]').val(id);
    for (let i = 1; i <= id; i++) {
        $(".rating #rate-" + i).css("color", "#fec009");
    }
    $("#rate").val(id);
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $("#imagePreview").attr("src", e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
    $("#imageUploadForm").submit();
}

function follow(id) {
    $.ajax({
        type: "GET",
        url: base_url + "/add-followList/" + id,
        success: function (result) {
            if (result.success == true) {
                setTimeout(() => {
                    window.location.reload();
                }, 800);
            }
        },
        error: function (err) {
            console.log("err ", err);
        },
    });
}

$("#onetime").flatpickr({
    minDate: "today",
    dateFormat: "Y-m-d",
});
$("#start_time,#end_time").flatpickr({
    minDate: "today",
    dateFormat: "Y-m-d",
});
function imagegallery(params) {
    var origin = window.location.origin;
    $("#eventimage").attr("src", origin + "/images/upload/" + params);
}

function stripeSession() {
    var stripe = Stripe($("#stripePublicKey").val());
    var origin = window.location.origin;
    var url = origin + "/user/stripe/create-session";
    $.ajax({
        url: url,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            payment: $("#payment").val(),
            payment_type: "STRIPE",
            ticket_id: $("#ticket_id").val(),

            event_id: $("#event_id").val(),
            current_url: $("#current_url").val(),
            coupon_code: $("#coupon_id").val(),
            tax: $("#tax_total").val(),
            quantity: $("#quantity").val(),
            ticket_date: $("#onetime").val(),
            selectedSeats: $("#selectedSeats").val(),
            selectedSeatsId: $("#selectedSeatsId").val(),
        },
        dataType: "json",
        success: function (session) {
            stripe
                .redirectToCheckout({
                    sessionId: session.id,
                })
                .then(function (result) {
                    console.log(result);
                    if (result.error) {
                        alert(result.error.message);
                    }
                });
        },
        error: function (error) {
            console.error("Error:", error);
        },
    });
}

// Wallet
$(document).ready(function () {
    // PayPal
    $("#paypalWallet").on("click", function () {
        paypal_sdk
            .Buttons({
                createOrder: function (data, actions) {
                    return actions.order.create({
                        purchase_units: [
                            {
                                amount: {
                                    value: $("#amount").val(),
                                },
                            },
                        ],
                    });
                },
                onApprove: function (data, actions) {
                    return actions.order.capture().then(function (details) {
                        var token = details.id;
                        var amount = $("#amount").val();
                        var payment_type = "PAYPAL";
                        addMoneyWallet(token, amount, payment_type);
                    });
                },
            })
            .render("#paypal-button-container");

        $(".paypal-button-section").show(500);
    });
    // Razorpay
    $("#razorpayWallet").on("click", function () {
        var razorpayOptions = {
            key: $("#razor_key").val(),
            amount: $("#amount").val() * 100,
            name: "Add to Wallet",
            description: "",
            capture: true,
            image: "https://i.imgur.com/n5tjHFD.png",
            handler: demoSuccessHandlerWallet,
        };
        window.r = new Razorpay(razorpayOptions);
        r.open();
    });
    function demoSuccessHandlerWallet(transaction) {
        var amount = $("#amount").val();
        var token = transaction.razorpay_payment_id;
        var payment_type = "RAZORPAY";
        addMoneyWallet(token, amount, payment_type);
    }
    // Stripe
    $("#stripeWallet").on("click", function () {
        var stripe = Stripe($("#stripePublicKey").val());
        var url = "/user/wallet/stripe/create-session";
        var currency = $("#walletCur").val();
        $.ajax({
            url: url,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                amount: $("#amount").val(),
                payment_type: "STRIPE",
                currency: currency,
            },
            dataType: "json",
            success: function (session) {
                stripe
                    .redirectToCheckout({
                        sessionId: session.id,
                    })
                    .then(function (result) {
                        console.log(result);
                        if (result.error) {
                            alert(result.error.message);
                        }
                    });
            },
            error: function (error) {
                console.error("Error:", error);
            },
        });
    });
    // Flutterwave
    $("#flutterwaveWallet").on("click", function () {
        FlutterwaveCheckout({
            public_key: $("#flutterwave_key").val(),
            tx_ref: Math.floor(Math.random() * (1000 - 9999 + 1)) + 9999,
            amount: $("#amount").val(),
            currency: $("#walletCur").val(),
            payment_options: "card, banktransfer, ussd",
            customer: {
                email: $("input[name=email]").val(),
            },
            callback: function (data) {
                if (data.status == "successful") {
                    booking();
                    $("#payment_token").val(data.transaction_id);
                    var token = data.transaction_id;
                    var amount = $("#amount").val();
                    var payment_type = "FLUTTERWAVE";
                    addMoneyWallet(token, amount, payment_type);
                }
            },
            customizations: {
                title: "Add to Wallet",
                description: "Payment for adding funds to wallet",
            },
        });
    });
    function addMoneyWallet(token, amount, payment_type) {
        var currency = $("#walletCur").val();
        $.ajax({
            type: "post",
            url: "/user/deposite",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                token: token,
                amount: amount,
                payment_type: payment_type,
                currency: currency,
            },
            dataType: "JSON",
            beforeSend: function () { },
            complete: function () { },
            success: function (response) {
                if (response.success == true) {
                    var newPath = "/user/wallet";
                    window.location.href = window.location.origin + newPath;
                }
            },
            error: function (response) { },
        });
    }

    $("#paymentbtn").on("click", function () {
        var min = 5;
        var input = $("#amount").val(); // Get the value directly

        if (input < min) {
            alert("Please enter a value greater than or equal to " + min);
        } else {
            $("#amount")[0].setCustomValidity("");
            $("#amount").prop("disabled", true);
            $(".payments").show(200);
        }
    });
});

// Carasoule
$(document).ready(function () {
    $('.your-carousel').slick({
        infinite: true, // Enable infinite loop
        speed: 300, // Transition speed
        slidesToShow: 1, // Number of slides to show at a time
        slidesToScroll: 1, // Number of slides to scroll at a time
        autoplay: true, // Auto play option
        autoplaySpeed: 5000,// Auto play speed in milliseconds
        dots: false, // Enable dots navigation
        arrows: false, // Enable arrows navigation
    });

    // Custom navigation button functionality
    $('.hs-carousel-prev').click(function () {
        $('.your-carousel').slick('slickPrev');
    });

    $('.hs-carousel-next').click(function () {
        $('.your-carousel').slick('slickNext');
    });
});
