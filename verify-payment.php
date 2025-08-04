<?php
require 'vendor/autoload.php';
include 'dbconnect.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (!isset($_GET['reference'])) {
    die("No payment reference provided.");
}

$reference = $_GET['reference'];
$secretKey = $_ENV['PAYSTACK_SECRET_KEY'];

// Verify with Paystack
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co/transaction/verify/" . rawurlencode($reference));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $secretKey",
    "Cache-Control: no-cache",
]);

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    die("cURL Error: $err");
}

$result = json_decode($response, true);

if ($result && $result['status'] && $result['data']['status'] === 'success') {
    $order_id = $result['data']['metadata']['custom_fields'][0]['value'] ?? null;

    if (!$order_id) {
        die("Order ID not found in metadata.");
    }

    // Optional: Add transaction_reference column, or remove it here
    $stmt = $pdo->prepare("UPDATE orders SET status = 'paid' WHERE order_id = :order_id");
    $stmt->execute([
        'order_id' => $order_id
    ]);

    echo "<script>alert('Payment successful!'); window.location.href = 'order-success.php?order_id=$order_id';</script>";
    exit;
} else {
    echo "<script>alert('Payment verification failed!'); window.location.href = 'cart.php';</script>";
    exit;
}
