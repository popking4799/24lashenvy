<?php
include 'dbconnect.php';
include 'header.php';
require 'vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to proceed with payment.'); window.location.href = 'login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    echo "<script>alert('No order found.'); window.location.href = 'manage_orders.php';</script>";
    exit;
}

$order_id = $_GET['order_id'];

// Fetch the order details
$stmt_order = $pdo->prepare("SELECT * FROM orders WHERE order_id = :order_id AND user_id = :user_id");
$stmt_order->execute(['order_id' => $order_id, 'user_id' => $user_id]);
$order = $stmt_order->fetch(PDO::FETCH_ASSOC);

if (!$order || $order['status'] !== 'unpaid') {
    echo "<script>alert('This order is not available for payment.'); window.location.href = 'manage_orders.php';</script>";
    exit;
}

// Fetch user details
$stmt_user = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt_user->execute(['user_id' => $user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Handle payment method logic
if ($order['payment_method'] === 'online_payment') {
    $public_key = $_ENV['PAYSTACK_PUBLIC_KEY'];
    $callback_url = $_ENV['PAYSTACK_CALLBACK_URL'];
    $totalAmount = number_format($order['total'], 2, '.', '') * 100;
    $reference = 'ORD-' . $order_id;

    echo '<script src="https://js.paystack.co/v1/inline.js"></script>';
    echo "<script>
        let handler = PaystackPop.setup({
            key: '{$public_key}',
            email: '{$user['email']}',
            amount: {$totalAmount},
            currency: 'NGN',
            firstname: '{$user['first_name']}',
            lastname: '{$user['last_name']}',
            reference: '{$reference}',
            callback: function(response) {
                window.location.href = '{$callback_url}?reference=' + response.reference;
            },
            onClose: function() {
                alert('Payment window closed.');
                window.location.href = 'manage_orders.php';
            }
        });
        setTimeout(() => handler.openIframe(), 1000);
    </script>";
    exit;

} elseif ($order['payment_method'] === 'cod') {
    // Handle Cash on Delivery (COD)
    echo "<script>alert('Your order is marked as Cash on Delivery. You will be contacted for delivery.'); window.location.href = 'manage_orders.php';</script>";
    exit;
}
?>

<section class="checkout-area ptb-90">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="checkout-section">
                    <h4>Processing your order payment...</h4>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
