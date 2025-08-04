<?php
include 'dbconnect.php';
include 'header.php';

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    echo "<script>alert('Invalid request.'); window.location.href='index.php';</script>";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = :id");
$stmt->execute(['id' => $order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "<script>alert('Order not found.'); window.location.href='index.php';</script>";
    exit;
}
?>

<style>
.success-wrapper {
    max-width: 500px;
    margin: 0 auto;
    padding: 40px 20px;
    background-color: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.success-icon {
    font-size: 60px;
    color: #28a745;
    margin-bottom: 20px;
}

.success-wrapper h4 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #333;
}

.success-wrapper p {
    font-size: 16px;
    color: #555;
    margin-bottom: 10px;
}

.order-info {
    margin-top: 20px;
    font-size: 16px;
    color: #444;
}

.order-info strong {
    color: #000;
}

.success-btn {
    display: inline-block;
    margin-top: 25px;
    padding: 10px 24px;
    background-color: #28a745;
    color: #fff;
    border-radius: 6px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.success-btn:hover {
    background-color: #218838;
}
</style>

<section class="checkout-area ptb-90 text-center">
    <div class="container">
        <div class="success-wrapper">
            <div class="success-icon">✅</div>
            <h4>Thank you for your order!</h4>
            <p>Your payment was successful.</p>

            <div class="order-info">
                <p><strong>Order ID:</strong> <?= htmlspecialchars($order['order_id']) ?></p>
                <p><strong>Total Paid:</strong> NGN <?= number_format($order['total'], 2) ?></p>
            </div>

            <a class="success-btn" href="index.php">Continue Shopping</a>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
