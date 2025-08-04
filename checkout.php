<?php
include 'dbconnect.php';
include 'header.php';
require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function sendOrderConfirmationEmail($email, $orderDetails) {
    $apiKey = $_ENV['MAILERSEND_API_KEY'];
    $senderEmail = $_ENV['MAILERSEND_SENDER_EMAIL'];
    $senderName = $_ENV['MAILERSEND_SENDER_NAME'];

    $payload = [
        'from' => ['email' => $senderEmail, 'name' => $senderName],
        'to' => [['email' => $email]],
        'subject' => 'Order Details - 24LashEnvy',
        'html' => $orderDetails
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.mailersend.com/v1/email');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        error_log("MailerSend Error: " . $err);
        return false;
    }

    return true;
}

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to proceed with the checkout.'); window.location.href = 'login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    $stmt_user = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt_user->execute(['user_id' => $user_id]);
    $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

    if (!$user || empty($user['address']) || empty($user['city']) || empty($user['postal_code']) || empty($user['country'])) {
        echo "<script>alert('Please fill in your address details in your account.'); window.location.href = 'my-account.php';</script>";
        exit;
    }

    $cart_total = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product) {
            $cart_total += $product['price'] * $product['qty'];
        }
    }

    if ($cart_total <= 0) {
        echo "<script>alert('Your cart is empty or invalid.'); window.location.href = 'cart.php';</script>";
        exit;
    }

    $stmt_order = $pdo->prepare("
        INSERT INTO orders (user_id, first_name, last_name, email, telephone, address, city, postal_code, country, total, payment_method, status)
        VALUES (:user_id, :first_name, :last_name, :email, :telephone, :address, :city, :postal_code, :country, :total, :payment_method, 'unpaid')
    ");
    $stmt_order->execute([
        'user_id' => $user_id,
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'email' => $user['email'],
        'telephone' => $user['telephone'],
        'address' => $user['address'],
        'city' => $user['city'],
        'postal_code' => $user['postal_code'],
        'country' => $user['country'],
        'total' => $cart_total,
        'payment_method' => $_POST['payment_method']
    ]);
    $order_id = $pdo->lastInsertId();

    foreach ($_SESSION['cart'] as $product_id => $product) {
        $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
            VALUES (:order_id, :product_id, :product_name, :quantity, :price)");
        $stmt_item->execute([
            'order_id' => $order_id,
            'product_id' => $product_id,
            'product_name' => $product['product_name'],
            'quantity' => $product['qty'],
            'price' => $product['price']
        ]);
    }

    $orderDetails = "<h2>Order Details</h2>";
    $orderDetails .= "<p><strong>Order ID:</strong> {$order_id}</p>";
    $orderDetails .= "<p><strong>Total:</strong> NGN " . number_format($cart_total, 2) . "</p>";
    $orderDetails .= "<p><strong>Payment:</strong> {$_POST['payment_method']}</p>";
    $orderDetails .= "<h3>Customer:</h3>";
    $orderDetails .= "<p>{$user['first_name']} {$user['last_name']}<br>{$user['email']}<br>{$user['telephone']}</p>";
    $orderDetails .= "<p>{$user['address']}, {$user['city']}, {$user['postal_code']}, {$user['country']}</p>";
    $orderDetails .= "<h3>Items:</h3><ul>";
    foreach ($_SESSION['cart'] as $product) {
        $orderDetails .= "<li>{$product['product_name']} - Qty: {$product['qty']} - NGN " . number_format($product['price'], 2) . "</li>";
    }
    $orderDetails .= "</ul><p>Thank you for shopping with 24LashEnvy!</p>";

    sendOrderConfirmationEmail($user['email'], $orderDetails);

    unset($_SESSION['cart']);

    if ($_POST['payment_method'] === 'online_payment') {
        $public_key = $_ENV['PAYSTACK_PUBLIC_KEY'];
        $totalAmount = number_format($cart_total, 2, '.', '') * 100;

        echo '<script src="https://js.paystack.co/v1/inline.js"></script>';
        echo "<script>
            let handler = PaystackPop.setup({
                key: '{$public_key}',
                email: '{$user['email']}',
                amount: {$totalAmount},
                currency: 'NGN',
                firstname: '{$user['first_name']}',
                lastname: '{$user['last_name']}',
                metadata: {
                    custom_fields: [
                        {
                            display_name: 'Order ID',
                            variable_name: 'order_id',
                            value: '{$order_id}'
                        }
                    ]
                },
                callback: function(response) {
                    window.location.href = 'verify-payment.php?reference=' + response.reference;
                },
                onClose: function() {
                    alert('Payment window closed.');
                }
            });
            setTimeout(() => handler.openIframe(), 2000);
        </script>";
        exit;
    } else {
        echo "<script>setTimeout(function() {
            window.location.href = 'order-success.php?order_id={$order_id}';
        }, 2000);</script>";
        exit;
    }
}
?>

<section class="checkout-area ptb-90 text-center">
    <div class="container">
        <div class="checkout-section">
            <h4>Placing your order...</h4>
            <p>Please wait while we finalize your order.</p>
            <img src="assets/images/loader.gif" alt="Processing..." style="width:80px;">
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
