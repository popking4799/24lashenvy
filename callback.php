<?php
require 'vendor/autoload.php';
include 'dbconnect.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Check if reference is present
if (!isset($_GET['reference'])) {
    die("No payment reference provided.");
}

$reference = $_GET['reference'];
$secretKey = $_ENV['PAYSTACK_SECRET_KEY'];

// Verify payment via Paystack API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co/transaction/verify/" . rawurlencode($reference));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $secretKey",
    "Cache-Control: no-cache"
]);

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    die("cURL Error: $err");
}

$result = json_decode($response, true);

if (!$result || !$result['status'] || $result['data']['status'] !== 'success') {
    echo "<script>alert('Payment verification failed.'); window.location.href = '../manage_orders.php';</script>";
    exit;
}

// Extract order ID from reference: ORD-123
if (!preg_match('/ORD-(\\d+)/', $reference, $matches)) {
    echo "<script>alert('Invalid payment reference format.'); window.location.href = '../manage_orders.php';</script>";
    exit;
}

$order_id = $matches[1];

// Update order status in DB
$stmt = $pdo->prepare("UPDATE orders SET status = 'paid', transaction_reference = :reference WHERE order_id = :order_id");
$stmt->execute([
    'reference' => $reference,
    'order_id' => $order_id
]);

// Redirect to order success page
header("Location: ../order-success.php?order_id=" . $order_id);
exit;
