<?php
require 'vendor/autoload.php';
if(1)
{
//    isset($_POST['authKey'])&& ($_POST['authKey']=='abc')
    $stripe = new \Stripe\StripeClient('sk_test_51OsiPhJSl4kaFANTv2e7tiGqrAWMTuwfpotd45nWI6YeCbboGeLAykIfpfv4MycQTuHuXIto5r32SFZxaDgnKEYf00MBzOYfdE'); // secret key

// Use an existing Customer ID if this is a returning customer.
    $customer = $stripe->customers->create([
        'name' => "HoangUyen",
        'address' => [
            'line1' => 'demo address',
            'postal_code' => '738933',
            'city' => 'NewYork',
            'state' => 'NY',
            'country' => 'US',
        ]
    ]);
    $ephemeralKey = $stripe->ephemeralKeys->create([
        'customer' => $customer->id,
    ], [
        'stripe_version' => '2023-10-16',
    ]);
    $paymentIntent = $stripe->paymentIntents->create([
        'amount' => 1099,
        'currency' => 'usd',
        'description' => 'Payment for MangaPlus App',
        'customer' => $customer->id,
        // In the latest version of the API, specifying the `automatic_payment_methods` parameter
        // is optional because Stripe enables its functionality by default.
        'automatic_payment_methods' => [
            'enabled' => 'true',
        ],
    ]);

    echo json_encode(
        [
            'paymentIntent' => $paymentIntent->client_secret,
            'ephemeralKey' => $ephemeralKey->secret,
            'customer' => $customer->id,
            'publishableKey' => 'pk_test_51OsiPhJSl4kaFANTuqY3Tw9yPOIqUMaPwkFbuKvbLjCeQC0njrHdH810sCyqbcK2Im1IZOK9euVvSu8gRjZcCEgE002RvYJJ5h'
        ]
    );
    http_response_code(200);

}
else{
    echo "Not authorised";
}