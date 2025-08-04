<?php
include 'header.php';
include 'dbconnect.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to manage your orders.'); window.location.href = 'login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle order cancellation
if (isset($_GET['cancel_order'])) {
    $order_id = $_GET['cancel_order'];

    // Mark order as cancelled (soft delete)
    $stmt_cancel_order = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE order_id = :order_id AND user_id = :user_id");
    $stmt_cancel_order->execute([
        'order_id' => $order_id,
        'user_id' => $user_id
    ]);

    // Fetch user details for email
    $stmt_user = $pdo->prepare("SELECT email, first_name, last_name FROM users WHERE user_id = :user_id");
    $stmt_user->execute(['user_id' => $user_id]);
    $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

    $orderDetails = "<h2>Order Cancellation</h2>";
    $orderDetails .= "<p>Dear {$user['first_name']} {$user['last_name']},</p>";
    $orderDetails .= "<p>Your order with ID #{$order_id} at 24LashEnvy has been successfully cancelled.</p>";
    $orderDetails .= "<p>If you have any questions, feel free to contact us.</p>";

    // Send cancellation email using MailerSend
    $apiKey = $_ENV['MAILERSEND_API_KEY'];
    $senderEmail = $_ENV['MAILERSEND_SENDER_EMAIL'];
    $senderName = $_ENV['MAILERSEND_SENDER_NAME'];

    $payload = [
        'from' => ['email' => $senderEmail, 'name' => $senderName],
        'to' => [['email' => $user['email']]],
        'subject' => '24LashEnvy: Order Cancellation Confirmation',
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
    curl_exec($ch);
    curl_close($ch);

    $message = 'Order successfully cancelled and a confirmation email has been sent.';
}

// Fetch user's orders
$stmt = $pdo->prepare("
    SELECT o.order_id, o.total, o.created_at AS order_date, o.payment_method, o.status
    FROM orders o
    WHERE o.user_id = :user_id
    ORDER BY o.created_at DESC
");
$stmt->execute(['user_id' => $user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

function generatePDFLink($order_id) {
    return "<a href='admin/download-order-pdf.php?order_id={$order_id}' class='btn btn-outline-primary btn-sm'>Download PDF</a>";
}

function generatePaystackButton($order_id) {
    return "<a href='paystack/pay.php?order_id={$order_id}' class='btn-custom btn-primary-custom'><i class='zmdi zmdi-credit-card'></i> Pay Now</a>";
}
?>

<style>
.manage-orders-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    padding: 40px 0;
}

.orders-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 30px;
    margin-bottom: 30px;
}

.orders-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f8f9fa;
}

.orders-header h3 {
    color: #333;
    font-weight: 600;
    margin-bottom: 10px;
}

.orders-header p {
    color: #666;
    font-size: 16px;
    margin: 0;
}

.orders-table {
    overflow-x: auto;
}

.orders-table table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.orders-table thead th {
    background: #B23372;
    color: white;
    padding: 15px 12px;
    font-weight: 600;
    text-align: left;
    border: none;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.orders-table tbody td {
    padding: 15px 12px;
    border-bottom: 1px solid #f1f3f4;
    vertical-align: middle;
    font-size: 14px;
}

.orders-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

.orders-table tbody tr:last-child td {
    border-bottom: none;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-unpaid {
    background-color: #f8d7da;
    color: #721c24;
}

.status-paid {
    background-color: #d4edda;
    color: #155724;
}

.status-completed {
    background-color: #cce5ff;
    color: #004085;
}

.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-custom {
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-block;
    text-align: center;
}

.btn-primary-custom {
    background: #764ba2;
    color: white;
}

.btn-primary-custom:hover, .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: white !important;
}

.btn-danger-custom {
    background: #ee5a52;
    color: white;
}

.btn-danger-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
    color: white !important;
}

.btn-outline-custom {
    background: transparent;
    border: 2px solid #667eea;
    color: #667eea;
}

.btn-outline-custom:hover {
    background: #667eea;
    color: white !important;
    transform: translateY(-2px);
}

.btn-disabled {
    background: #e9ecef;
    color: #6c757d;
    cursor: not-allowed;
}

.btn-disabled:hover {
    transform: none;
    box-shadow: none;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 20px;
    color: #dee2e6;
}

.empty-state h4 {
    margin-bottom: 10px;
    color: #495057;
}

.alert-custom {
    border-radius: 10px;
    border: none;
    padding: 15px 20px;
    margin-bottom: 25px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

.alert-success-custom {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border-left: 4px solid #28a745;
}

@media (max-width: 768px) {
    .orders-card {
        padding: 20px;
        margin: 10px;
    }

    .container {
        width: 100% !important;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-custom {
        width: 100%;
        margin-bottom: 5px;
    }
}
</style>

<section class="breadcrumbs-area ptb-100 bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title">Manage Orders</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Manage Orders</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="manage-orders-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12">
                <div class="orders-card">
                    <div class="orders-header">
                        <h3><i class="zmdi zmdi-shopping-cart"></i> My Orders</h3>
                        <p>Track and manage all your orders in one place</p>
                    </div>

                    <?php if (isset($message)): ?>
                        <div class="alert alert-success-custom alert-custom">
                            <i class="zmdi zmdi-check-circle"></i> <?= $message ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($orders)): ?>
                        <div class="orders-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Total (NGN)</th>
                                        <th>Order Date</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><strong>#<?= $order['order_id'] ?></strong></td>
                                            <td><strong>₦<?= number_format($order['total'], 2) ?></strong></td>
                                            <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                                            <td><?= ucfirst(str_replace('_', ' ', $order['payment_method'])) ?></td>
                                            <td>
                                                <span class="status-badge status-<?= strtolower($order['status']) ?>">
                                                    <?= ucfirst($order['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <?php if ($order['status'] === 'unpaid' && $order['payment_method'] === 'online_payment'): ?>
                                                        <a href="proceed_to_payment.php?order_id=<?= $order['order_id'] ?>" class="btn-custom btn-primary-custom">
                                                            <i class="zmdi zmdi-credit-card"></i> Pay Now
                                                        </a>
                                                        <a href="?cancel_order=<?= $order['order_id'] ?>" 
                                                           onclick="return confirm('Are you sure you want to cancel this order?')"
                                                           class="btn-custom btn-danger-custom">
                                                            <i class="zmdi zmdi-close"></i> Cancel
                                                        </a>
                                                    <?php elseif ($order['status'] === 'pending'): ?>
                                                        <a href="?cancel_order=<?= $order['order_id'] ?>" 
                                                           onclick="return confirm('Are you sure you want to cancel this order?')"
                                                           class="btn-custom btn-danger-custom">
                                                            <i class="zmdi zmdi-close"></i> Cancel
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn-custom btn-disabled" disabled>
                                                            <i class="zmdi zmdi-block"></i> Cannot Cancel
                                                        </button>
                                                    <?php endif; ?>
                                                    <?= generatePDFLink($order['order_id']) ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="zmdi zmdi-shopping-cart"></i>
                            <h4>No Orders Yet</h4>
                            <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                            <a href="products.php" class="btn-custom btn-primary-custom">
                                <i class="zmdi zmdi-shopping-basket"></i> Browse Products
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>