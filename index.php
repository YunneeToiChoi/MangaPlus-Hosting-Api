<?php
require 'vendor/autoload.php';

if (isset($_POST['authKey']) && ($_POST['authKey'] == 'abc')) {
    // Lấy thông tin tên người dùng và giá của Manga Plus App từ yêu cầu POST
    $userName = isset($_POST['userName']) ? $_POST['userName'] : '';
    $amount = isset($_POST['amount']) ? $_POST['amount'] : '';

    // Khởi tạo Stripe với secret key
    $stripe = new \Stripe\StripeClient('sk_test_51OsiPhJSl4kaFANTv2e7tiGqrAWMTuwfpotd45nWI6YeCbboGeLAykIfpfv4MycQTuHuXIto5r32SFZxaDgnKEYf00MBzOYfdE');

    // Tạo khách hàng Stripe với tên người dùng và địa chỉ mặc định
    $customer = $stripe->customers->create([
        'name' => $userName,
        'address' => [
            'line1' => 'demo address',
            'postal_code' => '738933',
            'city' => 'NewYork',
            'state' => 'NY',
            'country' => 'US',
        ]
    ]);

    // Tạo ephemeral key cho khách hàng với phiên bản API Stripe được chỉ định
    $ephemeralKey = $stripe->ephemeralKeys->create([
        'customer' => $customer->id,
    ], [
        'stripe_version' => '2023-10-16',
    ]);

    // Tạo payment intent với số tiền và thông tin khách hàng đã cung cấp
    $paymentIntent = $stripe->paymentIntents->create([
        'amount' => $amount,
        'currency' => 'usd',
        'description' => 'Payment for MangaPlus App',
        'customer' => $customer->id,
        'automatic_payment_methods' => [
            'enabled' => 'true',
        ],
    ]);

    // Thiết lập phản hồi HTTP và gửi dữ liệu JSON về máy khách
    http_response_code(200);
    echo json_encode([
        'paymentIntent' => $paymentIntent->client_secret,
        'ephemeralKey' => $ephemeralKey->secret,
        'customer' => $customer->id,
        'publishableKey' => 'pk_test_51OsiPhJSl4kaFANTuqY3Tw9yPOIqUMaPwkFbuKvbLjCeQC0njrHdH810sCyqbcK2Im1IZOK9euVvSu8gRjZcCEgE002RvYJJ5h'
    ]);
} else {
    // Trả về thông báo không được ủy quyền nếu authKey không hợp lệ
    echo "Not authorised";
}
?>
