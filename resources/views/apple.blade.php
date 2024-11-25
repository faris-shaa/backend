<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apple Pay Integration Demo</title>
</head>
<body>
    <!-- Apple Pay Button -->
    <div id="apple-pay-button"></div>

    <!-- Your Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check for Apple Pay availability
            if (window.ApplePaySession && ApplePaySession.canMakePayments()) {
                const applePayButton = document.getElementById('apple-pay-button');
                applePayButton.innerHTML = '<button id="payButton" style="background-color: black; color: white; border-radius: 5px; padding: 10px; font-size: 16px;">Pay with Apple Pay</button>';

                // Event listener for the Apple Pay button
                document.getElementById('payButton').addEventListener('click', function () {
                    const paymentRequest = {
                        countryCode: 'US',
                        currencyCode: 'USD',
                        supportedNetworks: ['visa', 'masterCard', 'amex'],
                        merchantCapabilities: ['supports3DS'],
                        total: {
                            label: 'Your Merchant Name',
                            amount: '0.11'
                        }
                    };

                    const session = new ApplePaySession(3, paymentRequest);

                    // Merchant validation
                    session.onvalidatemerchant = function (event) {
                        const validationURL = event.validationURL;
                        fetch('/validate-merchant', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ validationURL: validationURL })
                        })
                        .then(response => response.json())
                        .then(data => {
                            session.completeMerchantValidation(data);
                        })
                        .catch(error => console.error('Error during merchant validation:', error));
                    };

                    // Payment authorization
                    session.onpaymentauthorized = function (event) {
                        const payment = event.payment;
                        fetch('/process-payment', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ token: payment.token.paymentData })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                session.completePayment(ApplePaySession.STATUS_SUCCESS);
                                alert('Payment successful!');
                            } else {
                                session.completePayment(ApplePaySession.STATUS_FAILURE);
                                alert('Payment failed.');
                            }
                        })
                        .catch(error => {
                            session.completePayment(ApplePaySession.STATUS_FAILURE);
                            console.error('Error processing payment:', error);
                        });
                    };

                    session.begin();
                });
            } else {
                console.log('Apple Pay is not available on this device/browser.');
            }
        });
    </script>
</body>
</html>
